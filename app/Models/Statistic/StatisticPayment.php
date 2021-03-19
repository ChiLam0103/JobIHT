<?php

namespace App\Models\Statistic;

header('Content-Type: text/html; charset=utf-8');

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\JobD;

class StatisticPayment extends Model
{
    //1.phieu thu chi
    public static function advance($advance)
    {
        try {
            $data = DB::table('LENDER as l')
                ->leftJoin('CUSTOMER as c', 'l.CUST_NO', 'c.CUST_NO')
                ->leftJoin('LENDER_TYPE as lt', 'lt.LENDER_TYPE', 'l.LENDER_TYPE')
                ->leftJoin('PERSONAL as p', 'p.PNL_NO', 'l.PNL_NO')
                ->where('l.LENDER_DATE', '>=', '20190101')
                ->where('l.LENDER_NO', $advance)
                ->where('p.BRANCH_ID', 'IHTVN1')
                ->select('c.CUST_NAME', 'p.PNL_NAME as PNAME', 'lt.LENDER_NAME', 'l.*')
                ->first();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function advance_D($advance)
    {
        try {
            $data = DB::table('LENDER_D as ld')
                ->where('ld.BRANCH_ID', 'IHTVN1')
                ->where('ld.LENDER_NO', $advance)
                ->select('ld.*')->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    //1.1thống kê phiếu bù và phiếu trả
    public static function postReplenishmentWithdrawalPayment($advanceno)
    {
        try {
            $data =  DB::table('LENDER as l')
                ->where('l.LENDER_DATE', '>=', '20190101')
                ->where('l.BRANCH_ID', 'IHTVN1')
                ->whereIn('L.LENDER_NO', $advanceno)
                ->select('l.*')->get();
            foreach ($data as $item) {
                $SUM_LENDER_AMT = 0; //tien ung
                $SUM_JOB_ORDER = 0; //tien job order
                $advance_d = StatisticPayment::advance_D($item->LENDER_NO);
                $job_d = JobD::getJob($item->JOB_NO, "JOB_ORDER")->whereIn('jd.THANH_TOAN_MK', [null, 'N']);

                foreach ($advance_d as $i) {
                    $SUM_LENDER_AMT += $i->LENDER_AMT;
                }
                foreach ($job_d as $i) {
                    $SUM_JOB_ORDER += $i->PORT_AMT + $i->INDUSTRY_ZONE_AMT;
                }
                //kiem tra phieu chi truc tiep
                if ($item->LENDER_TYPE == 'C') {
                    $item->SUM_LENDER_AMT = 0; //tong ung
                    $item->SUM_DIRECT = $SUM_JOB_ORDER; //chi truc tiep
                    $item->SUM_JOB_ORDER = $SUM_JOB_ORDER; //tong job
                    $item->SUM_REPLENISHMENT_WITHDRAWAL = -$SUM_JOB_ORDER; //tong bu tra
                } else {
                    $item->SUM_LENDER_AMT = $SUM_LENDER_AMT; //tong ung
                    $item->SUM_DIRECT = 0; //chi truc tiep
                    $item->SUM_JOB_ORDER = $SUM_JOB_ORDER; //tong job
                    $item->SUM_REPLENISHMENT_WITHDRAWAL = $SUM_LENDER_AMT - $SUM_JOB_ORDER; //tong bu tra
                }
            }
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function getReplenishmentWithdrawalPayment($advanceno)
    {
        try {
            $str = json_decode($advanceno);
            $data =  DB::table('LENDER as l')
                ->where('l.LENDER_DATE', '>=', '20190101')
                ->where('l.BRANCH_ID', 'IHTVN1')
                ->whereIn('L.LENDER_NO', $str)
                ->select('l.*')->get();
            foreach ($data as $item) {
                $SUM_LENDER_AMT = 0; //tien ung
                $SUM_JOB_ORDER = 0; //tien job order
                $advance_d = StatisticPayment::advance_D($item->LENDER_NO);
                $job_d = JobD::getJob($item->JOB_NO, "JOB_ORDER")->whereIn('jd.THANH_TOAN_MK', [null, 'N']);

                foreach ($advance_d as $i) {
                    $SUM_LENDER_AMT += $i->LENDER_AMT;
                }
                foreach ($job_d as $i) {
                    $SUM_JOB_ORDER += $i->PORT_AMT + $i->INDUSTRY_ZONE_AMT;
                }
                //kiem tra phieu chi truc tiep
                if ($item->LENDER_TYPE == 'C') {
                    $item->SUM_LENDER_AMT = 0; //tong ung
                    $item->SUM_DIRECT = $SUM_JOB_ORDER; //chi truc tiep
                    $item->SUM_JOB_ORDER = $SUM_JOB_ORDER; //tong job
                    $item->SUM_REPLENISHMENT_WITHDRAWAL = -$SUM_JOB_ORDER; //tong bu tra
                } else {
                    $item->SUM_LENDER_AMT = $SUM_LENDER_AMT; //tong ung
                    $item->SUM_DIRECT = 0; //chi truc tiep
                    $item->SUM_JOB_ORDER = $SUM_JOB_ORDER; //tong job
                    $item->SUM_REPLENISHMENT_WITHDRAWAL = $SUM_LENDER_AMT - $SUM_JOB_ORDER; //tong bu tra
                }
            }
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    //2 phiếu yêu cầu thanh toán
    public static function postDebitNote($request)
    {
        try {
            $data = '';
            $total_amt = 0;
            $today = date("Ymd");
            $fromdate = $request->fromdate != 'null' ? $request->fromdate : '19000101';
            $todate = $request->todate != 'null' ? $request->todate : $today;
            $check_phone = ($request->phone == null || $request->phone == 'undefined' || $request->phone == 'null') ? 0 : 1;
            $check_person = ($request->person == null || $request->person == 'undefined' || $request->person == 'null') ? 0 : 1;
            $check_jobno = ($request->jobno == null || $request->jobno == 'undefined' || $request->jobno == 'null') ? 0 : 1;
            $check_custno = ($request->custno == null || $request->custno == 'undefined' || $request->custno == 'null') ? 0 : 1;
            $check_debittype = ($request->debittype == null || $request->debittype == 'undefined' || $request->debittype == 'null') ? 0 : 1;
            $check_fromdate = ($request->fromdate == null || $request->fromdate == 'undefined' || $request->fromdate == 'null') ? 0 : 1;
            $check_todate = ($request->todate == null || $request->todate == 'undefined' || $request->todate == 'null') ? 0 : 1;

            switch ($request->type) {
                case 'job':
                    if ($check_phone == 0) {
                        $data = 'error-phone';
                        return $data;
                    }
                    if ($check_person == 0) {
                        $data = 'error-person-empty';
                        return $data;
                    }
                    if ($check_jobno == 0) {
                        $data = 'error-job-empty';
                        return $data;
                    }

                    if ($request->jobno && $request->person && $request->phone) {
                        $query = StatisticPayment::query();
                        $data = $query->whereIn('dnm.JOB_NO', $request->jobno)->first();
                        return $data;
                    } else {
                        return 201;
                    }
                    break;
                case 'customer':
                    if ($check_custno == 0) {
                        $data = 'error-custno';
                        return $data;
                    }
                    if ($check_phone == 0) {
                        $data = 'error-phone';
                        return $data;
                    }
                    if ($check_person == 0) {
                        $data = 'error-person-empty';
                        return $data;
                    }
                    if ($check_jobno == 0) {
                        $data = 'error-job-empty';
                        return $data;
                    }
                    if ($request->custno && $request->person && $request->phone) {
                        $query = StatisticPayment::query();
                        $data = $query->whereIn('dnm.JOB_NO', $request->jobno)->whereIn('dnm.CUST_NO', $request->custno)
                            ->select('dnm.*')->get();
                        foreach ($data as $item) {
                            $debit_d = StatisticPayment::postDebitNote_D('customer', null, null, $item->JOB_NO, null);
                            $item->debit_d = $debit_d;
                        }
                        return $data;
                    }
                    break;
                case 'customer_new':
                    if ($check_custno == 0) {
                        $data = 'error-custno';
                        return $data;
                    }
                    if ($check_phone == 0) {
                        $data = 'error-phone';
                        return $data;
                    }
                    if ($check_person == 0) {
                        $data = 'error-person-empty';
                        return $data;
                    }
                    if ($check_jobno == 0) {
                        $data = 'error-job-empty';
                        return $data;
                    }
                    if ($request->custno && $request->person && $request->phone) {
                        $query = StatisticPayment::query();
                        $data = $query->whereIn('dnm.JOB_NO', $request->jobno)->whereIn('dnm.CUST_NO', $request->custno)->select('dnm.*')->get();
                        foreach ($data as $item) {
                            $debit_d = StatisticPayment::postDebitNote_D('customer', null, null, $item->JOB_NO, null);
                            $item->debit_d = $debit_d;
                        }
                        return $data;
                    }
                    break;
                case  'debit_date':
                    if ($check_fromdate == 0 || $check_todate == 0) {
                        $data = 'error-date';
                        return $data;
                    } elseif ($check_debittype == 0) {
                        $data = 'error-debittype';
                        return $data;
                    } else {
                        //  $debittype= our_company_pay, pay_in_advance, all
                        $query = DB::table('DEBIT_NOTE_M as dnm')
                            ->leftJoin('DEBIT_NOTE_D as dnd', 'dnm.JOB_NO', 'dnd.JOB_NO')
                            ->where('dnm.DEBIT_DATE', '>=', '20190101')
                            ->whereBetween('dnm.DEBIT_DATE', [$fromdate, $todate])
                            ->where('dnm.BRANCH_ID', 'IHTVN1')
                            ->whereNotNull('dnm.JOB_NO');
                        if ($request->debittype == "our_company_pay") {
                            $query->where('dnd.DEB_TYPE', 'Our Company Pay');
                        } elseif ($request->debittype == "pay_in_advance") {
                            $query->where('dnd.DEB_TYPE', 'Pay In Advance');
                        }
                        $data = $query->select('dnm.JOB_NO', 'dnm.CUST_NO', 'dnm.DEBIT_DATE as DEBIT_DATE_M')->distinct()->get();
                        return $data;
                    }
                    break;
                default:
                    break;
            }
        } catch (\Exception $e) {
            dd($e);
            return $e;
        }
    }
    public static function postDebitNote_D($type, $fromdate, $todate, $jobno, $debittype)
    {
        $query = DB::table('DEBIT_NOTE_D as dnd')
            ->where('dnd.BRANCH_ID', 'IHTVN1')
            ->where('dnd.JOB_NO', $jobno);
        if ($type == 'debit_date') {
            $query->leftJoin('DEBIT_NOTE_M as dnm', 'dnm.JOB_NO', 'dnd.JOB_NO')
                ->where('dnm.DEBIT_DATE', '>=', '20190101')
                ->whereBetween('dnm.DEBIT_DATE', [$fromdate, $todate]);
            if ($debittype == "our_company_pay") {
                $query->where('dnd.DEB_TYPE', 'Our Company Pay');
            } elseif ($debittype == "pay_in_advance") {
                $query->where('dnd.DEB_TYPE', 'Pay In Advance');
            }
        }
        $data = $query->select('dnd.*')->get();
        return $data;
    }
    //4. báo biểu lợi nhuận
    public static function profit($type, $jobno, $custno, $fromdate, $todate)
    {
        try {
            $check_date = (($fromdate == null || $fromdate == 'undefined' || $fromdate == 'null') && ($todate == null || $todate == 'undefined' || $todate == 'null') || $fromdate > $todate) ? 0 : 1;
            $check_custno = ($custno == null || $custno == 'undefined' || $custno == 'null') ? 0 : 1;
            $query = DB::table('DEBIT_NOTE_M as dm')
                ->leftJoin('CUSTOMER as c', 'dm.CUST_NO', 'c.CUST_NO')
                ->where('dm.BRANCH_ID', 'IHTVN1')
                ->where('c.BRANCH_ID', 'IHTVN1')
                ->where('dm.DEBIT_DATE', '>=', '20190101')
                ->orderBy('dm.JOB_NO')
                ->select('c.CUST_NAME', 'dm.JOB_NO', 'dm.CUST_NO');
            if ($check_date == 1) {
                $query->whereBetween('dm.DEBIT_DATE', [$fromdate, $todate]);
            }
            switch ($type) {
                case 'all':

                    break;
                case 'jobno':
                    $query->where('dm.JOB_NO', $jobno);
                    break;
                case 'customer':
                    if ($check_custno == 1) {
                        $query->where('dm.CUST_NO', $custno);
                    }
                    break;
            }
            $data = $query->leftJoin('DEBIT_NOTE_D as dnd', 'dnd.JOB_NO', 'dm.JOB_NO')
                ->selectRaw("sum(dnd.QUANTITY * dnd.PRICE) as TIEN_THANH_TOAN")
                ->groupBy('c.CUST_NAME', 'dm.JOB_NO', 'dm.CUST_NO')->get();
            foreach ($data as $item) {
                $item->job_d = StatisticPayment::profitJobOrderD($item->JOB_NO);
            }
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public static function profitJobOrderD($jobno)
    {

        $data = DB::table('JOB_ORDER_D as jd')
            ->where('jd.JOB_NO', $jobno)
            ->selectRaw("sum(CASE WHEN (jd.QTY = 0) THEN jd.PRICE ELSE jd.PRICE * jd.QTY  END) as CHI_PHI_BOOK_TAU")
            ->selectRaw("sum(jd.PORT_AMT + jd.INDUSTRY_ZONE_AMT)  as CHI_PHI_JOB")
            ->selectRaw("sum(CASE WHEN (jd.ORDER_TYPE = 'C') THEN jd.PRICE ELSE 0 END)  as SUM_DEPOSIT_FEE")
            ->selectRaw("sum(CASE WHEN (jd.ORDER_TYPE = '8' ) THEN jd.PRICE ELSE 0 END)  as SUM_DEPOSIT_FIX_FEE")
            ->where('jd.BRANCH_ID', 'IHTVN1')
            ->get();
        // dd($data);

        return $data;
    }
    //5. thống kê số job trong tháng
    public static function jobMonthly($type, $custno, $fromdate, $todate)
    {
        try {
            $error_customer = 0;
            if (($fromdate == null || $fromdate == 'undefined' || $fromdate == 'null') && ($todate == null || $todate == 'undefined' || $todate == 'null') || $fromdate > $todate) {
                $data = 'error-date';
                return $data;
            }
            if ($custno == null || $custno == 'undefined' || $custno == 'null') {
                $error_customer = 1;
            }
            $data = '';
            $table_name = '';
            $table_name = ($type == 'job_start') || ($type == 'job_pay') ? 'JOB_START' : ($type == 'job_order' ? 'JOB_ORDER_M' : 'DEBIT_NOTE_M');
            $table_date = ($type == 'job_start') || ($type == 'job_pay') ? 'JOB_DATE' : ($type == 'job_order' ? 'ORDER_DATE' : 'DEBIT_DATE');
            $query = DB::table($table_name . ' as job')
                ->leftJoin('CUSTOMER as c', 'job.CUST_NO', 'c.CUST_NO')
                ->where('job.BRANCH_ID', 'IHTVN1')
                ->where('c.BRANCH_ID', 'IHTVN1')
                ->where('job.' . $table_date, '>=', '20190101')
                ->whereBetween('job.' . $table_date, [$fromdate, $todate])->select('c.CUST_NAME', 'job.*')->orderBy('job.JOB_NO');
            if ($error_customer == 0) {
                $query->where('job.CUST_NO', $custno);
            }
            // if ($type == 'job_pay') {
            //   $query->leftJoin('JOB_ORDER_D as jd','jd.JOB_NO','job.JOB_NO')
            // //   ->where('jd.BRANCH_ID', 'IHTVN1')
            //   ->selectRaw('sum(jd.PORT_AMT) as SUM_PORT_AMT')
            //   ->selectRaw('sum(jd.INDUSTRY_ZONE_AMT) as SUM_INDUSTRY_ZONE_AMT');
            // }
            $data = $query->get();
            // dd($data);
            if ($type == 'job_pay') {
                foreach ($data as $item) {
                    $job_d = StatisticPayment::jobMonthly_jobOrderD($item->JOB_NO);
                    $lender_d = StatisticPayment::jobMonthly_lenderD($item->JOB_NO);
                    $item->SUM_PORT_AMT = $job_d->SUM_PORT_AMT;
                    $item->SUM_INDUSTRY_ZONE_AMT = $job_d->SUM_INDUSTRY_ZONE_AMT;
                    $item->SUM_LENDER_AMT = $lender_d->SUM_LENDER_AMT;
                }
            }

            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function jobMonthly_jobOrderD($jobno)
    {
        $data = DB::table('JOB_ORDER_D as jd')
            ->where('jd.BRANCH_ID', 'IHTVN1')
            ->where('jd.JOB_NO', $jobno)
            ->selectRaw('sum(jd.PORT_AMT) as SUM_PORT_AMT')
            ->selectRaw('sum(jd.INDUSTRY_ZONE_AMT) as SUM_INDUSTRY_ZONE_AMT')
            ->first();
        return $data;
    }
    public static function jobMonthly_lenderD($jobno)
    {
        $data = DB::table('LENDER as l')
            ->leftJoin('LENDER_D as ld', 'l.LENDER_NO', 'ld.LENDER_NO')
            ->where('l.BRANCH_ID', 'IHTVN1')
            ->where('l.JOB_NO', $jobno)
            ->selectRaw('sum(ld.LENDER_AMT) as SUM_LENDER_AMT')
            ->first();
        return $data;
    }
    //6. thong ke thanh toan cua khach hang
    public static function paymentCustomers($type, $custno, $fromdate, $todate)
    {
        try {
            if (($fromdate == null || $fromdate == 'undefined' || $fromdate == 'null') && ($todate == null || $todate == 'undefined' || $todate == 'null') || $fromdate > $todate) {
                $data = 'error-date';
                return $data;
            } elseif ($custno == null || $custno == 'undefined' || $custno == 'null') {
                $data = 'error-custno';
                return $data;
            }
            $query = DB::table('DEBIT_NOTE_M as dm')
                ->leftJoin('CUSTOMER as c', 'dm.CUST_NO', 'c.CUST_NO')
                ->where('dm.BRANCH_ID', 'IHTVN1')
                ->where('c.BRANCH_ID', 'IHTVN1')
                ->where('dm.DEBIT_DATE', '>=', '20190101')
                ->whereBetween('dm.DEBIT_DATE', [$fromdate, $todate])
                ->orderBy('dm.JOB_NO');


            switch ($type) {
                case 'unpaid':
                    $query->where(function ($query) {
                        $query->where('dm.PAYMENT_CHK', 'N')
                            ->orWhere('dm.PAYMENT_CHK', null);
                    });
                    break;
                case  'paid':
                    $query->where('dm.PAYMENT_CHK', 'Y');
                    break;
                default:
                    break;
            }
            if ($custno) {
                $query->where('dm.CUST_NO', $custno);
            }
            $data = $query->distinct()->take(9000)
                ->select('c.CUST_NAME', 'dm.JOB_NO', 'dm.CUST_NO', 'dm.DEBIT_DATE')
                ->get();
            // foreach ($data as $item) {
            //     $query_d = DB::table('DEBIT_NOTE_D as dd')
            //         ->where('dd.JOB_NO', $item->JOB_NO)
            //         // ->selectRaw("sum( (CASE WHEN (dd.TAX_NOTE = '0%') OR (dd.TAX_NOTE = '10%') OR (dd.TAX_NOTE = '') THEN  (dd.QUANTITY * dd.PRICE)  ELSE (dd.QUANTITY * dd.PRICE) + (dd.QUANTITY * dd.PRICE) * dd.TAX_NOTE/100 END)) as TOTAL_AMT")
            //         ->first();
            //     // dd($query_d->TOTAL_AMT);
            //     // $item->TOTAL_AMT = $query_d->TOTAL_AMT;
            // }
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    //7. thong ke job order
    public static function jobOrder($type, $custno, $person, $fromdate, $todate)
    {
        try {
            $flag_date = (($fromdate == null || $fromdate == 'undefined' || $fromdate == 'null') && ($todate == null || $todate == 'undefined' || $todate == 'null') || $fromdate > $todate) ? 0 : 1;
            $flag_custno = ($custno == null || $custno == 'undefined' || $custno == 'null') ? 0 : 1;
            $flag_person = ($person == null || $person == 'undefined' || $person == 'null') ? 0 : 1;
            $query = DB::table('JOB_ORDER_M as jm')
                ->leftJoin('CUSTOMER as c', 'jm.CUST_NO', 'c.CUST_NO')
                ->where('jm.BRANCH_ID', 'IHTVN1')
                ->where('c.BRANCH_ID', 'IHTVN1')
                ->where('jm.ORDER_DATE', '>=', '20190101')
                ->orderBy('jm.JOB_NO');
            if ($flag_custno == 1) {
                $query->where('jm.CUST_NO', $custno);
            }
            if ($flag_date == 1) {
                $query->whereBetween('jm.ORDER_DATE', [$fromdate, $todate]);
            }
            if ($flag_person == 1) {
                $query->where('jm.INPUT_USER', $person);
            }
            switch ($type) {
                case 'truck_fee':
                    $query->leftJoin('JOB_ORDER_D as jd', 'jm.JOB_NO', 'jd.JOB_NO')
                        ->where('jd.ORDER_TYPE', 'T')
                        ->select('c.CUST_NAME', 'jm.JOB_NO', 'jm.CUST_NO', 'jm.ORDER_FROM', 'jm.ORDER_TO', 'jm.INPUT_USER', 'jd.DESCRIPTION', 'jd.UNIT', 'jd.QTY', 'jd.PRICE', 'jd.TAX_AMT');
                    break;
                case  'have_not_debit_note':
                    $query->leftJoin('DEBIT_NOTE_M as dm', 'jm.JOB_NO', 'dm.JOB_NO')
                        ->whereNull('dm.JOB_NO')
                        ->where('dm.DEBIT_DATE', '>=', '20190101')
                        ->select('c.CUST_NAME', 'jm.*');
                    break;
                case  'unpaid_cont':
                    $query->leftJoin('JOB_ORDER_D as jd', 'jm.JOB_NO', 'jd.JOB_NO')
                        ->where('jd.ORDER_TYPE', 'C')
                        ->where(function ($query) {
                            $query->where('jd.THANH_TOAN_MK', 'N')
                                ->orWhere('jd.THANH_TOAN_MK', null);
                        })
                        ->select('jm.JOB_NO', 'jm.CUST_NO', 'jm.ORDER_FROM', 'jm.ORDER_TO', 'jm.INPUT_USER', 'jd.DESCRIPTION', 'jd.PORT_AMT', 'jd.INDUSTRY_ZONE_AMT');
                    break;
                case  'paid_cont':
                    $query->leftJoin('JOB_ORDER_D as jd', 'jm.JOB_NO', 'jd.JOB_NO')
                        ->where('jd.ORDER_TYPE', 'C')
                        ->where('jd.THANH_TOAN_MK', 'Y')
                        ->select('jm.JOB_NO', 'jm.CUST_NO', 'jm.ORDER_FROM', 'jm.ORDER_TO', 'jm.INPUT_USER', 'jd.DESCRIPTION', 'jd.PORT_AMT', 'jd.INDUSTRY_ZONE_AMT');
                    break;
                default:
                    break;
            }
            $data = $query->take(9000)
                ->get();

            // dd($data);
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    //8. thống kê phiếu thu
    public static function receipt($type, $receiptno)
    {
        try {
            $query = DB::table('RECEIPT as r')
                ->leftJoin('CUSTOMER as c', 'c.CUST_NO', 'r.CUST_NO')
                ->where('r.BRANCH_ID', 'IHTVN1')
                ->where('r.RECEIPT_NO', $receiptno)
                ->select('c.CUST_NAME', 'c.CUST_ADDRESS', 'r.RECEIPT_NO', 'r.RECEIPT_DATE', 'r.RECEIPT_REASON', 'r.TOTAL_AMT', 'r.DOR_NO', 'r.TRANSFER_FEES')
                ->first();
            return $query;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public static function query()
    {
        $data = DB::table('DEBIT_NOTE_M as dnm')
            ->leftJoin('CUSTOMER as c', 'dnm.CUST_NO', 'c.CUST_NO')
            ->where('dnm.BRANCH_ID', 'IHTVN1')
            ->where('c.BRANCH_ID', 'IHTVN1')
            ->where('dnm.DEBIT_DATE', '>=', '20190101')
            ->orderBy('dnm.JOB_NO');
        return $data;
    }
    public static function test($advanceno)
    {
        $data =  DB::table('LENDER as l')
            ->whereIn('L.LENDER_NO', $advanceno)
            ->where('l.BRANCH_ID', 'IHTVN1')
            ->where('l.LENDER_DATE', '>=', '20190101')
            ->select('l.*')->take(10)->get();
        $data_d = DB::table('LENDER_D as ld')
            ->where('ld.BRANCH_ID', 'IHTVN1')
            ->whereIn('ld.LENDER_NO', $advanceno)
            ->select('ld.*')->get();
        $array = $data['0']->push($data_d);
        // dd($data,$advance_d);
        // $array = $data->merge($data_d);
        // $array = array_add_multiple($data, array($data_d));
        dd($array);
        return $data;
    }
}

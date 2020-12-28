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
                ->rightJoin('LENDER as l', 'l.LENDER_NO', 'ld.LENDER_NO')
                ->where('l.BRANCH_ID', 'IHTVN1')
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
                ->whereIn('L.LENDER_NO', $advanceno)
                ->where('l.BRANCH_ID', 'IHTVN1')
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
                    $item->SUM_LENDER_AMT = 0;//tong ung
                    $item->SUM_DIRECT = $SUM_JOB_ORDER;//chi truc tiep
                    $item->SUM_JOB_ORDER = $SUM_JOB_ORDER;//tong job
                    $item->SUM_REPLENISHMENT_WITHDRAWAL = -$SUM_JOB_ORDER;//tong bu tra
                } else {
                    $item->SUM_LENDER_AMT = $SUM_LENDER_AMT;//tong ung
                    $item->SUM_DIRECT = 0;//chi truc tiep
                    $item->SUM_JOB_ORDER = $SUM_JOB_ORDER;//tong job
                    $item->SUM_REPLENISHMENT_WITHDRAWAL = $SUM_LENDER_AMT-$SUM_JOB_ORDER;//tong bu tra
                }
            }
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function query()
    {
        $data = DB::table('DEBIT_NOTE_M as dnm')
            ->leftJoin('CUSTOMER as c', 'dnm.CUST_NO', 'c.CUST_NO')
            ->where('dnm.BRANCH_ID', 'IHTVN1')
            ->where('c.BRANCH_ID', 'IHTVN1');
        return $data;
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
            $debit_d = StatisticPayment::postDebitNote_D($request);
            switch ($request->type) {
                case 'job':
                    if ($request->phone == null || $request->phone == 'undefined' || $request->phone == 'null') {
                        $data = 'error-phone';
                        return $data;
                    }
                    if ($request->person == null || $request->person == 'undefined' || $request->person == 'null') {
                        $data = 'error-person-empty';
                        return $data;
                    }
                    if ($request->jobno == null || $request->jobno == 'undefined' || $request->jobno == 'null') {
                        $data = 'error-job-empty';
                        return $data;
                    }

                    if ($request->jobno && $request->person && $request->phone) {
                        $query = StatisticPayment::query();
                        $data = $query->whereIn('dnm.JOB_NO', $request->jobno)->first();
                        foreach ($debit_d as $item_d) {
                            $total_amt += $item_d->QUANTITY * ($item_d->PRICE + $item_d->TAX_AMT);
                            $data->debit_d[] = [
                                "SER_NO" => $item_d->SER_NO,
                                "INV_NO" => $item_d->INV_NO,
                                "DESCRIPTION" => $item_d->DESCRIPTION,
                                "UNIT" => $item_d->UNIT,
                                "QUANTITY" => $item_d->QUANTITY,
                                "PRICE" => $item_d->PRICE,
                                "TAX_AMT" => $item_d->TAX_AMT,
                                "TOTAL_AMT" => $item_d->TOTAL_AMT,
                                "NOTE" => $item_d->NOTE,
                            ];
                        }
                        return $data;
                    } else {
                        return 201;
                    }
                    break;
                case 'customer':
                    if ($request->custno == null || $request->custno == 'undefined' || $request->custno == 'null') {
                        $data = 'error-custno';
                        return $data;
                    }
                    if ($request->phone == null || $request->phone == 'undefined' || $request->phone == 'null') {
                        $data = 'error-phone';
                        return $data;
                    }
                    if ($request->person == null || $request->person == 'undefined' || $request->person == 'null') {
                        $data = 'error-person-empty';
                        return $data;
                    }
                    if ($request->jobno == null || $request->jobno == 'undefined' || $request->jobno == 'null') {
                        $data = 'error-job-empty';
                        return $data;
                    }
                    if ($request->custno && $request->person && $request->phone) {
                        $query = StatisticPayment::query();
                        $data = $query->whereIn('dnm.JOB_NO', $request->jobno)->whereIn('dnm.CUST_NO', $request->custno)
                            ->select('dnm.*')->get();
                        foreach ($data as $item) {
                            foreach ($debit_d as $item_d) {
                                if ($item->JOB_NO == $item_d->JOB_NO) {
                                    $item->debit_d[] = [
                                        "JOB_NO" => $item_d->JOB_NO,
                                        "SER_NO" => $item_d->SER_NO,
                                        "INV_NO" => $item_d->INV_NO,
                                        "DESCRIPTION" => $item_d->DESCRIPTION,
                                        "UNIT" => $item_d->UNIT,
                                        "QUANTITY" => $item_d->QUANTITY,
                                        "PRICE" => $item_d->PRICE,
                                        "TAX_AMT" => $item_d->TAX_AMT,
                                        "TOTAL_AMT" => $item_d->TOTAL_AMT,
                                        "NOTE" => $item_d->NOTE,
                                    ];
                                }
                            }
                        }
                        return $data;
                    }
                    break;
                case  'debit_date':
                    if (($fromdate == null || $fromdate == 'undefined' || $fromdate == 'null') && ($todate == null || $request->todate == 'undefined' || $todate == 'null') || $fromdate > $todate) {
                        $data = 'error-date';
                        return $data;
                    } elseif ($request->debittype == null || $request->debittype == 'undefined' || $request->debittype == 'null') {
                        $data = 'error-debittype';
                        return $data;
                    } else {
                        //  $debittype= our_company_pay, pay_in_advance, all
                        $query = DB::table('DEBIT_NOTE_M as dnm')
                            ->leftJoin('DEBIT_NOTE_D as dnd', 'dnm.JOB_NO', 'dnd.JOB_NO')
                            ->whereBetween('dnm.DEBIT_DATE', [$fromdate, $todate])
                            ->where('dnm.BRANCH_ID', 'IHTVN1')
                            ->whereNotNull('dnm.JOB_NO');
                        if ($request->debittype == "our_company_pay") {
                            $query->where('dnd.DEB_TYPE', 'Our Company Pay');
                        } elseif ($request->debittype == "pay_in_advance") {
                            $query->where('dnd.DEB_TYPE', 'Pay In Advance');
                        }
                        $data = $query->select('dnm.JOB_NO', 'dnm.CUST_NO', 'dnm.DEBIT_DATE as DEBIT_DATE_M')->distinct()->get();
                        foreach ($data as $item) {
                            foreach ($debit_d as $item_d) {
                                if ($item->JOB_NO == $item_d->JOB_NO) {
                                    $item->debit_d[] = [
                                        "DEB_TYPE" => $item_d->DEB_TYPE,
                                        "SER_NO" => $item_d->SER_NO,
                                        "INV_NO" => $item_d->INV_NO,
                                        "DESCRIPTION" => $item_d->DESCRIPTION,
                                        "UNIT" => $item_d->UNIT,
                                        "DOR_AMT" => $item_d->DOR_AMT,
                                        "DOR_RATE" => $item_d->DOR_RATE,
                                        "PRICE" => $item_d->PRICE,
                                        "QUANTITY" => $item_d->QUANTITY,
                                        "TAX_NOTE" => $item_d->TAX_NOTE,
                                        "TAX_AMT" => $item_d->TAX_AMT,
                                        "TOTAL_AMT" => $item_d->TOTAL_AMT,
                                    ];
                                }
                            }
                        }
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

    public static function postDebitNote_D($request)
    {
        $query = DB::table('DEBIT_NOTE_D as dnd')
            ->leftJoin('DEBIT_NOTE_M as dnm', 'dnm.JOB_NO', 'dnd.JOB_NO')
            ->where('dnd.BRANCH_ID', 'IHTVN1');
        switch ($request->type) {
            case 'job':
                $query->whereIn('dnd.JOB_NO', $request->jobno);
                $data = $query->get();
                break;
            case 'customer':
                $query->whereIn('dnd.JOB_NO', $request->jobno);
                $data = $query->get();
                break;
            case  'debit_date':
                $query->whereBetween('dnm.DEBIT_DATE', [$request->fromdate, $request->todate]);
                if ($request->debittype == "our_company_pay") {
                    $query->where('dnd.DEB_TYPE', 'Our Company Pay');
                } elseif ($request->debittype == "pay_in_advance") {
                    $query->where('dnd.DEB_TYPE', 'Pay In Advance');
                }
                $query->select('dnd.JOB_NO', 'dnd.DEB_TYPE', 'dnd.SER_NO', 'dnd.INV_NO', 'dnd.DESCRIPTION', 'dnd.UNIT', 'dnd.DOR_AMT', 'dnd.DOR_RATE', 'dnd.PRICE', 'dnd.QUANTITY', 'dnd.TAX_NOTE', 'dnd.TAX_AMT', 'dnd.TOTAL_AMT');
                $data = $query->get();
                break;
            default:
                break;
        }
        return $data;
    }
    //5. thống kê số job trong tháng
    public static function jobMonthly($type, $custno, $fromdate, $todate)
    {
        try {
            $data = '';
            $table_name = '';

            if (($fromdate == null || $fromdate == 'undefined' || $fromdate == 'null') && ($todate == null || $todate == 'undefined' || $todate == 'null') || $fromdate > $todate) {
                $data = 'error-date';
                return $data;
            } elseif ($custno == null || $custno == 'undefined' || $custno == 'null') {
                $data = 'error-custno';
                return $data;
            }
            $table_name = $type == 'job_start' ? 'JOB_START' : ($type == 'job_order' ? 'JOB_ORDER_M' : 'DEBIT_NOTE_M');
            $data = DB::table($table_name . ' as job')
                ->leftJoin('CUSTOMER as c', 'job.CUST_NO', 'c.CUST_NO')
                ->where('job.BRANCH_ID', 'IHTVN1')
                ->where('c.BRANCH_ID', 'IHTVN1')
                ->where('job.CUST_NO', $custno)
                ->whereBetween('job.INPUT_DT', [$fromdate, $todate])
                ->select('c.CUST_NAME', 'job.*')->take(9000)->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    //8. thống kê phiếu thu
    public static function receipt($receipt_no)
    {
        try {
            $query = DB::table('RECEIPT as r')
                ->leftJoin('CUSTOMER as c', 'c.CUST_NO', 'r.CUST_NO')
                ->where('r.BRANCH_ID', 'IHTVN1')
                ->where('r.RECEIPT_NO', $receipt_no)
                ->select('c.CUST_NAME', 'c.CUST_ADDRESS', 'r.RECEIPT_NO', 'r.RECEIPT_DATE', 'r.RECEIPT_REASON', 'r.TOTAL_AMT', 'r.DOR_NO', 'r.TRANSFER_FEES')
                ->first();
            return $query;
        } catch (\Exception $e) {
            return $e;
        }
    }
}

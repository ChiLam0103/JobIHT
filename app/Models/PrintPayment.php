<?php

namespace App\Models;

header('Content-Type: text/html; charset=utf-8');

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PrintPayment extends Model
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
                ->where('ld.LENDER_NO', $advance)
                ->select('ld.*')->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }

    //2 phiếu yêu cầu thanh toán
    public static function debitNote($type, $jobno, $custno, $fromdate, $todate, $debittype, $person, $phone)
    {
        try {
            $data = '';
            switch ($type) {
                case 'job':
                    if ($phone == null || $phone == 'undefined' || $phone == 'null') {
                        $data = 'error-phone';
                        return $data;
                    }
                    if ($person == null || $person == 'undefined' || $person == 'null') {
                        $data = 'error-person-empty';
                        return $data;
                    }
                    if ($jobno == null || $jobno == 'undefined' || $jobno == 'null') {
                        $data = 'error-job-empty';
                        return $data;
                    }
                    if ($jobno && $person && $phone) {
                        $data = DB::table('DEBIT_NOTE_M as dnm')
                            ->leftJoin('CUSTOMER as c', 'dnm.CUST_NO', 'c.CUST_NO')
                            ->where('dnm.JOB_NO', $jobno)
                            ->where('dnm.BRANCH_ID', 'IHTVN1')
                            ->where('c.BRANCH_ID', 'IHTVN1')
                            ->first();
                        return $data;
                    } else {
                        return 201;
                    }
                    break;
                case 'customer':
                    if ($phone == null || $phone == 'undefined' || $phone == 'null') {
                        $data = 'error-phone';
                        return $data;
                    }
                    if ($custno && $person && $phone) {
                        //array job[]
                        return $data;
                    }
                    break;
                case  'debit_date':
                    if (($fromdate == null || $fromdate == 'undefined' || $fromdate == 'null') && ($todate == null || $todate == 'undefined' || $todate == 'null')) {
                        $data = 'error-date';
                        return $data;
                    } elseif ($fromdate > $todate) {
                        $data = 'error-date';
                        return $data;
                    } elseif ($debittype == null || $debittype == 'undefined' || $debittype == 'null') {
                        $data = 'error-debittype';
                        return $data;
                    } else {
                        //  $debittype= our_company_pay, pay_in_advance, all
                        $query = DB::table('DEBIT_NOTE_M as dnm')
                            ->leftJoin('DEBIT_NOTE_D as dnd', 'dnm.JOB_NO', 'dnd.JOB_NO')
                            ->whereBetween('dnm.DEBIT_DATE', [$fromdate, $todate])
                            ->where('dnm.BRANCH_ID', 'IHTVN1')
                            ->whereNotNull('dnm.JOB_NO');

                        if ($debittype == "our_company_pay") {
                            $query->where('dnd.DEB_TYPE', 'Our Company Pay');
                        } elseif ($debittype == "pay_in_advance") {
                            $query->where('dnd.DEB_TYPE', 'Pay In Advance');
                        }
                        $data = $query->select('dnm.JOB_NO', 'dnm.CUST_NO', 'dnm.DEBIT_DATE', 'dnd.*')->take(100)->get();
                        dd($data);
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
    public static function debitNote_D($jobno)
    {
        $data = DB::table('DEBIT_NOTE_D ')
            ->where('JOB_NO', $jobno)
            ->where('BRANCH_ID', 'IHTVN1')
            ->get();
        return $data;
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

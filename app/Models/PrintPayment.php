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
            if (($type == 'job' || $type = 'customer') && ($phone == null || $phone == 'undefined' || $phone == 'null')) {
                $data = 'error-phone';
                return $data;
            }
            if ($type == 'job') {
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
                        ->first();
                    return $data;
                } else {
                    return 201;
                }
            } elseif ($type == 'customer') {
                if ($custno && $person && $phone) {
                    //array job[]
                    return $data;
                }
            } elseif ($type == 'debit_date') {
                if ($fromdate && $todate && $debittype) {
                    return $data;
                }
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

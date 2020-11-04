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
                ->select('c.CUST_NAME', 'p.PNL_NAME as PNAME', 'lt.LENDER_NAME', 'l.*')->first();
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
    public static function debitNote($type, $formjobno, $tojobno, $custno, $fromdate, $todate, $debittype, $person, $phone)
    {
        try {
            if ($type == 'job_no') {
                if ($formjobno && $tojobno  && $person && $phone) {
                    $data = DB::table('DEBIT_NOTE_M as dnm')->get();
                } else {
                    return 201;
                }
            } elseif ($type == 'customer') {
                if ($custno && $person && $phone) {
                    //array job[]
                }
            } elseif ($type == 'debit_date') {
                if ($fromdate && $todate && $debittype) {
                }
            }
            return $data;
        } catch (\Exception $e) {
            dd($e);
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
            ->select('c.CUST_NAME', 'c.CUST_ADDRESS','r.RECEIPT_NO','r.RECEIPT_DATE','r.RECEIPT_REASON','r.TOTAL_AMT','r.DOR_NO','r.TRANSFER_FEES')
            ->first();
            return $query;
        } catch (\Exception $e) {
            return $e;
        }
    }
}

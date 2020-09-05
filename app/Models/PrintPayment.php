<?php

namespace App\Models;

header('Content-Type: text/html; charset=utf-8');

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PrintPayment extends Model
{
    //1. in phieu theo doi
    //1.1.phieu chi
    public static function advancePayment($fromadvance, $toadvance)
    {
        try {
            $data = DB::table('LENDER as l')
                ->leftJoin('CUSTOMER as c', 'l.CUST_NO', 'c.CUST_NO')
                ->whereBetween('l.LENDER_NO', [$fromadvance, $toadvance])
                ->select('c.CUST_NAME', 'l.*')->get();
            // $str = "";
            // foreach ($data as $item) {
            //     $str = PrintPayment::convert_number_to_words((int)$item->TOTAL_AMT);
            //     $str = mb_convert_encoding($str, 'UTF-8', 'auto');
            //     $item->AMT_NAME = $str;
            // }
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    //1.4.phieu tam ung chua mo job order
    public static function withoutJob_AdvancePayment($fromdate, $todate)
    {
        try {
            $data = DB::table('LENDER as l')
                ->leftJoin('CUSTOMER as c', 'l.CUST_NO', 'c.CUST_NO')
                ->leftJoin('JOB_ORDER_M as jom', 'l.JOB_NO', 'jom.JOB_NO')
                ->leftJoin('JOB_ORDER_D as jod', 'jod.JOB_NO', 'jom.JOB_NO')
                ->whereNotNull('l.JOB_NO')
                ->where(function ($query) {
                    $query->where('jod.JOB_NO', null)
                        ->orWhere('jom.JOB_NO', null);
                })
                ->whereBetween('l.LENDER_DATE', [$fromdate, $todate])
                ->select('c.CUST_NAME', 'l.LENDER_NO', 'l.JOB_NO', 'jod.JOB_NO as jod','jom.JOB_NO as jom')
                ->take(100)
                ->get();
            dd($data);
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
}

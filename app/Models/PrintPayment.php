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
    public static function withoutJob($fromdate, $todate)
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
                ->select('c.CUST_NAME', 'l.LENDER_NO', 'l.JOB_NO', 'jod.JOB_NO as jod', 'jom.JOB_NO as jom')
                ->take(100)
                ->get();
            dd($data);
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    //1.7 thống kê phiếu tạm ứng
    public static function statisticalAdvance($fromdate, $todate, $type)
    {
        try {
            $lender_type = '';
            if ($type == '7') {
                $lender_type = 'T';
            } elseif ($type == '10') {
                $lender_type = 'C';
            } else {
                $lender_type = 'U';
            }
            $a = DB::table('LENDER as l')
                ->leftJoin('PERSONAL as p', 'p.PNL_NO', 'l.LENDER_NO')
                ->where('l.LENDER_TYPE', $lender_type)
                ->where('l.BRANCH_ID', 'IHTVN1')
                ->whereBetween('l.LENDER_DATE', [$fromdate, $todate]);
            $data = $a->select('l.*', 'p.PNL_NAME as PNL_NAME2')
                ->get();
            return $data;
        } catch (\Exception $e) {
            dd($e);
            return $e;
        }
    }
    //8. phiếu bù
    public static function statisticalAdvance2($fromdate, $todate, $type)
    {
        try {
            $lender_type = '';
            if ($type == '7' || $type == '8') {
                $lender_type = 'T';
            } elseif ($type == '10') {
                $lender_type = 'C';
            } else {
                $lender_type = 'U';
            }
            $a = DB::table('LENDER as l')
                ->leftJoin('PERSONAL as p', 'p.PNL_NO', 'l.LENDER_NO')
                ->where('l.LENDER_TYPE', $lender_type)
                ->where('l.BRANCH_ID', 'IHTVN1')
                ->whereBetween('l.LENDER_DATE', [$fromdate, $todate])
                ->rightJoin('JOB_ORDER_M as jom', 'jom.JOB_NO', 'l.JOB_NO')
                // ->rightJoin('JOB_ORDER_D as jod', 'jom.JOB_NO', 'jod.JOB_NO')
                ->whereNotNull('jom.CUSTOMS_NO');
            // if ($type == '8') {
            // }

            $data = $a->select('l.LENDER_NO','jom.*')
                ->distinct()
                ->take(10)
                ->get();
            dd($data);
            return $data;
        } catch (\Exception $e) {
            dd($e);
            return $e;
        }
    }
}

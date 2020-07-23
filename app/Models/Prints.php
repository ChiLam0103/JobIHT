<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Prints extends Model
{
    public static function jobStart($id)
    {
        try {
            $data =  DB::table('JOB_START as js')
                ->leftJoin('CUSTOMER as c','js.CUST_NO','c.CUST_NO')
                ->leftJoin('PERSONAL as p1','js.NV_CHUNGTU','p1.PNL_NO')
                ->leftJoin('PERSONAL as p2','js.NV_GIAONHAN','p2.PNL_NO')
                ->where('js.JOB_NO', $id)
                ->select('c.CUST_NAME','c.CUST_TAX','c.CUST_ADDRESS','p1.PNL_NAME as NV_CHUNGTU_1','p2.PNL_NAME as NV_GIAONHAN_2','js.*')
                ->first();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function jobOrder($id)
    {
        try {
            $data =  DB::table('JOB_ORDER_M as jom')
                ->leftJoin('CUSTOMER as c','jom.CUST_NO','c.CUST_NO')
                ->leftJoin('LENDER as l','jom.JOB_NO','l.JOB_NO')
                ->where('jom.JOB_NO', $id)
                ->select('c.CUST_NAME','l.LENDER_NO','jom.*')
                ->first();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function jobOrder_D($id)
    {
        try {
            $data =  DB::table('JOB_ORDER_M as jom')
                ->leftJoin('JOB_ORDER_D as jod','jom.JOB_NO','jod.JOB_NO')
                ->where('jom.JOB_NO', $id)
                ->select('jod.*')
                ->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function refund($type,$id,$jobno,$todate,$fromdate)
    {
        try {
            $data =  DB::table('JOB_ORDER_M as jom')
                ->leftJoin('CUSTOMER as c','jom.CUST_NO','c.CUST_NO')
                ->leftJoin('LENDER as l','jom.JOB_NO','l.JOB_NO')
                ->where('jom.JOB_NO', $id)
                ->select('c.CUST_NAME','l.LENDER_NO','jom.*')
                ->first();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
}

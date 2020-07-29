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
                ->leftJoin('CUSTOMER as c', 'js.CUST_NO', 'c.CUST_NO')
                ->leftJoin('PERSONAL as p1', 'js.NV_CHUNGTU', 'p1.PNL_NO')
                ->leftJoin('PERSONAL as p2', 'js.NV_GIAONHAN', 'p2.PNL_NO')
                ->where('js.JOB_NO', $id)
                ->select('c.CUST_NAME', 'c.CUST_TAX', 'c.CUST_ADDRESS', 'p1.PNL_NAME as NV_CHUNGTU_1', 'p2.PNL_NAME as NV_GIAONHAN_2', 'js.*')
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
                ->leftJoin('CUSTOMER as c', 'jom.CUST_NO', 'c.CUST_NO')
                ->leftJoin('LENDER as l', 'jom.JOB_NO', 'l.JOB_NO')
                ->where('jom.JOB_NO', $id)
                ->select('c.CUST_NAME', 'l.LENDER_NO', 'jom.*')
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
                ->leftJoin('JOB_ORDER_D as jod', 'jom.JOB_NO', 'jod.JOB_NO')
                ->where('jom.JOB_NO', $id)
                ->select('jod.*')
                ->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function refund($type, $id, $jobno, $todate, $fromdate)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $today = date("Ymd");
            $to_date = ($todate ? $todate : '19000101');
            $from_date = ($fromdate ? $fromdate : $today);
            $a = DB::table('JOB_ORDER_M as jom')
                ->join('JOB_ORDER_D as jod', 'jom.JOB_NO', 'jod.JOB_NO')
                ->join('CUSTOMER as c', 'jom.CUST_NO', 'c.CUST_NO')
                ->where('jom.BRANCH_ID', 'IHTVN1');

            //check loai: 1.hang tau 2.khach hang 3.dai ly
            if ($type == '1') {
                $a->where('jod.ORDER_TYPE', '5');
            } elseif ($type == '2') {
                $a->where('jod.ORDER_TYPE', '6');
            } elseif ($type == '3') {
                $a->where('jod.ORDER_TYPE', '7');
            }else{
                $a->where('jod.ORDER_TYPE', '5');
            }
            //CUST_NO
            if ($id != 'null') {
                $a->where('c.CUST_NO', $id);
            }
            //job_no
            if ($jobno != 'null') {
                $a->where('jom.JOB_NO', $jobno);
            }
            if ($todate || $fromdate) {
                $a->whereBetween('jod.INPUT_DT', [$to_date, $from_date]);
            }
            $data = $a->select('c.CUST_NO','c.CUST_NAME','jod.*')
            ->orderBy('c.CUST_NO')->take(10)->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PrintFile extends Model
{
    //1. in phieu theo doi
    public static function jobStart($fromjob, $tojob)
    {
        try {
            $data =  DB::table('JOB_START as js')
                ->leftJoin('CUSTOMER as c', 'js.CUST_NO', 'c.CUST_NO')
                ->leftJoin('PERSONAL as p1', 'js.NV_CHUNGTU', 'p1.PNL_NO')
                ->leftJoin('PERSONAL as p2', 'js.NV_GIAONHAN', 'p2.PNL_NO')
                ->whereBetween('js.JOB_NO', [$fromjob, $tojob])
                ->select('c.CUST_NAME', 'c.CUST_TAX', 'c.CUST_ADDRESS', 'p1.PNL_NAME as NV_CHUNGTU_1', 'p2.PNL_NAME as NV_GIAONHAN_2', 'js.*')
                ->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    //2.1 in job order theo job
    public static function jobOrder($jobno)
    {
        try {
            $data =  DB::table('JOB_ORDER_M as jom')
                ->leftJoin('CUSTOMER as c', 'jom.CUST_NO', 'c.CUST_NO')
                ->leftJoin('LENDER as l', 'jom.JOB_NO', 'l.JOB_NO')
                ->where('jom.JOB_NO', $jobno)
                ->select('c.CUST_NAME', 'l.LENDER_NO', 'jom.*')
                ->first();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function jobOrder_D($jobno)
    {
        try {
            $data =  DB::table('JOB_ORDER_M as jom')
                ->leftJoin('JOB_ORDER_D as jod', 'jom.JOB_NO', 'jod.JOB_NO')
                ->where('jom.JOB_NO', $jobno)
                ->select('jod.*')
                ->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    //2.2 in job order theo ngay
    public static function jobOrder_Date($fromdate, $todate)
    {
        try {
            $data =  DB::table('JOB_ORDER_M as jom')
                ->leftJoin('CUSTOMER as c', 'jom.CUST_NO', 'c.CUST_NO')
                ->leftJoin('LENDER as l', 'jom.JOB_NO', 'l.JOB_NO')
                ->whereBetween('jom.ORDER_DATE', [$fromdate, $todate])
                ->select('c.CUST_NAME', 'l.LENDER_NO', 'jom.*')
                ->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function getJobOrder_D($fromdate, $todate)
    {
        try {
            $data =  DB::table('JOB_ORDER_M as jom')
                ->leftJoin('JOB_ORDER_D as jod', 'jom.JOB_NO', 'jod.JOB_NO')
                ->rightJoin('PAY_TYPE as pt', 'jod.ORDER_TYPE', 'pt.PAY_NO')
                ->whereBetween('jom.ORDER_DATE', [$fromdate, $todate])
                ->select('pt.PAY_NAME', 'jod.*')
                ->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    //2.2 in job order theo khach hang
    public static function getJobOrderCustomer($custno)
    {
        try {
            $data =  DB::table('JOB_ORDER_M as jom')
                ->leftJoin('CUSTOMER as c', 'jom.CUST_NO', 'c.CUST_NO')
                ->leftJoin('LENDER as l', 'jom.JOB_NO', 'l.JOB_NO')
                ->where('c.CUST_NO', $custno)
                ->select('jom.JOB_NO')
                ->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function jobOrderCustomer($custno, $jobno)
    {
        try {
            $array_jobno = explode(",", $jobno);
            $data =  DB::table('JOB_ORDER_M as jom')
                ->leftJoin('CUSTOMER as c', 'jom.CUST_NO', 'c.CUST_NO')
                ->leftJoin('LENDER as l', 'jom.JOB_NO', 'l.JOB_NO')
                ->where('c.CUST_NO', $custno)
                ->whereIn('jom.JOB_NO', $array_jobno)
                ->select('c.CUST_NAME', 'l.LENDER_NO', 'jom.*')
                ->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function jobOrderCustomer_D($custno, $jobno)
    {
        try {
            $array_jobno = explode(",", $jobno);
            $data =  DB::table('JOB_ORDER_M as jom')
                ->leftJoin('CUSTOMER as c', 'jom.CUST_NO', 'c.CUST_NO')
                ->leftJoin('JOB_ORDER_D as jod', 'jom.JOB_NO', 'jod.JOB_NO')
                ->rightJoin('PAY_TYPE as pt', 'jod.ORDER_TYPE', 'pt.PAY_NO')
                ->where('c.CUST_NO', $custno)
                ->whereIn('jom.JOB_NO', $array_jobno)
                ->select('pt.PAY_NAME', 'jod.*')
                ->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    //3 bao bieu refund
    public static function refund($type, $id, $jobno, $fromdate, $todate)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $today = date("Ymd");
            $from_date = ($fromdate ? $fromdate : '19000101');
            $to_date = ($todate ? $todate : $today);
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
            } else {
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
                $a->whereBetween('jod.INPUT_DT', [$from_date, $to_date]);
            }
            $data = $a->select('c.CUST_NO', 'c.CUST_NAME', 'jod.*')
                ->orderBy('c.CUST_NO')->take(9000)->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    //4 thong ke job order
    public static function statisticCreatedJob($cust, $user, $todate, $fromdate)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $today = date("Ymd");
            $to_date = ($todate ? $todate : '19000101');
            $from_date = ($fromdate ? $fromdate : $today);
            $a =  DB::table('JOB_START as js')
                ->leftJoin('CUSTOMER as c', 'js.CUST_NO', 'c.CUST_NO')
                ->rightJoin('PERSONAL as p', 'js.NV_CHUNGTU', 'p.PNL_NO')
                ->leftJoin('USERM as u', 'js.INPUT_USER', 'u.USER_NO')
                ->where('js.BRANCH_ID', 'IHTVN1');
            if ($cust != 'null') {
                $a->where('js.CUST_NO', $cust);
            }
            if ($user != 'null') {
                $a->where('js.INPUT_USER', $user);
            }
            if ($todate || $fromdate) {
                $a->whereBetween('js.INPUT_DT', [$to_date, $from_date]);
            }
            $data = $a->select('c.CUST_NAME', 'u.USER_NAME', 'p.PNL_NAME', 'js.*')
                ->orderBy('p.PNL_NAME')
                ->take(9000)->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function statisticUserImportJob($cust, $user, $todate, $fromdate)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $today = date("Ymd");
            $to_date = ($todate ? $todate : '19000101');
            $from_date = ($fromdate ? $fromdate : $today);
            $a =  DB::table('JOB_ORDER_M as jom')
                ->leftJoin('JOB_START as js', 'jom.JOB_NO', 'js.JOB_NO')
                ->leftJoin('CUSTOMER as c', 'js.CUST_NO', 'c.CUST_NO')
                ->rightJoin('PERSONAL as p', 'js.NV_CHUNGTU', 'p.PNL_NO')
                ->leftJoin('USERM as u', 'js.INPUT_USER', 'u.USER_NO')
                ->where('js.BRANCH_ID', 'IHTVN1');
            if ($cust != 'null') {
                $a->where('js.CUST_NO', $cust);
            }
            if ($user != 'null') {
                $a->where('js.INPUT_USER', $user);
            }
            if ($todate || $fromdate) {
                $a->whereBetween('js.INPUT_DT', [$to_date, $from_date]);
            }
            $data = $a->select('c.CUST_NAME', 'u.USER_NAME', 'p.PNL_NAME', 'js.*')
                ->orderBy('p.PNL_NAME')
                ->take(9000)->get();
            //  dd($data);
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
}

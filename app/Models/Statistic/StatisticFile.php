<?php

namespace App\Models\Statistic;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StatisticFile extends Model
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
                ->where('p1.BRANCH_ID', 'IHTVN1')
                ->where('p2.BRANCH_ID', 'IHTVN1')
                ->select('c.CUST_NAME', 'c.CUST_TAX', 'c.CUST_ADDRESS', 'p1.PNL_NAME as NV_CHUNGTU_1', 'p2.PNL_NAME as NV_GIAONHAN_2', 'js.*')
                ->orderBy('js.JOB_NO')
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
                ->where('jom.BRANCH_ID', 'IHTVN1')
                ->where('c.BRANCH_ID', 'IHTVN1')
                ->where('l.BRANCH_ID', 'IHTVN1')
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
                ->where('jom.BRANCH_ID', 'IHTVN1')
                ->where('jod.BRANCH_ID', 'IHTVN1')
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
                ->where('c.CUST_NO', $custno)
                ->where('jom.BRANCH_ID', 'IHTVN1')
                ->where('c.BRANCH_ID', 'IHTVN1')
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
                ->where('c.CUST_NO', $custno)
                ->whereIn('jom.JOB_NO', $array_jobno)
                ->select('c.CUST_NAME', 'jom.*')
                ->where('jom.BRANCH_ID', 'IHTVN1')
                ->where('c.BRANCH_ID', 'IHTVN1')
                ->distinct()
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
    public static function refund($type, $custno, $jobno, $fromdate, $todate)
    {
        try {
            //ORDER_TYPE: 1.hang tau 2.khach hang 3.dai ly
            //CUST_TYPE: 1.customer(khach hang), 2.carriers(hang tau), 3.agent(dai ly), 4.garage(nha xe)

            $query = DB::table('JOB_ORDER_M as jom')
                ->join('JOB_ORDER_D as jod', 'jom.JOB_NO', 'jod.JOB_NO')
                ->join('JOB_START as js', 'jom.JOB_NO', 'js.JOB_NO')
                ->where('jom.BRANCH_ID', 'IHTVN1')
                ->where('c.BRANCH_ID', 'IHTVN1')
                ->whereBetween('js.JOB_DATE', [$fromdate, $todate])
                ->where(function ($query) {
                    $query->where('jod.THANH_TOAN_MK', 'N')
                        ->orWhere('jod.THANH_TOAN_MK', null);
                })
                ->select('c.CUST_NO', 'c.CUST_NAME', 'jom.BILL_NO', 'jod.*');

            if ($type == '1') { //hang tau
                $query->join('CUSTOMER as c', 'jom.CUST_NO2', 'c.CUST_NO')
                    ->where('jod.ORDER_TYPE', '5')
                    ->where('c.CUST_TYPE', 2);
                ($custno == 'undefined' || $custno == 'null' || $custno == null) ? null : $query->where('jom.CUST_NO2', $custno);
            } elseif ($type == '2') { //khach hang
                $query->join('CUSTOMER as c', 'jom.CUST_NO', 'c.CUST_NO')
                    ->where('jod.ORDER_TYPE', '6')
                    ->where('c.CUST_TYPE', 1);
                ($custno == 'undefined' || $custno == 'null' || $custno == null) ? null : $query->where('jom.CUST_NO', $custno);
            } elseif ($type == '3') { //dai ly
                $query->leftJoin('CUSTOMER as c', 'jom.CUST_NO3', 'c.CUST_NO')
                    ->leftJoin('CUSTOMER as c2', 'jom.CUST_NO', 'c2.CUST_NO')
                    ->where('jod.ORDER_TYPE', '7')
                    ->where('c.CUST_TYPE', 3)
                    ->where('c2.BRANCH_ID', 'IHTVN1')
                    ->selectRaw('c2.CUST_NO as CUST_NO2 , c2.CUST_NAME as CUST_NAME2');
                ($custno == 'undefined' || $custno == 'null' || $custno == null) ? null : $query->where('jom.CUST_NO3', $custno);
            }

            //job_no
            ($jobno == 'undefined' || $jobno == 'null' || $jobno == null) ? null : $query->where('jom.JOB_NO', $jobno);

            $data = $query->orderBy('jom.JOB_NO')->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function postExportRefund($request)
    {
        try {
            //ORDER_TYPE: 1.hang tau 2.khach hang 3.dai ly
            //CUST_TYPE: 1.customer(khach hang), 2.carriers(hang tau), 3.agent(dai ly), 4.garage(nha xe)
            $today = date("Ymd");
            $from_date = ($request->fromdate == 'undefined' || $request->fromdate == 'null' || $request->fromdate == null) ? '19000101' :  $request->fromdate;
            $to_date = ($request->todate == 'undefined' || $request->todate == 'null' || $request->todate == null) ? $today : $request->todate;
            $query = DB::table('JOB_ORDER_M as jom')
                ->join('JOB_ORDER_D as jod', 'jom.JOB_NO', 'jod.JOB_NO')
                ->join('JOB_START as js', 'jom.JOB_NO', 'js.JOB_NO')
                ->where('jom.BRANCH_ID', 'IHTVN1')
                ->where('c.BRANCH_ID', 'IHTVN1')
                ->whereBetween('js.JOB_DATE', [$from_date, $to_date])
                ->where(function ($query) {
                    $query->where('jod.THANH_TOAN_MK', 'N')
                        ->orWhere('jod.THANH_TOAN_MK', null);
                })
                ->select('c.CUST_NO', 'c.CUST_NAME', 'jom.BILL_NO', 'jod.*');
            switch ($request->type) { //hang tau
                case 'carriers' || '1':
                    $query->join('CUSTOMER as c', 'jom.CUST_NO2', 'c.CUST_NO')
                        ->where('jod.ORDER_TYPE', '5')
                        ->where('c.CUST_TYPE', 2);
                    ($request->custno == 'undefined' || $request->custno == 'null' || $request->custno == null) ? null : $query->where('jom.CUST_NO2', $request->custno);
                    break;
                case 'customer' || '2': //khach hang
                    $query->join('CUSTOMER as c', 'jom.CUST_NO', 'c.CUST_NO')
                        ->where('jod.ORDER_TYPE', '6')
                        ->where('c.CUST_TYPE', 1);
                    ($request->custno == 'undefined' || $request->custno == 'null' || $request->custno == null) ? null : $query->where('jom.CUST_NO', $request->custno);

                    break;
                case 'agency' || '3': //dai ly
                    $query->leftJoin('CUSTOMER as c', 'jom.CUST_NO3', 'c.CUST_NO')
                        ->leftJoin('CUSTOMER as c2', 'jom.CUST_NO', 'c2.CUST_NO')
                        ->where('jod.ORDER_TYPE', '7')
                        ->where('c.CUST_TYPE', 3)
                        ->where('c2.BRANCH_ID', 'IHTVN1')
                        ->selectRaw('c2.CUST_NO as CUST_NO2 , c2.CUST_NAME as CUST_NAME2');
                    ($request->custno == 'undefined' || $request->custno == 'null' || $request->custno == null) ? null : $query->where('jom.CUST_NO3', $request->custno);

                    break;
                default:
                    break;
            }
            //job_no
            ($request->jobno == 'undefined' || $request->jobno == 'null' || $request->jobno == null) ? null : $query->where('jom.JOB_NO', $request->jobno);
            $data = $query->orderBy('jom.JOB_NO')->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    //4.1 thong ke job order
    public static function statisticCreatedJob($cust, $user,  $fromdate, $todate)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $today = date("Ymd");
            $from_date = ($fromdate == 'undefined' || $fromdate == 'null' || $fromdate == null) ? '19000101' :  $fromdate;
            $to_date = ($fromdate == 'undefined' || $fromdate == 'null' || $fromdate == null) ? $today : $todate;
            $a =  DB::table('JOB_START as js')
                ->leftJoin('CUSTOMER as c', 'js.CUST_NO', 'c.CUST_NO')
                ->rightJoin('PERSONAL as p', 'js.NV_CHUNGTU', 'p.PNL_NO')
                ->leftJoin('USERM as u', 'js.INPUT_USER', 'u.USER_NO')
                ->where('js.BRANCH_ID', 'IHTVN1');
            if ($cust != 'undefined') {
                $a->where('js.CUST_NO', $cust);
            }
            if ($user != 'undefined') {
                $a->where('js.INPUT_USER', $user);
            }
            if ($todate || $fromdate) {
                $a->whereBetween('js.INPUT_DT', [$from_date, $to_date]);
            }
            $data = $a->select('c.CUST_NAME', 'u.USER_NAME', 'p.PNL_NAME', 'js.*')
                ->orderBy('js.JOB_NO')
                ->take(9000)->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    //4.2 thong ke user import job
    public static function statisticUserImportJob($cust,  $user, $fromdate, $todate)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $today = date("Ymd");
            $from_date = (($fromdate != 'undefined') ? $fromdate : '19000101');
            $to_date = (($todate != 'undefined') ? $todate : $today);
            $a =  DB::table('JOB_ORDER_M as jom')
                ->leftJoin('JOB_START as js', 'jom.JOB_NO', 'js.JOB_NO')
                ->leftJoin('JOB_ORDER_D as jod', 'jom.JOB_NO', 'jod.JOB_NO')
                ->leftJoin('CUSTOMER as c', 'jom.CUST_NO', 'c.CUST_NO')
                ->rightJoin('PERSONAL as p', 'js.NV_CHUNGTU', 'p.PNL_NO')
                ->whereBetween('jom.INPUT_DT', [$from_date, $to_date]);
            if ($cust != 'undefined') {
                $a->where('jom.CUST_NO', $cust);
            }
            if ($user != 'undefined') {
                $a->where('jod.INPUT_USER', $user);
            }
            $data = $a->select('c.CUST_NAME', 'p.PNL_NAME', 'js.JOB_DATE', 'jom.*')
                ->orderBy('jom.JOB_NO')
                ->distinct()
                ->take(9000)->get();
            return $data;
        } catch (\Exception $e) {
            return 201;
        }
    }
    public static function statisticUserImportJob_D($cust, $user, $fromdate, $todate)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $today = date("Ymd");
            $from_date = (($fromdate != 'undefined') ? $fromdate : '19000101');
            $to_date = (($todate != 'undefined') ? $todate : $today);
            $a =  DB::table('JOB_ORDER_M as jom')
                ->leftJoin('JOB_ORDER_D as jod', 'jom.JOB_NO', 'jod.JOB_NO')
                ->leftJoin('PAY_TYPE as pt', 'jod.ORDER_TYPE', 'pt.PAY_NO')
                ->rightJoin('USERM as u', 'u.USER_NO', 'jod.INPUT_USER')
                ->whereBetween('jom.INPUT_DT', [$from_date, $to_date]);
            if ($cust != 'undefined') {
                $a->where('jom.CUST_NO', $cust);
            }
            if ($user != 'undefined') {
                $a->where('jod.INPUT_USER', $user);
            }
            $data = $a->select('pt.PAY_NAME', 'u.USER_NAME', 'jod.*')
                ->take(9000)->get();
            // dd($data);
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    //5. thong ke nang ha
    public static function lifting($fromdate, $todate)
    {
        try {
            $query =  DB::table('JOB_START as js')
                ->leftJoin('CUSTOMER as c', 'js.CUST_NO', 'c.CUST_NO')
                ->whereBetween('js.JOB_DATE', [$fromdate, $todate]);
            $data = $query->select('c.CUST_NAME', 'c.CUST_TAX', 'js.*')
                ->where('js.BRANCH_ID', 'IHTVN1')
                ->where('c.BRANCH_ID', 'IHTVN1')
                ->orderBy('js.JOB_NO')->get();
            return $data;
        } catch (\Exception $e) {
            return 201;
        }
    }
}

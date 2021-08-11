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
                ->where('js.INPUT_DT', '>=', '20190101000000')
                ->where('p1.BRANCH_ID', 'IHTVN1')
                ->where('p2.BRANCH_ID', 'IHTVN1')
                ->where('c.BRANCH_ID', 'IHTVN1')
                ->where('js.BRANCH_ID', 'IHTVN1')
                ->select('c.CUST_NAME', 'c.CUST_TAX', 'c.CUST_ADDRESS', 'p1.PNL_NAME as NV_CHUNGTU_1', 'p2.PNL_NAME as NV_GIAONHAN_2', 'js.*')
                ->orderBy('js.JOB_NO')
                ->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    //1.1 function update statistic
    public static function jobStartNew($request)
    {

        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $from_date = ($request->fromdate == 'undefined' || $request->fromdate == 'null' || $request->fromdate == null) ? $today :  $request->fromdate;
        $to_date = ($request->todate == 'undefined' || $request->todate == 'null' || $request->todate == null) ? $today : $request->todate;
        $query = DB::table('JOB_START');
        if ($request->type == "customer") {
            $query->whereIn('ID', $request->array_id);
        } else if ($request->type == "date") {
            $query->whereBetween('JOB_DATE', [$from_date, $to_date]);
        }
        $data =  $query->get();
        return $data;
    }


    //--------------------------------//--------------------------------------
    //2.1 in job order theo job new
    public static function JobOrderNew($request)
    {
        $data = '';
        $today = date("Ymd");
        $from_date = ($request->fromdate == 'undefined' || $request->fromdate == 'null' || $request->fromdate == null) ? '19000101' :  $request->fromdate;
        $to_date = ($request->todate == 'undefined' || $request->todate == 'null' || $request->todate == null) ? $today : $request->todate;
        switch ($request->type) {
            case 'job':
                $data =  DB::table('JOB_ORDER_M as jom')
                    ->leftJoin('CUSTOMER as c', 'jom.CUST_NO', 'c.CUST_NO')
                    ->leftJoin('LENDER as l', 'jom.JOB_NO', 'l.JOB_NO')
                    ->where('jom.JOB_NO', $request->job_no)
                    ->where('c.BRANCH_ID', 'IHTVN1')
                    ->where('jom.BRANCH_ID', 'IHTVN1')
                    ->where('jom.INPUT_DT', '>=', '20190101000000')
                    ->select('c.CUST_NAME', 'l.LENDER_NO', 'jom.*')
                    ->first();
                $data_d = DB::select("select *
                FROM JOB_ORDER_D
                WHERE BRANCH_ID ='IHTVN1'
                AND JOB_NO = '" . $request->job_no . "'");
                $data->job_d = $data_d;
                break;

            case 'customer':
                $data = DB::table('JOB_ORDER_M as job')
                    ->rightJoin('CUSTOMER as c', 'c.CUST_NO', 'job.CUST_NO')
                    ->where('job.BRANCH_ID', 'IHTVN1')
                    ->where('c.BRANCH_ID', 'IHTVN1')
                    ->whereIn('job.ID', $request->array_id)
                    ->select('c.CUST_NAME', 'job.*')
                    ->get();
                foreach ($data as $item) {
                    $job_d = DB::select("select pt.PAY_NAME, job_d.JOB_NO, job_d.SER_NO, job_d.DESCRIPTION, job_d.PORT_AMT, job_d.NOTE, job_d.UNIT, job_d.QTY, job_d.PRICE, job_d.TAX_AMT, job_d.TAX_NOTE, job_d.INDUSTRY_ZONE_AMT
                    FROM JOB_ORDER_D job_d
                    LEFT JOIN PAY_TYPE pt
                    ON job_d.ORDER_TYPE = pt.PAY_NO
                    WHERE job_d.BRANCH_ID='IHTVN1'
                    AND  job_d.INPUT_DT >='20190101000000'
                    AND  job_d.JOB_NO = '" . $item->JOB_NO . "'
                    ORDER BY job_d.JOB_NO");
                    $item->job_d = $job_d;
                }
                break;
            case 'date':
                // $data = [];
                // $job_m =  DB::table('JOB_ORDER_M as job_m')
                //     ->leftJoin('CUSTOMER as c', 'c.CUST_NO', 'job_m.CUST_NO')
                //     ->where('job_m.BRANCH_ID', 'IHTVN1')
                //     ->where('c.BRANCH_ID', 'IHTVN1')
                //     ->where('job_m.INPUT_DT', '>=', '20190101000000')
                //     ->whereIn('job_m.ORDER_DATE', [$from_date, $to_date])
                //     ->orderBy('job_m.JOB_NO')
                //     ->select('c.CUST_NAME', 'job_m.JOB_NO', 'job_m.ORDER_DATE', 'job_m.CUST_NO', 'job_m.ORDER_FROM', 'job_m.ORDER_TO', 'job_m.NW', 'job_m.GW', 'job_m.POL', 'job_m.POL', 'job_m.POD', 'job_m.ETD_ETA', 'job_m.PO_NO', 'job_m.CONTAINER_QTY', 'job_m.CONSIGNEE', 'job_m.CUSTOMS_DATE', 'job_m.SHIPPER')
                //     ->chunk(5000, function ($job_m) use (&$data) {
                //         // Do something
                //         foreach ($job_m as $item) {
                //             dd($job_m,$item);
                //             $job_d = DB::table('JOB_ORDER_D as job_d')
                //                 ->leftJoin('PAY_TYPE as pt', 'pt.PAY_NO', 'job_d.ORDER_TYPE')
                //                 ->where('job_d.BRANCH_ID', 'IHTVN1')
                //                 ->where('job_d.INPUT_DT', '>=', '20190101000000')
                //                 ->where('job_d.JOB_NO', $item->JOB_NO)
                //                 ->select('pt.PAY_NAME', 'job_d.JOB_NO', 'job_d.SER_NO', 'job_d.DESCRIPTION', 'job_d.PORT_AMT', 'job_d.NOTE', 'job_d.UNIT', 'job_d.QTY', 'job_d.PRICE', 'job_d.TAX_AMT', 'job_d.TAX_NOTE')
                //                 ->get();
                //             $item->job_d = $job_d;
                //         }

                //         $data[0] = $job_m->toArray();
                //         dd($data[0]);
                //     });
                // dd($data);
                $data = DB::select("select c.CUST_NAME, job.JOB_NO, job.ORDER_DATE, job.CUST_NO, job.ORDER_FROM, job.ORDER_TO, job.NW, job.GW, job.POL, job.POL, job.POD, job.ETD_ETA, job.PO_NO, job.CONTAINER_QTY, job.CONSIGNEE, job.CUSTOMS_DATE, job.SHIPPER
                FROM JOB_ORDER_M job
                LEFT JOIN CUSTOMER c
                ON job.CUST_NO =c.CUST_NO
                WHERE job.BRANCH_ID='IHTVN1'
                AND  c.BRANCH_ID='IHTVN1'
                AND  job.INPUT_DT >='20190101000000'
                AND  job.ORDER_DATE >= '" . $from_date . "'
                AND  job.ORDER_DATE <= '" . $to_date . "'
                ORDER BY job.JOB_NO ");
                foreach ($data as $item) {
                    $job_d = DB::select("select pt.PAY_NAME, job_d.JOB_NO, job_d.SER_NO, job_d.DESCRIPTION, job_d.PORT_AMT, job_d.NOTE, job_d.UNIT, job_d.QTY, job_d.PRICE, job_d.TAX_AMT, job_d.TAX_NOTE
                    FROM JOB_ORDER_D job_d
                    RIGHT JOIN PAY_TYPE pt
                    ON job_d.ORDER_TYPE = pt.PAY_NO
                    WHERE job_d.BRANCH_ID='IHTVN1'
                    AND  job_d.INPUT_DT >='20190101000000'
                    AND  job_d.JOB_NO = '" . $item->JOB_NO . "'
                    ORDER BY job_d.JOB_NO");
                    $item->job_d = $job_d;
                }
                break;
        }
        return $data;
    }

    public static function jobOrder($jobno)
    {
        try {
            $data =  DB::table('JOB_ORDER_M as jom')
                ->leftJoin('CUSTOMER as c', 'jom.CUST_NO', 'c.CUST_NO')
                ->leftJoin('LENDER as l', 'jom.JOB_NO', 'l.JOB_NO')
                ->where('jom.JOB_NO', $jobno)
                ->where('jom.INPUT_DT', '>=', '20190101000000')
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
                ->where('jom.INPUT_DT', '>=', '20190101000000')
                ->where('jom.JOB_NO', $jobno)
                ->select('jod.*')
                ->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    //2.2 in job order theo ngay

    // public static function getJobOrder_D($jobno)
    // {
    //     try {
    //         $data =  DB::table('JOB_ORDER_D as jod')
    //             ->rightJoin('PAY_TYPE as pt', 'jod.ORDER_TYPE', 'pt.PAY_NO')
    //             ->where('jod.JOB_NO', $jobno)
    //             ->select('pt.PAY_NAME', 'jod.JOB_NO', 'jod.SER_NO', 'jod.DESCRIPTION', 'jod.PORT_AMT', 'jod.NOTE', 'jod.UNIT', 'jod.QTY', 'jod.PRICE', 'jod.TAX_AMT', 'jod.TAX_NOTE')
    //             ->get();
    //         return $data;
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }
    //2.2 in job order theo khach hang
    public static function getJobOrderCustomer($custno)
    {
        try {
            $data =  DB::table('JOB_ORDER_M as jom')
                ->leftJoin('CUSTOMER as c', 'jom.CUST_NO', 'c.CUST_NO')
                ->where('c.CUST_NO', $custno)
                ->where('jom.BRANCH_ID', 'IHTVN1')
                ->where('c.BRANCH_ID', 'IHTVN1')
                ->where('jom.INPUT_DT', '>=', '20190101000000')
                ->select('jom.ID', 'jom.JOB_NO', 'jom.ORDER_DATE')
                ->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    // public static function jobOrderCustomer($custno, $jobno)
    // {
    //     try {
    //         $array_jobno = explode(",", $jobno);
    //         $data =  DB::table('JOB_ORDER_M as jom')
    //             ->leftJoin('CUSTOMER as c', 'jom.CUST_NO', 'c.CUST_NO')
    //             ->where('jom.INPUT_DT', '>=', '20190101000000')
    //             ->where('c.CUST_NO', $custno)
    //             ->whereIn('jom.JOB_NO', $array_jobno)
    //             ->select('c.CUST_NAME', 'jom.*')
    //             ->where('jom.BRANCH_ID', 'IHTVN1')
    //             ->where('c.BRANCH_ID', 'IHTVN1')
    //             ->distinct()
    //             ->get();
    //         return $data;
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }
    // public static function jobOrderCustomer_D($custno, $jobno)
    // {
    //     try {
    //         $array_jobno = explode(",", $jobno);
    //         $data =  DB::table('JOB_ORDER_M as jom')
    //             ->leftJoin('CUSTOMER as c', 'jom.CUST_NO', 'c.CUST_NO')
    //             ->leftJoin('JOB_ORDER_D as jod', 'jom.JOB_NO', 'jod.JOB_NO')
    //             ->rightJoin('PAY_TYPE as pt', 'jod.ORDER_TYPE', 'pt.PAY_NO')
    //             ->where('jom.INPUT_DT', '>=', '20190101000000')
    //             ->where('c.CUST_NO', $custno)
    //             ->whereIn('jom.JOB_NO', $array_jobno)
    //             ->select('pt.PAY_NAME', 'jod.*')
    //             ->get();
    //         return $data;
    //     } catch (\Exception $e) {
    //         return $e;
    //     }
    // }
    //--------------------------------//--------------------------------------

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
                ->where('jom.INPUT_DT', '>=', '20190101000000')
                ->where('js.INPUT_DT', '>=', '20190101000000')
                ->where('c.BRANCH_ID', 'IHTVN1')
                ->whereBetween('js.JOB_DATE', [$fromdate, $todate])
                ->where(function ($query) {
                    $query->where('jod.THANH_TOAN_MK', 'N')
                        ->orWhere('jod.THANH_TOAN_MK', null);
                })
                ->select('c.CUST_NO', 'c.CUST_NAME', 'jom.BILL_NO', 'jod.*');
            switch (true) {
                case ($type == '1') || ($type == "carriers"):
                    $query->join('CUSTOMER as c', 'jom.CUST_NO2', 'c.CUST_NO')
                        ->where('jod.ORDER_TYPE', '5')
                        ->where('c.CUST_TYPE', 2);
                    ($custno == 'undefined' || $custno == 'null' || $custno == null) ? null : $query->where('jom.CUST_NO2', $custno);
                    break;
                case ($type == '2') || ($type == "customer"):
                    $query->join('CUSTOMER as c', 'jom.CUST_NO', 'c.CUST_NO')
                        ->where('jod.ORDER_TYPE', '6')
                        ->where('c.CUST_TYPE', 1);
                    ($custno == 'undefined' || $custno == 'null' || $custno == null) ? null : $query->where('jom.CUST_NO', $custno);
                    break;
                case $type == '3' || $type == "agent":
                    $query->leftJoin('CUSTOMER as c', 'jom.CUST_NO3', 'c.CUST_NO')
                        ->leftJoin('CUSTOMER as c2', 'jom.CUST_NO', 'c2.CUST_NO')
                        ->where('jod.ORDER_TYPE', '7')
                        ->where('c.CUST_TYPE', 3)
                        ->where('c2.BRANCH_ID', 'IHTVN1')
                        ->selectRaw('c2.CUST_NO as CUST_NO2 , c2.CUST_NAME as CUST_NAME2');
                    ($custno == 'undefined' || $custno == 'null' || $custno == null) ? null : $query->where('jom.CUST_NO3', $custno);
                    break;
            }
            //job_no
            ($jobno == 'undefined' || $jobno == 'null' || $jobno == null) ? null : $query->where('jom.JOB_NO', $jobno);

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
                ->where('js.INPUT_DT', '>=', '20190101000000')
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
                ->where('jom.INPUT_DT', '>=', '20190101000000')
                ->where('js.INPUT_DT', '>=', '20190101000000')
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
                ->where('jom.INPUT_DT', '>=', '20190101000000')
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
                ->where('js.INPUT_DT', '>=', '20190101000000')
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

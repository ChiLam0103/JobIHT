<?php

namespace App\Http\Controllers\Api\v1\Statistic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use App\Models\Statistic\StatisticFile;
use App\Models\PayType;
use App\Models\Company;

class StatisticFileController extends Controller
{
    //1 in phieu theo doi
    public function jobStart($fromjob, $tojob)
    {
        $job = StatisticFile::jobStart($fromjob, $tojob);
        $company = Company::des('IHT');
        if ($job) {
            return view('print\file\job-start\job', [
                'job' => $job,
                'company' => $company
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'PHẢI CHỌN JOB THEO THỨ TỰ NHỎ ĐẾN LỚN'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    //2 in job order
    public function jobOrder($jobno)
    {
        $order_m = StatisticFile::jobOrder($jobno);
        $order_d = StatisticFile::jobOrder_D($jobno);
        $pay_type = PayType::listPayType_JobNo($jobno);
        $total_port = 0;
        if ($order_m) {
            return view('print\file\job-order\job', [
                'data' => $order_m,
                'order_d' => $order_d,
                'pay_type' => $pay_type,
                'total_port' => $total_port,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'null'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    public function jobOrderBoat($jobno)
    {
        $order_m = StatisticFile::jobOrder($jobno);
        $order_d = StatisticFile::jobOrder_D($jobno);
        $pay_type = PayType::listPayType_JobNo($jobno);
        $total_port = 0;
        $total_tienTruocThue = 0;
        $total_tienThue = 0;
        $total_tongTien = 0;

        if ($order_m) {
            return view('print\file\job-order\job-boat', [
                'data' => $order_m,
                'order_d' => $order_d,
                'pay_type' => $pay_type,
                'total_port' => $total_port,
                'total_tienTruocThue' => $total_tienTruocThue,
                'total_tienThue' => $total_tienThue,
                'total_tongTien' => $total_tongTien,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'null'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    public function getJobOrderCustomer($custno)
    {

        $job_m = StatisticFile::getJobOrderCustomer($custno);
        if ($job_m) {
            return response()->json([
                'success' => true,
                'job_m' => $job_m,
            ], Response::HTTP_OK);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'null'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    public function jobOrderCustomer($custno, $jobno)
    {

        $job_m = StatisticFile::jobOrderCustomer($custno, $jobno);
        $job_d = StatisticFile::jobOrderCustomer_D($custno, $jobno);
        // dd($job_m,$job_d);
        if ($job_m) {
            return view('print\file\job-order\customer', [
                'job_m' => $job_m,
                'job_d' => $job_d
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'null'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    public function jobOrder_Date($fromdate, $todate)
    {
        $job_m = StatisticFile::jobOrder_Date($fromdate, $todate);
        $job_d = StatisticFile::getJobOrder_D($fromdate, $todate);
        // dd($job_d);
        if ($job_m) {
            return view('print\file\job-order\date', [
                'job_m' => $job_m,
                'job_d' => $job_d
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'null'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    //3.bao bieu refund
    public function refund($type, $custno, $jobno, $fromdate, $todate)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $from_date = ($fromdate == 'undefined' || $fromdate == 'null' || $fromdate == null) ? '19000101' :  $fromdate;
        $to_date = ($fromdate == 'undefined' || $fromdate == 'null' || $fromdate == null) ? $today : $todate;
        $sum_money_before = 0;
        $sum_money_after = 0;
        $sum_tax_money = 0;
        $type_name = "HÃNG TÀU";
        if ($type == 2) {
            $type_name = "KHÁCH HÀNG";
        } elseif ($type == 3) {
            $type_name = "ĐẠI LÝ";
        }

        $data = StatisticFile::refund($type, $custno, $jobno, $from_date, $to_date);
        foreach ($data as $item) {
            $sum_money_before += $item->TAX_NOTE == 0 ? $item->PRICE * $item->QTY : $item->TAX_AMT * $item->TAX_NOTE * $item->QTY;
            $sum_money_after += $item->TAX_NOTE == 0 ? $item->PRICE * $item->QTY : $item->TAX_AMT * $item->TAX_NOTE + $item->TAX_AMT * $item->QTY;
            $sum_tax_money += $item->TAX_AMT;
        }
        if ($data) {
            return view('print\file\refund\index', [
                'data' => $data,
                'type_name' => $type_name,
                'todate' => $to_date,
                'fromdate' => $from_date,
                'sum_money_before' => $sum_money_before,
                'sum_money_after' => $sum_money_after,
                'sum_tax_money' => $sum_tax_money,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'null'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    //4.thong ke
    public function statisticCreatedJob($cust, $user, $fromdate, $todate)
    {

        $data = StatisticFile::statisticCreatedJob($cust, $user, $fromdate, $todate);
        if ($data) {
            return view('print\file\statistic-job-order\created', [
                'data' => $data,
                'todate' => $todate,
                'fromdate' => $fromdate
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'null'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    public function statisticUserImportJob($cust, $user,  $fromdate, $todate)
    {

        $data = StatisticFile::statisticUserImportJob($cust, $user, $fromdate, $todate);
        $job_d = StatisticFile::statisticUserImportJob_D($cust, $user, $fromdate, $todate);
        if ($data) {
            return view('print\file\statistic-job-order\user-import', [
                'data' => $data,
                'job_d' => $job_d,
                'todate' => $todate,
                'fromdate' => $fromdate
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'null'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}

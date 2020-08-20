<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Prints;
use App\Models\PayType;


class PrintController extends Controller
{
    //1 in phieu theo doi
    public function jobStart($jobfrom, $jobto)
    {
        $job = Prints::jobStart($jobfrom, $jobto);
        if ($job) {
            return view('print\job-start\job')->with('job', $job);
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
        $order_m = Prints::jobOrder($jobno);
        $order_d = Prints::jobOrder_D($jobno);
        $pay_type = PayType::listPayType_JobNo($jobno);
        $total_port = 0;
        if ($order_m) {
            return view('print\job-order\job', [
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
        $order_m = Prints::jobOrder($jobno);
        $order_d = Prints::jobOrder_D($jobno);
        $pay_type = PayType::listPayType_JobNo($jobno);
        $total_port = 0;
        $total_tienTruocThue = 0;
        $total_tienThue = 0;
        $total_tongTien = 0;

        if ($order_m) {
            return view('print\job-order\job-boat', [
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
    public function jobOrder_Date($fromdate, $todate)
    {
        $job_m = Prints::jobOrder_Date($fromdate, $todate);
        $job_d = Prints::getJobOrder_D($fromdate, $todate);
        // dd($job_d);
        if ($job_m) {
            return view('print\job-order\date', [
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
    public function refund($type, $id, $jobno, $todate, $fromdate)
    {
        $type_name = "HÃNG TÀU";
        if ($type == 2) {
            $type_name = "KHÁCH HÀNG";
        } elseif ($type == 3) {
            $type_name = "ĐẠI LÝ";
        }
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $todate = $todate != 'null' ? $todate : '19000101';
        $fromdate = $fromdate != 'null' ? $fromdate : $today;
        $data = Prints::refund($type, $id, $jobno, $todate, $fromdate);
        if ($data) {
            return view('print\refund', [
                'data' => $data,
                'type_name' => $type_name,
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
    public function statisticCreatedJob($cust, $user, $todate, $fromdate)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $todate = $todate != 'null' ? $todate : '';
        $fromdate = $fromdate != 'null' ? $fromdate : $today;
        $data = Prints::statisticCreatedJob($cust, $user, $todate, $fromdate);
        if ($data) {
            return view('print\statistic-created-job', [
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
    public function statisticUserImportJob($cust, $user, $todate, $fromdate)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $todate = $todate != 'null' ? $todate : '';
        $fromdate = $fromdate != 'null' ? $fromdate : $today;
        $data = Prints::statisticUserImportJob($cust, $user, $todate, $fromdate);
        if ($data) {
            return view('print\statistic-created-job', [
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
}

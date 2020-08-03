<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Prints;
use App\Models\PayType;


class PrintController extends Controller
{

    public function jobStart($id)
    {
        $data = Prints::jobStart($id);
        if ($data) {
            return view('print\job-start')->with('data', $data);
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
    public function jobOrder($id)
    {
        $order_m = Prints::jobOrder($id);
        $order_d = Prints::jobOrder_D($id);
        $pay_type = PayType::listPayType_JobNo($id);
        $sum_port = 0;
        $sum_kcn = 0;
        $total_port = 0;
        $total_kcn = 0;
        if ($order_m) {
            return view('print\job-order', [
                'data' => $order_m,
                'order_d' => $order_d,
                'pay_type' => $pay_type,
                'sum_port' => $sum_port,
                'sum_kcn' => $sum_kcn,
                'total_port' => $total_port,
                'total_kcn' => $total_kcn
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
    public function jobOrder_Date($todate, $fromdate)
    {
        $data = Prints::jobOrder_Date($todate, $fromdate);
        if ($data) {
            return view('print\job-start')->with('data', $data);
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

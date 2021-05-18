<?php

namespace App\Http\Controllers\Api\v1\Prints;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use App\Models\Statistic\StatisticFile;
use App\Models\PayType;
use App\Models\Company;
use Illuminate\Http\Request;


class FileController extends Controller
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
    public function getJobOrder(Request $request) //print new
    {
        try {
            $data = StatisticFile::JobOrderNew($request);
            $total_port = 0;
            switch ($request->type) {
                case 'job':
                    $pay_type = PayType::listPayType_JobNo($request->job_no);
                    return view('print\file\job-order-new\job', [
                        'data' => $data,
                        'pay_type' => $pay_type,
                        'total_port' => $total_port,
                    ]);
                    break;
                case 'customer':
                    return view('print\file\job-order-new\customer', [
                        'data' => $data,
                    ]);
                    break;
                case 'date':
                    return view('print\file\job-order-new\date', [
                        'data' => $data,
                    ]);
                    break;
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => $e
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
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
        $data = StatisticFile::jobOrder_Date($fromdate, $todate);

        if ($data) {
            return view('print\file\job-order\date', [
                'job_m' => $data['job_m'],
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
        $to_date = ($todate == 'undefined' || $todate == 'null' || $todate == null) ? $today : $todate;

        switch (true) {
            case ($type == 'carriers') || ($type == "1"):
                $type_name = "HÃNG TÀU";
                break;
            case ($type == 'customer') || ($type == "2"):
                $type_name = "KHÁCH HÀNG";
                break;
            case ($type == 'agency') || ($type == "3"):
                $type_name = "ĐẠI LÝ";
                break;
            default:
                break;
        }

        $data = StatisticFile::refund($type, $custno, $jobno, $from_date, $to_date);
        if ($data) {
            return view('print\file\refund\index', [
                'data' => $data,
                'type_name' => $type_name,
                'todate' => $to_date,
                'fromdate' => $from_date,
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
    public function getRefund(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $from_date = ($request->fromdate == 'undefined' || $request->fromdate == 'null' || $request->fromdate == null) ? '19000101' :  $request->fromdate;
        $to_date = ($request->todate == 'undefined' || $request->todate == 'null' || $request->todate == null) ? $today : $request->todate;

        switch (true) {
            case ($request->type == 'carriers') || ($request->type == "1"):
                $type_name = "HÃNG TÀU";
                break;
            case ($request->type == 'customer') || ($request->type == "2"):
                $type_name = "KHÁCH HÀNG";
                break;
            case ($request->type == 'agency') || ($request->type == "3"):
                $type_name = "ĐẠI LÝ";
                break;
            default:
                break;
        }
        $data = StatisticFile::refund($request->type, $request->custno, $request->jobno, $from_date, $to_date);
        if ($data) {
            return view('print\file\refund\index', [
                'data' => $data,
                'type_name' => $type_name,
                'todate' => $to_date,
                'fromdate' => $from_date,
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

<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use App\Models\PrintFile;
use App\Models\PayType;
use App\Models\Company;

class PrintFileController extends Controller
{
    //1 in phieu theo doi
    public function jobStart($fromjob, $tojob)
    {
        $job = PrintFile::jobStart($fromjob, $tojob);
        $company=Company::des('IHT');
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
        $order_m = PrintFile::jobOrder($jobno);
        $order_d = PrintFile::jobOrder_D($jobno);
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
        $order_m = PrintFile::jobOrder($jobno);
        $order_d = PrintFile::jobOrder_D($jobno);
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

        $job_m = PrintFile::getJobOrderCustomer($custno);
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

        $job_m = PrintFile::jobOrderCustomer($custno, $jobno);
        $job_d = PrintFile::jobOrderCustomer_D($custno, $jobno);
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
        $job_m = PrintFile::jobOrder_Date($fromdate, $todate);
        $job_d = PrintFile::getJobOrder_D($fromdate, $todate);
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
        $type_name = "HÃNG TÀU";
        if ($type == 2) {
            $type_name = "KHÁCH HÀNG";
        } elseif ($type == 3) {
            $type_name = "ĐẠI LÝ";
        }

        $data = PrintFile::refund($type, $custno, $jobno, $fromdate, $todate);
        if ($data) {
            return view('print\file\refund\index', [
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
    //4.thong ke
    public function statisticCreatedJob($cust, $user, $fromdate, $todate)
    {

        $data = PrintFile::statisticCreatedJob($cust, $user, $fromdate, $todate);
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

        $data = PrintFile::statisticUserImportJob($cust, $user, $fromdate, $todate);
        $job_d = PrintFile::statisticUserImportJob_D($cust, $user, $fromdate, $todate);
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

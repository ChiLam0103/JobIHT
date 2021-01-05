<?php

namespace App\Http\Controllers\Api\v1\Statistic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use App\Models\Statistic\StatisticFile;
use App\Models\PayType;
use App\Models\Company;
use GuzzleHttp\Psr7\Request;
use Maatwebsite\Excel\Facades\Excel;

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
    //2.1 export date
    public function exportJobOrder_Date(Request $request)
    {
        $job_m = StatisticFile::jobOrder_Date($request->fromdate, $request->todate);
        $job_d = StatisticFile::getJobOrder_D($request->fromdate, $request->todate);
        if ($job_m) {

            $filename = 'job-order-date' . '(' . date('YmdHis') . ')';

            Excel::create($filename, function ($excel) use ($job_m, $job_d) {
                $excel->sheet('JOB ORDER', function ($sheet) use ($job_m, $job_d) {
                    $sheet->loadView('print\file\job-order\export-date', [
                        'job_m' => $job_m,
                        'job_d' => $job_d,
                    ]);
                    $sheet->setOrientation('landscape');
                });
            })->download('xlsx');
            return response()->json([
                'url' => 'https://job-api.ihtvn.com/storage/exports/' . $filename . '.xlsx',
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

        $type_name = "HÃNG TÀU";
        if ($type == 2) {
            $type_name = "KHÁCH HÀNG";
        } elseif ($type == 3) {
            $type_name = "ĐẠI LÝ";
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
    //3.1 export
    public function postExportRefund(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $from_date = ($request->fromdate == 'undefined' || $request->fromdate == 'null' || $request->fromdate == null) ? '19000101' :  $request->fromdate;
        $to_date = ($request->todate == 'undefined' || $request->todate == 'null' || $request->todate == null) ? $today : $request->todate;
        $sum_price = 0;
        $sum_money_after = 0;
        switch ($request->type) {
            case 'carriers' || '1':
                $type_name = "HÃNG TÀU";
                break;
            case 'customer' || '2':
                $type_name = "KHÁCH HÀNG";
                break;
            case 'agency' || '3':
                $type_name = "ĐẠI LÝ";
                break;
            default:
                $type_name = "HÃNG TÀU";
                break;
        }

        $data = StatisticFile::postExportRefund($request);
        if ($data) {

            $filename = 'refund' . '(' . date('YmdHis') . ')';
            Excel::create($filename, function ($excel) use ($data, $type_name, $from_date, $to_date, $sum_price, $sum_money_after) {
                $excel->sheet('Debit Note', function ($sheet) use ($data, $type_name, $from_date, $to_date, $sum_price, $sum_money_after) {
                    $sheet->loadView('print\file\refund\export', [
                        'data' => $data,
                        'type_name' => $type_name,
                        'from_date' => $from_date,
                        'to_date' => $to_date,
                        'sum_price' => $sum_price,
                        'sum_money_after' => $sum_money_after
                    ]);
                    $sheet->setOrientation('landscape');
                });
            })->store('xlsx');
            return response()->json([
                'url' => 'https://job-api.ihtvn.com/storage/exports/' . $filename . '.xlsx',
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
    //5 thống kê nâng hạ
    public function lifting($fromdate, $todate)
    {
        $data = StatisticFile::lifting($fromdate, $todate);
        if ($data) {
            ob_end_clean();
            ob_start(); //At the very top of your program (first line)
            return Excel::create($fromdate . '-'  . $todate . '-' . 'THỐNG KÊ NÂNG HẠ', function ($excel) use ($data) {
                $excel->sheet('Debit Note', function ($sheet) use ($data) {
                    $sheet->loadView('print\file\lifting\index', [
                        'data' => $data
                    ]);
                    $sheet->setOrientation('landscape');
                });
            })->download('xlsx');
            ob_flush();
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

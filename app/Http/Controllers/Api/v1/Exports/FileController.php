<?php

namespace App\Http\Controllers\Api\v1\Exports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Models\PayType;
use App\Models\JobD;
use App\Models\Statistic\StatisticFile;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Input;

class FileController extends Controller
{
    //2.1 export date
    public function exportJobOrder(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $data = StatisticFile::jobOrderNew($request);

        switch ($request->type) {
            case 'job':
                $pay_type = PayType::listPayType_JobNo($request->job_no);
                $total_port = 0;
                $filename = 'job-order' . '(' . date('YmdHis') . ')';
                Excel::create($filename, function ($excel) use ($data, $pay_type, $total_port) {
                    $excel->sheet('JOB ORDER', function ($sheet) use ($data, $pay_type, $total_port) {
                        $sheet->loadView('export\file\job-order-new\export-job', [
                            'data' => $data,
                            'pay_type' => $pay_type,
                            'total_port' => $total_port,
                        ]);
                        $sheet->setOrientation('landscape');
                    });
                })->store('xlsx');
                break;
            case 'customer':
                $filename = 'job-order-customer' . '(' . date('YmdHis') . ')';
                Excel::create($filename, function ($excel) use ($data) {
                    $excel->sheet('JOB ORDER CUSTOMER', function ($sheet) use ($data) {
                        $sheet->loadView('export\file\job-order-new\export-customer', [
                            'data' => $data,
                        ]);
                        $sheet->setOrientation('landscape');
                    });
                })->store('xlsx');
                break;
            case 'date':
                $filename = 'job-order-date' . '(' . date('YmdHis') . ')';
                Excel::create($filename, function ($excel) use ($data) {
                    $excel->sheet('JOB ORDER DATE', function ($sheet) use ($data) {
                        $sheet->loadView('export\file\job-order-new\export-date', [
                            'data' => $data,
                        ]);
                        $sheet->setOrientation('landscape');
                    });
                })->store('xlsx');
                break;
        }

        return response()->json([
            'url' => 'https://job-api.ihtvn.com/storage/exports/' . $filename . '.xlsx',
        ]);
    }
    public function exportJobOrder_Date(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $from_date = ($request->fromdate == 'undefined' || $request->fromdate == 'null' || $request->fromdate == null) ? '19000101' :  $request->fromdate;
        $to_date = ($request->todate == 'undefined' || $request->todate == 'null' || $request->todate == null) ? $today : $request->todate;
        $data = StatisticFile::jobOrder_Date($request->fromdate, $request->todate);
        if ($data) {
            $filename = 'job-order-date' . '(' . date('YmdHis') . ')';
            Excel::create($filename, function ($excel) use ($data, $from_date, $to_date) {
                $excel->sheet('JOB ORDER', function ($sheet) use ($data, $from_date, $to_date) {
                    $sheet->loadView('export\file\job-order\export-date', [
                        'data' => $data,
                        'from_date' => $from_date,
                        'to_date' => $to_date,
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
    //2.1 import excel thêm or chỉnh sửa chi phí job_d
    public function importJobOrder()
    {
        Excel::load(Input::file('file'), function ($reader) {
            foreach ($reader->toArray() as $array) {
                JobD::importJobOrder($array);
            }
        });
        return response()->json([
            'success' => true,
        ]);
    }
    //3.1 export
    public function postExportRefund(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $fromdate = ($request->fromdate == 'undefined' || $request->fromdate == 'null' || $request->fromdate == null) ? '19000101' :  $request->fromdate;
        $todate = ($request->todate == 'undefined' || $request->todate == 'null' || $request->todate == null) ? $today : $request->todate;
        $sum_price = 0;
        $sum_money_after = 0;
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
        $data = StatisticFile::refund($request->type, $request->custno, $request->jobno, $request->fromdate, $request->todate);
        if ($data) {
            $filename = 'refund' . '(' . date('YmdHis') . ')';
            Excel::create($filename, function ($excel) use ($data, $type_name, $fromdate, $todate, $sum_price, $sum_money_after) {
                $excel->sheet('Debit Note', function ($sheet) use ($data, $type_name, $fromdate, $todate, $sum_price, $sum_money_after) {
                    $sheet->loadView('export\file\refund\index', [
                        'data' => $data,
                        'type_name' => $type_name,
                        'fromdate' => $fromdate,
                        'todate' => $todate,
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
    //4.thong ke tạo job
    public function statisticCreatedJob(Request $request)
    {
        $fromdate = $request->fromdate;
        $todate = $request->todate;
        $data = StatisticFile::statisticCreatedJob($request->cust, $request->user, $fromdate, $todate);
        if ($data) {
            $filename = 'create-job' . '(' . date('YmdHis') . ')';
            Excel::create($filename, function ($excel) use ($data, $fromdate, $todate) {
                $excel->sheet('Debit Note', function ($sheet) use ($data, $fromdate, $todate) {
                    $sheet->loadView('export\file\job-order\create-job', [
                        'data' => $data,
                        'fromdate' => $fromdate,
                        'todate' => $todate,
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
    //5 thống kê nâng hạ (export)
    public function lifting($fromdate, $todate)
    {
        $data = StatisticFile::lifting($fromdate, $todate);
        if ($data) {
            ob_end_clean();
            ob_start(); //At the very top of your program (first line)
            return Excel::create($fromdate . '-'  . $todate . '-' . 'THỐNG KÊ NÂNG HẠ', function ($excel) use ($data) {
                $excel->sheet('Debit Note', function ($sheet) use ($data) {
                    $sheet->loadView('export\file\lifting\index', [
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

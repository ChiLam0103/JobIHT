<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use App\Models\PrintPayment;
use App\Models\JobD;
use App\Models\Company;

class PrintPaymentController extends Controller
{
    //1 in phieu theo doi
    //1.1phieu chi
    public function advancePayment($fromadvance, $toadvance)
    {
        $type = '1';
        $title_vn = 'PHIẾU CHI TẠM ỨNG';
        $title_cn = '現金借支取款單';
        $data = PrintPayment::advancePayment($fromadvance, $toadvance);
        if ($data) {
            return view('print\payment\advance-payment\index', [
                'data' => $data,
                'type' => $type,
                'title_vn' => $title_vn,
                'title_cn' => $title_cn,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'PHẢI CHỌN PHIẾU THEO THỨ TỰ NHỎ ĐẾN LỚN'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    //1.2.phieu bu
    public function replenishment($fromadvance, $toadvance)
    {
        $data = PrintPayment::advancePayment($fromadvance, $toadvance);
        foreach ($data as $item) {
            $job = JobD::getJobD($item->JOB_NO);
        }
        $moneyspent = 0;
        $type = '2';
        $title_vn = 'PHIẾU BÙ';
        $title_cn = '現金補款單';
        $money_compensate_vn = "Số Tiền Bù";
        $money_compensate_cn = "補金額";
        $user_money_vn = 'Người Bù Tiền';
        $user_money_cn = '補款人';

        if ($data) {
            return view('print\payment\advance-payment\payment', [
                'data' => $data,
                'job' => $job,
                'moneyspent' => $moneyspent,
                'type' => $type,
                'title_vn' => $title_vn,
                'title_cn' => $title_cn,
                'money_compensate_vn' => $money_compensate_vn,
                'money_compensate_cn' => $money_compensate_cn,
                'user_money_vn' => $user_money_vn,
                'user_money_cn' => $user_money_cn,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'PHẢI CHỌN PHIẾU THEO THỨ TỰ NHỎ ĐẾN LỚN'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    //1.3.phieu tra
    public function withdrawal($fromadvance, $toadvance)
    {
        $data = PrintPayment::advancePayment($fromadvance, $toadvance);
        foreach ($data as $item) {
            $job = JobD::getJobD($item->JOB_NO);
        }
        $moneyspent = 0;
        $type = '3';
        $title_vn = 'PHIẾU TRẢ';
        $title_cn = '現金退款單';
        $money_compensate_vn = "Số Tiền Trả";
        $money_compensate_cn = "退金額";
        $user_money_vn = 'Người Trả Tiền';
        $user_money_cn = '退款人';
        if ($data) {
            return view('print\payment\advance-payment\payment', [
                'data' => $data,
                'job' => $job,
                'moneyspent' => $moneyspent,
                'type' => $type,
                'title_vn' => $title_vn,
                'title_cn' => $title_cn,
                'money_compensate_vn' => $money_compensate_vn,
                'money_compensate_cn' => $money_compensate_cn,
                'user_money_vn' => $user_money_vn,
                'user_money_cn' => $user_money_cn,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'PHẢI CHỌN PHIẾU THEO THỨ TỰ NHỎ ĐẾN LỚN'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    //1.5.phieu tam ung
    public function advance($fromdate, $todate)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $fromdate = $fromdate != 'null' ? $fromdate : '19000101';
        $todate = $todate != 'null' ? $todate : $today;
        $data = PrintPayment::advancePayment($fromdate, $todate);
        foreach ($data as $item) {
            $job = JobD::getJobD($item->JOB_NO);
        }
        $type = '5';
        $title_vn = 'PHIẾU TẠM ỨNG';
        $title_cn = '現金借支取款單';
        if ($data) {
            return view('print\payment\advance-payment\index', [
                'data' => $data,
                'job' => $job,
                'type' => $type,
                'title_vn' => $title_vn,
                'title_cn' => $title_cn,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'PHẢI CHỌN PHIẾU THEO THỨ TỰ NHỎ ĐẾN LỚN'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    //1.6.phieu bu-tra tam ung
    public function replenishmentWithdrawal($moneyused, $fromadvance, $toadvance)
    {
        $data = PrintPayment::advancePayment($fromadvance, $toadvance);
        foreach ($data as $item) {
            $job = JobD::getJobD($item->JOB_NO);
        }
        $type = '6';
        $title_vn = 'PHIẾU BÙ';
        $title_cn = '現金補款單';
        $money_compensate_vn = "Số Tiền Bù";
        $money_compensate_cn = "補金額";
        $user_money_vn = 'Người Bù Tiền';
        $user_money_cn = '補款人';

        if ($data) {
            return view('print\payment\advance-payment\payment', [
                'moneyused' => $moneyused,
                'data' => $data,
                'job' => $job,
                'type' => $type,
                'title_vn' => $title_vn,
                'title_cn' => $title_cn,
                'money_compensate_vn' => $money_compensate_vn,
                'money_compensate_cn' => $money_compensate_cn,
                'user_money_vn' => $user_money_vn,
                'user_money_cn' => $user_money_cn,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'PHẢI CHỌN PHIẾU THEO THỨ TỰ NHỎ ĐẾN LỚN'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    //1.7 thống kê phiếu tạm ứng
    public function statisticalAdvance($fromdate, $todate)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $fromdate = $fromdate != 'null' ? $fromdate : '19000101';
        $todate = $todate != 'null' ? $todate : $today;
        $type = '7';
        $title = 'BÁO BIỂU THỐNG KÊ PHIẾU TẠM ỨNG';
        $total = 0;
        $data = PrintPayment::statisticalAdvance($fromdate, $todate, $type);
        if ($data) {
            return view('print\payment\advance-payment\statistical', [
                'data' => $data,
                'fromdate' => $fromdate,
                'todate' => $todate,
                'title' => $title,
                'total' => $total
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'PHẢI CHỌN NGÀY THEO THỨ TỰ NHỎ ĐẾN LỚN'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    //1.8 thống kê phiếu bù
    public function statisticalReplenishment($fromdate, $todate)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $fromdate = $fromdate != 'null' ? $fromdate : '19000101';
        $todate = $todate != 'null' ? $todate : $today;
        $type = '8';
        $title = 'BÁO BIỂU THỐNG KÊ PHIẾU BÙ';
        $total = 0;
        $moneyspent = 0;
        $data = PrintPayment::statisticalAdvance2($fromdate, $todate, $type);
        foreach ($data as $item) {
            $job = JobD::getJobD($item->JOB_NO);
        }
        if ($data) {
            return view('print\payment\advance-payment\statistical2', [
                'data' => $data,
                'job' => $job,
                'fromdate' => $fromdate,
                'todate' => $todate,
                'title' => $title,
                'total' => $total,
                'moneyspent' => $moneyspent,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'PHẢI CHỌN NGÀY THEO THỨ TỰ NHỎ ĐẾN LỚN'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    //1.10 thống kê phiếu chi trực tiếp
    public function statisticalDirect($fromdate, $todate)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $fromdate = $fromdate != 'null' ? $fromdate : '19000101';
        $todate = $todate != 'null' ? $todate : $today;
        $type = '10';
        $total = 0;
        $title = 'BÁO BIỂU THỐNG KÊ PHIẾU CHI TRỰC TIẾP';
        $data = PrintPayment::statisticalAdvance($fromdate, $todate, $type);
        if ($data) {
            return view('print\payment\advance-payment\statistical', [
                'data' => $data,
                'fromdate' => $fromdate,
                'todate' => $todate,
                'title' => $title,
                'total' => $total,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'PHẢI CHỌN NGÀY THEO THỨ TỰ NHỎ ĐẾN LỚN'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    //2 phiếu yêu cầu thanh toán
    public function debitNote($type = 'jobno', $formjobno, $tojobno, $custno, $fromdate, $todate, $debittype, $person, $phone)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $fromdate = $fromdate != 'null' ? $fromdate : '19000101';
        $todate = $todate != 'null' ? $todate : $today;
        $data = PrintPayment::debitNote($type, $formjobno, $tojobno, $custno, $fromdate, $todate, $debittype, $person, $phone);
        $company = Company::des('IHT');
        if ($data) {
            return view('print\payment\debit-note', [
                'data' => $data,
                'company'=>$company,
                'fromdate' => $fromdate,
                'todate' => $todate,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'PHẢI CHỌN NGÀY THEO THỨ TỰ NHỎ ĐẾN LỚN'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}

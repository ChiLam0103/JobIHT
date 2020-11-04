<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use App\Models\PrintPayment;
use App\Models\JobD;
use App\Models\Company;

class PrintPaymentController extends Controller
{
    //1.phieu chi tam ung new
    public function advance($advance_no)
    {
        $type = '1';
        $title_vn = '';
        $title_cn = '現金借支取款單';
        $advance = PrintPayment::advance($advance_no);
        if ($advance->LENDER_TYPE == 'T') {
            $title_vn = 'PHIẾU CHI TẠM ỨNG';
        } elseif ($advance->LENDER_TYPE == 'C') {
            $title_vn = 'PHIẾU CHI TRỰC TIẾP';
        } elseif ($advance->LENDER_TYPE == 'U') {
            $title_vn = 'PHIẾU TẠM ỨNG';
        }
        $advance_d = PrintPayment::advance_D($advance_no);
        if ($advance) {
            return view('print\payment\advance\index', [
                'advance' => $advance,
                'advance_d' => $advance_d,
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
                'company' => $company,
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
    //8. thống kê phiếu thu
    public function receipt($receiptno)
    {
        $type = '1';
        $title_vn = 'PHIẾU THU';
        $receipt = PrintPayment::receipt($receiptno);
        $company = Company::des('IHT');
        // dd($receipt,$company);
        if ($receipt) {
            return view('print\payment\receipt\index', [
                'receipt' => $receipt,
                'company' => $company,
                'type' => $type,
                'title_vn' => $title_vn,
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
}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use App\Models\PrintPayment;

use App\Models\Company;
use App\Models\Bank;
use App\Models\Receipts;

class PrintPaymentController extends Controller
{
    //1.phieu chi tam ung new
    public function advance($advance_no)
    {
        $type = '1';
        $title_vn = '';
        $title_en = '現金借支取款單';
        $advance = PrintPayment::advance($advance_no);
        if ($advance->LENDER_TYPE == 'T') {
            $title_vn = 'PHIẾU CHI TẠM ỨNG';
            $title_en='ADVANCE ';
        } elseif ($advance->LENDER_TYPE == 'C') {
            $title_vn = 'PHIẾU CHI TRỰC TIẾP';
            $title_en='ADVANCE PAYMENT ';
        } elseif ($advance->LENDER_TYPE == 'U') {
            $title_vn = 'PHIẾU TẠM ỨNG';
            $title_en='ADVANCE ';
        }
        $advance_d = PrintPayment::advance_D($advance_no);
        if ($advance) {
            return view('print\payment\advance\index', [
                'advance' => $advance,
                'advance_d' => $advance_d,
                'type' => $type,
                'title_vn' => $title_vn,
                'title_en' => $title_en,
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
    public function debitNote($type = 'jobno', $jobno,  $custno, $fromdate, $todate, $debittype, $person, $phone, $bank = 'ACB')
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $bank_no = ($bank == 'undefined' || $bank == 'null' || $bank == null) ? 'ACB' : $bank;
        $today = date("Ymd");
        $total_amt = 0;
        $dor_no = '';
        $fromdate = $fromdate != 'null' ? $fromdate : '19000101';
        $todate = $todate != 'null' ? $todate : $today;
        $debit = PrintPayment::debitNote($type, $jobno, $custno, $fromdate, $todate, $debittype, $person, $phone);
        $bank = Bank::des($bank_no);
        $company = Company::des('IHT');

        if ($debit == 'error-job-empty') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Nhập vào số job để xem dữ liệu!'
                ],
                Response::HTTP_BAD_REQUEST
            );
        } elseif ($debit == 'error-person-empty') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Nhập vào thông tin người liên lạc!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } elseif ($debit == 'error-phone') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Nhập vào số điện thoại người liên lạc!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            $debit_d = PrintPayment::debitNote_D($jobno);
            foreach ($debit_d as $item) {
                $total_amt += $item->QUANTITY * ($item->PRICE + $item->TAX_AMT);
                $dor_no = $item->DOR_NO;
            }
            // dd($company,$debit,$debit_d);
            return view('print\payment\debit-note\index', [
                'debit' => $debit,
                'debit_d' => $debit_d,
                'company' => $company,
                'person' => $person,
                'phone' => $phone,
                'fromdate' => $fromdate,
                'todate' => $todate,
                'total_amt' => $total_amt,
                'dor_no' => $dor_no,
                'bank'=>$bank
            ]);
        }
    }
    //8. thống kê phiếu thu
    public function receipt($receiptno)
    {
        $title_vn = 'PHIẾU THU';
        $receipt = PrintPayment::receipt($receiptno);
        // $list=Receipts::list();


        $company = Company::des('IHT');
        if ($receipt) {
            return view('print\payment\receipt\index', [
                'receipt' => $receipt,
                'company' => $company,
                'title_vn' => $title_vn,
                // 'list' => $list,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Phải chọn phiếu theo thứ tự từ nhỏ đến lớn!'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}

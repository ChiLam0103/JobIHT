<?php

namespace App\Http\Controllers\Api\v1\Statistic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Models\Statistic\StatisticPayment;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Company;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\JobD;
use App\Models\Personal;

class StatisticPaymentController extends Controller
{
    //1.phieu chi tam ung new
    public function advance($advance_no)
    {
        //khai bao bien
        $type = '1';
        $title_vn = '';
        $title_en = '現金借支取款單';
        $SUM_PORT_AMT = 0;
        $SUM_LENDER_AMT = 0;
        $title_sum_money = "";
        $INPUT_USER_jobD = "";
        $INPUT_DT_jobD = "";

        $advance = StatisticPayment::advance($advance_no);
        $advance_d = StatisticPayment::advance_D($advance_no);
        $job_d = JobD::getJob($advance->JOB_NO, "JOB_ORDER")->whereIn('jd.THANH_TOAN_MK', [null, 'N']);
        // $job_d = JobD::getJob($advance->JOB_NO, "JOB_ORDER")->where('jd.THANH_TOAN_MK', 'Y');
        foreach ($advance_d as $i) {
            $SUM_LENDER_AMT += $i->LENDER_AMT;
        }

        foreach ($job_d as $i) {
            $SUM_PORT_AMT += $i->PORT_AMT + $i->INDUSTRY_ZONE_AMT;
            $INPUT_USER_jobD = $i->INPUT_USER;
            $INPUT_DT_jobD = $i->INPUT_DT;
        }
        if ($SUM_PORT_AMT == 0) {
            $title_sum_money = "TỔNG TIỀN / TOTAL MONEY";
        }
        if ($advance->LENDER_TYPE == 'T') {
            $title_vn = 'Chi Tạm Ứng';
            $title_en = 'Advance ';
            if ($SUM_LENDER_AMT > $SUM_PORT_AMT) {
                $title_sum_money = "TỔNG TIỀN TRẢ / TOTAL PAYMENT";
            } elseif ($SUM_LENDER_AMT < $SUM_PORT_AMT) {
                $title_sum_money = "TỔNG TIỀN BÙ / TOTAL COMPENSATION";
            }
        } elseif ($advance->LENDER_TYPE == 'C') {
            $title_vn = 'Chi Trực Tiếp';
            $title_en = 'Advance Payment ';
            $title_sum_money = "TỔNG TIỀN / TOTAL MONEY";
        } elseif ($advance->LENDER_TYPE == 'U') {
            $title_sum_money = "TỔNG TIỀN / TOTAL MONEY";
            $title_vn = 'Tạm Ứng';
            $title_en = 'Advance ';
        }

        if ($advance) {
            return view('print\payment\advance\index', [
                'advance' => $advance,
                'advance_d' => $advance_d,
                'type' => $type,
                'title_vn' => $title_vn,
                'title_en' => $title_en,
                'SUM_PORT_AMT' => $SUM_PORT_AMT,
                'SUM_LENDER_AMT' => $SUM_LENDER_AMT,
                'title_sum_money' => $title_sum_money,
                'INPUT_USER_jobD' => $INPUT_USER_jobD,
                'INPUT_DT_jobD' => $INPUT_DT_jobD,
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
    //1.1thống kê phiếu bù và phiếu trả
    public function replenishmentWithdrawalPayment($advanceno)
    {
        $SUM_LENDER_AMT = 0; //tien ung
        $SUM_JOB_ORDER = 0; //tien job order
        $SUM_WITHDRAWAL = 0; //tien tra
        $SUM_REPLENISHMENT = 0; //tien bu
        $SUM_DIRECT = 0; //tien chi truc tiep
        $lender = StatisticPayment::replenishmentWithdrawalPayment($advanceno);

        foreach ($lender as $item) {
            $advance_d = StatisticPayment::advance_D($item->LENDER_NO);
            $job_d = JobD::getJob($item->JOB_NO, "JOB_ORDER")->whereIn('jd.THANH_TOAN_MK', [null, 'N']);

            foreach ($advance_d as $i) {
                $SUM_LENDER_AMT += $i->LENDER_AMT;
            }
            foreach ($job_d as $i) {
                $SUM_JOB_ORDER += $i->PORT_AMT + $i->INDUSTRY_ZONE_AMT;
            }
            //kiem tra phieu chi truc tiep
            if ($item->LENDER_TYPE == 'C') {
                $item->SUM_LENDER_AMT = 0;
                $item->SUM_DIRECT = $SUM_JOB_ORDER;
                $item->SUM_REPLENISHMENT = $SUM_JOB_ORDER;
                $item->SUM_WITHDRAWAL = $SUM_WITHDRAWAL;
            } else {
                if ($SUM_JOB_ORDER < $SUM_LENDER_AMT) {
                    $item->SUM_LENDER_AMT = $SUM_LENDER_AMT;
                    $item->SUM_WITHDRAWAL = $SUM_WITHDRAWAL;
                    $item->SUM_REPLENISHMENT =  $SUM_LENDER_AMT - $SUM_JOB_ORDER;
                    $item->SUM_MONEY = $SUM_LENDER_AMT + ($SUM_JOB_ORDER - $SUM_LENDER_AMT);
                    $item->SUM_DIRECT = $SUM_DIRECT;
                } else {
                    $item->SUM_LENDER_AMT = $SUM_LENDER_AMT;
                    $item->SUM_WITHDRAWAL =  $SUM_JOB_ORDER - $SUM_LENDER_AMT;
                    $item->SUM_REPLENISHMENT = $SUM_REPLENISHMENT;
                    $item->SUM_DIRECT = $SUM_DIRECT;
                }
            }
            $item->SUM_JOB_ORDER = $SUM_JOB_ORDER;
        }
        if ($lender) {
            return view('print\payment\advance\replenishmentWithdrawalPayment', [
                'lender' => $lender,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng chọn số phiếu!'
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
        $debit = StatisticPayment::debitNote($type, $jobno, $custno, $fromdate, $todate, $debittype, $person, $phone);
        $bank = Bank::des($bank_no);
        $customer = Customer::arrayCustomer($custno);
        // dd($customer);
        $person = Personal::des($person);
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
        } elseif ($debit == 'error-date') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng chọn lại ngày!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } elseif ($debit == 'error-debittype') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng chọn debit type!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } elseif ($debit == 'error-custno') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng chọn Khách Hàng!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            $debit_d = StatisticPayment::debitNote_D($type, $jobno,  $fromdate, $todate, $debittype);
            switch ($type) {
                case 'job':
                    foreach ($debit_d as $item) {
                        $total_amt += $item->QUANTITY * ($item->PRICE + $item->TAX_AMT);
                        $dor_no = $item->DOR_NO;
                    }
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
                        'bank' => $bank
                    ]);
                    break;
                case 'customer':
                    return view('print\payment\debit-note\customer', [
                        'debit' => $debit,
                        'debit_d' => $debit_d,
                        'company' => $company,
                        'person' => $person,
                        'phone' => $phone,
                        'bank' => $bank,
                        'customer' => $customer,
                    ]);
                    break;
                case  'debit_date':
                    return view('print\payment\debit-note\debit-date', [
                        'debit' => $debit,
                        'debit_d' => $debit_d,
                        'fromdate' => $fromdate,
                        'todate' => $todate,
                    ]);
                    break;
                default:
                    break;
            }
        }
    }
    //2.1 xuất excel phiếu yêu cầu thanh toán
    public function exportDebitNote($type = 'jobno', $jobno,  $custno, $fromdate, $todate, $debittype, $person, $phone, $bank = 'ACB')
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');

        $bank_no = ($bank == 'undefined' || $bank == 'null' || $bank == null) ? 'ACB' : $bank;
        $today = date("Ymd");
        $total_amt = 0;
        $dor_no = '';

        $fromdate = $fromdate != 'null' ? $fromdate : '19000101';
        $todate = $todate != 'null' ? $todate : $today;
        $debit = StatisticPayment::debitNote($type, $jobno, $custno, $fromdate, $todate, $debittype, $person, $phone);
        $bank = Bank::des($bank_no);
        $customer = Customer::arrayCustomer($custno);
        $person = Personal::des($person);
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
        } elseif ($debit == 'error-date') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng chọn lại ngày!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } elseif ($debit == 'error-debittype') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng chọn debit type!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } elseif ($debit == 'error-custno') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng chọn Khách Hàng!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            $debit_d = StatisticPayment::debitNote_D($type, $jobno,  $fromdate, $todate, $debittype);
            switch ($type) {
                case 'job':
                    foreach ($debit_d as $item) {
                        $total_amt += $item->QUANTITY * ($item->PRICE + $item->TAX_AMT);
                        $dor_no = $item->DOR_NO;
                    }
                    ob_end_clean();
                    ob_start(); //At the very top of your program (first line)
                    return Excel::create($fromdate . '-'  . $todate . ') ' . '(' . date('dmY') . ')', function ($excel) use ($debit, $debit_d, $company, $person, $phone, $fromdate, $todate, $total_amt, $dor_no, $bank) {
                        $excel->sheet('Debit Note', function ($sheet) use ($debit, $debit_d, $company, $person, $phone, $fromdate, $todate, $total_amt, $dor_no, $bank) {
                            $sheet->loadView('print\payment\debit-note\export-job', [
                                'debit' => $debit,
                                'debit_d' => $debit_d,
                                'company' => $company,
                                'person' => $person,
                                'phone' => $phone,
                                'fromdate' => $fromdate,
                                'todate' => $todate,
                                'total_amt' => $total_amt,
                                'dor_no' => $dor_no,
                                'bank' => $bank
                            ]);
                            $sheet->setOrientation('landscape');
                        });
                    })->download('xlsx');
                    ob_flush();
                    break;
                case 'customer':
                    ob_end_clean();
                    ob_start(); //At the very top of your program (first line)
                    return Excel::create('Thống kê debit note-customer', function ($excel) use ($debit, $debit_d) {
                        $excel->sheet('Debit Note', function ($sheet) use ($debit, $debit_d) {
                            $sheet->loadView('print\payment\debit-note\export-customer', [
                                'debit' => $debit,
                                'debit_d' => $debit_d,
                            ]);
                            $sheet->setOrientation('landscape');
                        });
                    })->download('xlsx');
                    ob_flush();
                    break;
                case  'debit_date':
                    return view('print\payment\debit-note\debit-date', [
                        'debit' => $debit,
                        'debit_d' => $debit_d,
                        'fromdate' => $fromdate,
                        'todate' => $todate,
                    ]);
                    break;
                default:
                    break;
            }
        }
    }
    //5. thống kê số job trong tháng
    public function jobMonthly($type = 'job_start', $custno, $fromdate, $todate)
    {
        $title_vn = 'THỐNG KẾ JOB';
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $fromdate = $fromdate != 'null' ? $fromdate : '19000101';
        $todate = $todate != 'null' ? $todate : $today;
        $data = StatisticPayment::jobMonthly($type, $custno, $fromdate, $todate);

        if ($data == 'error-date') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng chọn lại ngày!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } elseif ($data == 'error-custno') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng chọn Khách Hàng!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
        // dd($data);
        if ($data) {
            return view('print\payment\job-monthly\index', [
                'data' => $data,
                'fromdate' => $fromdate,
                'todate' => $todate,
                'type' => $type,
                'title_vn' => $title_vn,
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
    //8. thống kê phiếu thu
    public function receipt($receiptno)
    {
        $title_vn = 'PHIẾU THU';
        $receipt = StatisticPayment::receipt($receiptno);
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

    //test
    public function test()
    {
        return view('print\test', []);
    }
}

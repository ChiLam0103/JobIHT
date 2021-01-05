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
use Illuminate\Http\Request;


class StatisticPaymentController extends Controller
{
    //1.1 phieu chi tam ung new
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
    //1.2 thống kê phiếu bù/trả
    public function postReplenishmentWithdrawalPayment(Request $request)
    {
        $lender = StatisticPayment::postReplenishmentWithdrawalPayment($request->advanceno);

        if ($lender) {
            return view('print\payment\advance\post-replenishment-withdrawal-payment', [
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
    //1.2.1 excel thống kê phiếu bù/trả
    public function postExportReplenishmentWithdrawalPayment(Request $request)
    {
        $lender = StatisticPayment::postReplenishmentWithdrawalPayment($request->advanceno);
        if ($lender) {
            $filename = 'thong-ke-bu-tra' . '(' . date('YmdHis') . ')';
            Excel::create($filename, function ($excel) use ($lender) {
                $excel->sheet('Thong ke Bu-Tra', function ($sheet) use ($lender) {
                    $sheet->loadView('print\payment\advance\post-export-replenishment-withdrawal-payment', [
                        'lender' => $lender,
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
                    'message' => 'Vui lòng chọn số phiếu!'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    //2 phiếu yêu cầu thanh toán
    public function postDebitNote(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $bank_no = ($request->bankno == 'undefined' || $request->bankno == 'null' || $request->bankno == null) ? 'ACB' : $request->bankno;
        $total_amt = 0;
        $debit = StatisticPayment::postDebitNote($request);
        $bank = Bank::des($bank_no);
        $person = Personal::des($request->person);
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
            switch ($request->type) {
                case 'job':
                    return view('print\payment\debit-note\post-job', [
                        'debit' => $debit,
                        'company' => $company,
                        'person' => $person,
                        'phone' => $request->phone,
                        'total_amt' => $total_amt,
                        'bank' => $bank
                    ]);
                    break;
                case 'customer':
                    $customer = Customer::postCustomer($request->custno);

                    return view('print\payment\debit-note\post-customer', [
                        'debit' => $debit,
                        'company' => $company,
                        'person' => $person,
                        'phone' => $request->phone,
                        'bank' => $bank,
                        'customer' => $customer,
                    ]);
                    break;
                case  'debit_date':

                    return view('print\payment\debit-note\post-debit-date', [
                        'debit' => $debit,
                        'fromdate' => $request->fromdate,
                        'todate' => $request->todate,
                    ]);
                    break;
                default:
                    break;
            }
        }
    }
    //2.1 xuất excel phiếu yêu cầu thanh toán
    public function postExportDebitNote(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $bank_no = ($request->bankno == 'undefined' || $request->bankno == 'null' || $request->bankno == null) ? 'ACB' : $request->bankno;
        $total_amt = 0;
        $dor_no = '';
        $debit = StatisticPayment::postDebitNote($request);
        $bank = Bank::des($bank_no);
        $phone = $request->phone;
        $person = Personal::des($request->person);
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
            switch ($request->type) {
                case 'job':
                    $filename = 'debit-note-job' . '(' . date('YmdHis') . ')';
                    Excel::create($filename, function ($excel) use ($debit, $company, $person, $phone, $bank) {
                        $excel->sheet('Debit Note', function ($sheet) use ($debit, $company, $person, $phone, $bank) {
                            $sheet->loadView('print\payment\debit-note\post-export-job', [
                                'debit' => $debit,
                                'company' => $company,
                                'person' => $person,
                                'phone' => $phone,
                                'bank' => $bank
                            ]);
                            $sheet->setOrientation('landscape')->setAutoSize(true);
                        });
                    })->store('xlsx');
                    return response()->json([
                        'url' => 'https://job-api.ihtvn.com/storage/exports/' . $filename . '.xlsx',
                    ]);
                    break;
                case 'customer':
                    $filename = 'debit-note-customer' . '(' . date('YmdHis') . ')';
                    Excel::create($filename, function ($excel) use ($debit) {
                        $excel->sheet('Debit Note', function ($sheet) use ($debit) {
                            $sheet->loadView('print\payment\debit-note\post-export-customer', [
                                'debit' => $debit,
                            ]);
                            $sheet->setOrientation('landscape')->setAutoSize(true);
                        });
                    })->store('xlsx');
                    return response()->json([
                        'url' => 'https://job-api.ihtvn.com/storage/exports/' . $filename . '.xlsx',
                    ]);
                case  'debit_date':
                    $today = date("Ymd");
                    $fromdate = $request->fromdate != 'null' ? $request->fromdate : '19000101';
                    $todate = $request->todate != 'null' ? $request->todate : $today;
                    $filename = 'debit-note-date' . '(' . date('YmdHis') . ')';
                    Excel::create($filename, function ($excel) use ($debit, $fromdate, $todate) {
                        $excel->sheet('Debit Note', function ($sheet) use ($debit, $fromdate, $todate) {
                            $sheet->loadView('print\payment\debit-note\post-export-date', [
                                'debit' => $debit,
                                'fromdate' => $fromdate,
                                'todate' => $todate
                            ]);
                            $sheet->setOrientation('landscape')->setAutoSize(true);
                        });
                    })->store('xlsx');
                    return response()->json([
                        'url' => 'https://job-api.ihtvn.com/storage/exports/' . $filename . '.xlsx',
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
        $title_vn = '';
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $fromdate = $fromdate != 'null' ? $fromdate : '19000101';
        $todate = $todate != 'null' ? $todate : $today;
        $data = StatisticPayment::jobMonthly($type, $custno, $fromdate, $todate);
        switch ($type) {
            case 'job_start':
                $title_vn = 'THỐNG KẾ JOB';
                break;
            case 'job_order':
                $title_vn = 'THỐNG KẾ JOB ORDER';
                break;
            case  'debit_note':
                $title_vn = 'THỐNG KẾ DEBIT NOTE';
                break;
            default:
                break;
        }
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
    //5.1 xuất excel thống kê số job trong tháng
    public function postExportJobMonthly(Request $request)
    {
        $title_vn = '';
        $type = $request->type;
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $fromdate = $request->fromdate != 'null' ? $request->fromdate : '19000101';
        $todate = $request->todate != 'null' ? $request->todate : $today;
        $data = StatisticPayment::jobMonthly($request->type, $request->custno, $request->fromdate, $request->todate);
        switch ($type) {
            case 'job_start':
                $title_vn = 'THỐNG KẾ JOB';
                break;
            case 'job_order':
                $title_vn = 'THỐNG KẾ JOB ORDER';
                break;
            case  'debit_note':
                $title_vn = 'THỐNG KẾ DEBIT NOTE';
                break;
            default:
                break;
        }
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
        } else {
            $filename = 'job-monthly' . '(' . date('YmdHis') . ')';
            Excel::create($filename, function ($excel) use ($data, $title_vn, $type, $fromdate, $todate) {
                $excel->sheet('Debit Note', function ($sheet) use ($data, $title_vn, $type, $fromdate, $todate) {
                    $sheet->loadView('print\payment\job-monthly\post-export', [
                        'data' => $data,
                        'title_vn' => $title_vn,
                        'fromdate' => $fromdate,
                        'todate' => $todate,
                        'type' => $type
                    ]);
                    $sheet->setOrientation('landscape')->setAutoSize(true);
                });
            })->store('xlsx');
            return response()->json([
                'url' => 'https://job-api.ihtvn.com/storage/exports/' . $filename . '.xlsx',
            ]);
        }
    }
    //8. thống kê phiếu thu
    public function receipt($type, $receiptno)
    {
        $title_vn = 'PHIẾU THU';
        $receipt = StatisticPayment::receipt($type, $receiptno);

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
                    'message' => 'Đã có lỗi xảy ra, vui lòng kiểm tra lại!'
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

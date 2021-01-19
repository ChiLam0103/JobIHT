<?php

namespace App\Http\Controllers\Api\v1\Exports;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Models\Statistic\StatisticPayment;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Company;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\Personal;
use Illuminate\Http\Request;


class PaymentController extends Controller
{

    //1.2.1 excel thống kê phiếu bù/trả
    public function postExportReplenishmentWithdrawalPayment(Request $request)
    {
        $lender = StatisticPayment::postReplenishmentWithdrawalPayment($request->advanceno);
        if ($lender) {
            $filename = 'thong-ke-bu-tra' . '(' . date('YmdHis') . ')';
            Excel::create($filename, function ($excel) use ($lender) {
                $excel->sheet('Thong ke Bu-Tra', function ($sheet) use ($lender) {
                    $sheet->loadView('export\payment\advance\post-export-replenishment-withdrawal-payment', [
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
    //2.1 export-phiếu yêu cầu thanh toán
    public function postExportDebitNote(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $bank_no = ($request->bankno == 'undefined' || $request->bankno == 'null' || $request->bankno == null) ? 'ACB' : $request->bankno;
        $debit = StatisticPayment::postDebitNote($request);
        $bank = Bank::des($bank_no);
        $phone = $request->phone;
        $person = Personal::des($request->person);
        $company = Company::des('IHT');
        $customer = Customer::postCustomer($request->custno);
        switch ($debit) {
            case 'error-job-empty':
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Nhập vào số job để xem dữ liệu!'
                    ],
                    Response::HTTP_BAD_REQUEST
                );
                break;
            case 'error-person-empty':
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Nhập vào thông tin người liên lạc!'
                    ],
                    Response::HTTP_BAD_REQUEST
                );
                break;
            case 'error-phone':
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Nhập vào số điện thoại người liên lạc!'
                    ],
                    Response::HTTP_BAD_REQUEST
                );
                break;
            case 'error-date':
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Vui lòng chọn lại ngày!'
                    ],
                    Response::HTTP_BAD_REQUEST
                );
                break;
            case 'error-debittype':
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Vui lòng chọn debit type'
                    ],
                    Response::HTTP_BAD_REQUEST
                );
                break;
            case 'error-custno':
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Vui lòng chọn Khách Hàng!'
                    ],
                    Response::HTTP_BAD_REQUEST
                );
                break;
            default:
                switch ($request->type) {
                    case 'job':
                        $filename = 'debit-note-job' . '(' . date('YmdHis') . ')';
                        Excel::create($filename, function ($excel) use ($debit, $company, $person, $phone, $bank) {
                            $excel->sheet('Debit Note', function ($sheet) use ($debit, $company, $person, $phone, $bank) {
                                $sheet->loadView('export\payment\debit-note\post-export-job', [
                                    'debit' => $debit,
                                    'company' => $company,
                                    'person' => $person,
                                    'phone' => $phone,
                                    'bank' => $bank
                                ]);
                                $sheet->setOrientation('landscape');
                            });
                        })->store('xlsx');
                        return response()->json([
                            'url' => 'https://job-api.ihtvn.com/storage/exports/' . $filename . '.xlsx',
                        ]);
                        break;
                    case 'customer':
                        $filename = 'debit-note-customer-' . trim($customer->CUST_NO) . '(' . date('YmdHis') . ')';
                        Excel::create($filename, function ($excel) use ($debit) {
                            $excel->sheet('Debit Note', function ($sheet) use ($debit) {
                                $sheet->loadView('export\payment\debit-note\post-export-customer', [
                                    'debit' => $debit,
                                ]);
                                $sheet->setOrientation('landscape');
                            });
                        })->store('xlsx');
                        return response()->json([
                            'url' => 'https://job-api.ihtvn.com/storage/exports/' . $filename . '.xlsx',
                        ]);
                        break;
                    case 'customer_new':
                        $filename = 'debit-note-customer-new-' . trim($customer->CUST_NO) . '(' . date('YmdHis') . ')';
                        Excel::create($filename, function ($excel) use ($debit, $company, $person, $phone, $bank, $customer) {
                            $excel->sheet('Debit Note', function ($sheet) use ($debit, $company, $person, $phone, $bank, $customer) {
                                $sheet->loadView('export\payment\debit-note\post-export-customer-new', [
                                    'debit' => $debit,
                                    'company' => $company,
                                    'person' => $person,
                                    'phone' => $phone,
                                    'bank' => $bank,
                                    'customer' => $customer
                                ]);
                                $sheet->setOrientation('landscape');
                            });
                        })->store('xlsx');
                        return response()->json([
                            'url' => 'https://job-api.ihtvn.com/storage/exports/' . $filename . '.xlsx',
                        ]);
                        break;
                    case  'debit_date':
                        $today = date("Ymd");
                        $fromdate = $request->fromdate != 'null' ? $request->fromdate : '19000101';
                        $todate = $request->todate != 'null' ? $request->todate : $today;
                        $filename = 'debit-note-date' . '(' . date('YmdHis') . ')';
                        $debittype = ($request->debittype == 'undefined' || $request->debittype == 'null' || $request->debittype == null) ? 'all' : $request->debittype;
                        Excel::create($filename, function ($excel) use ($debit, $fromdate, $todate, $debittype) {
                            $excel->sheet('Debit Note', function ($sheet) use ($debit, $fromdate, $todate, $debittype) {
                                $sheet->loadView('export\payment\debit-note\post-export-date', [
                                    'debit' => $debit,
                                    'fromdate' => $fromdate,
                                    'todate' => $todate,
                                    'debittype' => $debittype,
                                ]);
                                $sheet->setOrientation('landscape');
                            });
                        })->store('xlsx');
                        return response()->json([
                            'url' => 'https://job-api.ihtvn.com/storage/exports/' . $filename . '.xlsx',
                        ]);
                        break;
                    default:
                        break;
                }
                break;
        }
    }
    //4. báo biểu lợi nhuận
    public function profit(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $fromdate = $request->fromdate != 'null' ? $request->fromdate : '19000101';
        $todate = $request->todate != 'null' ? $request->todate : $today;
        $data = StatisticPayment::profit($request->type, $request->jobno, $request->custno, $fromdate, $todate);

        if ($data == 'error-date') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng chọn lại ngày!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
        if ($data) {
            $filename = 'profit' . '(' . date('YmdHis') . ')';
            // $thanh_toan = $data['thanh_toan'];
            // $chi_phi = $data['chi_phi'];
            Excel::create($filename, function ($excel) use ($data, $fromdate, $todate) {
                $excel->sheet('Debit Note', function ($sheet) use ($data, $fromdate, $todate) {
                    $sheet->loadView('export\payment\profit\index', [
                        'thanh_toan' => $data,
                        // 'chi_phi' => $chi_phi,
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
                    'message' => 'Vui lòng kiểm tra lại!'
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
                    $sheet->loadView('export\payment\job-monthly\post-export', [
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

    //6.1 xuất excel thống kê thanh toan cua khach hang
    public function postExportPaymentCustomers(Request $request)
    {
        $title_vn = '';
        $type = $request->type;
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $fromdate = $request->fromdate != 'null' ? $request->fromdate : '19000101';
        $todate = $request->todate != 'null' ? $request->todate : $today;
        $data = StatisticPayment::paymentCustomers($request->type, $request->custno, $request->fromdate, $request->todate);
        switch ($type) {
            case 'all':
                $title_vn = 'THỐNG KẾ SỐ JOB ĐÃ LÀM DEBIT NOTE';
                break;
            case 'unpaid':
                $title_vn = 'THỐNG KẾ SỐ DEBIT NOTE CHƯA THANH TOÁN';
                break;
            case  'paid':
                $title_vn = 'THỐNG KẾ DEBIT NOTE ĐÃ THANH TOÁN';
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

            $filename = 'payment-customers' . '(' . date('YmdHis') . ')';
            Excel::create($filename, function ($excel) use ($data, $title_vn, $type, $fromdate, $todate) {
                $excel->sheet('Debit Note', function ($sheet) use ($data, $title_vn, $type, $fromdate, $todate) {
                    $sheet->loadView('export\payment\payment-customers\post-export', [
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

    //7. thong ke job order
    public function postExportjobOrder(Request $request)
    {
        $title_vn = '';
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $fromdate = $request->fromdate != 'null' ? $request->fromdate : '19000101';
        $todate = $request->todate != 'null' ? $request->todate : $today;
        $data = StatisticPayment::jobOrder($request->type, $request->custno, $request->person, $fromdate, $todate);
        $type = $request->type;
        switch ($type) {
            case 'truck_fee':
                $title_vn = 'BÁO BIỂU THỐNG KÊ TRUCKING FEE';
                $filename = 'statistics-trucking-fee' . '(' . date('YmdHis') . ')';
                break;
            case 'have_not_debit_note':
                $title_vn = 'BÁO BIỂU THỐNG KÊ JOB ORDER CHƯA MỞ DEBIT NOTE';
                $filename = 'statistics-have-not-debit-note' . '(' . date('YmdHis') . ')';
                break;
                break;
            case  'unpaid_cont':
                $title_vn = 'BÁO BIỂU THỐNG KÊ CƯỢC TÀU (CHƯA DUYỆT)';
                $filename = 'statistics-unpaid-cont' . '(' . date('YmdHis') . ')';
                break;
            case  'paid_cont':
                $title_vn = 'BÁO BIỂU THỐNG KÊ CƯỢC TÀU (ĐÃ DUYỆT)';
                $filename = 'statistics-paid-cont' . '(' . date('YmdHis') . ')';
                break;
            default:
                break;
        }
        if ($data) {
            Excel::create($filename, function ($excel) use ($data, $title_vn, $type, $fromdate, $todate) {
                $excel->sheet('statistics', function ($sheet) use ($data, $title_vn, $type, $fromdate, $todate) {
                    $sheet->loadView('export\payment\job-order\index', [
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

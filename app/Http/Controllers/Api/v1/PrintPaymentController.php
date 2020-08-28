<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use App\Models\PrintPayment;


class PrintPaymentController extends Controller
{
    //1 in phieu theo doi
    public function lender_AdvancePayment($fromadvance, $toadvance)
    {
        $data = PrintPayment::lender_AdvancePayment($fromadvance, $toadvance);
        if ($data) {
            return view('print\payment\advance-payment\index', [
                'data' => $data,
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

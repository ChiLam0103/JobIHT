<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Prints;
use App\Models\PayType;


class PrintController extends Controller
{

    public function jobStart($id)
    {
        $data = Prints::jobStart($id);
        if ($data) {
            return view('print\job-start')->with('data', $data);
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
    public function jobOrder($id)
    {
        $order_m = Prints::jobOrder($id);
        $order_d = Prints::jobOrder_D($id);
        $pay_type=PayType::listPayType_JobNo($id);
        $sum_port=0;
        $sum_kcn=0;
        $total_port=0;
        $total_kcn=0;
        if ($order_m) {
            return view('print\job-order', [
                'data' => $order_m,
                'order_d' => $order_d,
                'pay_type' => $pay_type,
                'sum_port'=>$sum_port,
                'sum_kcn'=>$sum_kcn,
                'total_port'=>$total_port,
                'total_kcn'=>$total_kcn
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
    public function jobOrder_Date($todate,$fromdate)
    {
        $data = Prints::jobOrder_Date($todate,$fromdate);
        if ($data) {
            return view('print\job-start')->with('data', $data);
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
    public function refund($type,$id,$jobno,$todate,$fromdate)
    {
        $data = Prints::refund($type,$id,$jobno,$todate,$fromdate);
        if ($data) {
            return view('print\refund')->with('data', $data);
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

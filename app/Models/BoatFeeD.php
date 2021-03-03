<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BoatFeeD extends Model
{
    public static function desMonth($type, $value)
    {
        $data = DB::table(config('constants.BOAT_FEE_D_TABLE'))
            ->where('BRANCH_ID', 'IHTVN1')
            ->where('BOAT_FEE_MONTH', $value)
            ->where('FEE_TYPE', $type)
            ->get();
        return $data;
    }
    public static function des($month, $jobno)
    {
        $data = DB::table('BOAT_FEE_D')
            ->where('BOAT_FEE_MONTH', $month)
            ->where('JOB_NO', $jobno)
            ->first();
        return $data;
    }
    public static function edit($request)
    {
        DB::table(config('constants.BOAT_FEE_D_TABLE'))
            ->where('BOAT_FEE_MONTH', $request->BOAT_FEE_MONTH)
            ->where('JOB_NO', $request->JOB_NO)
            ->update([
                'BOAT_LEAVE_DATE' =>$request->BOAT_LEAVE_DATE =="undefined" ? null : date("Ymd", strtotime($request->BOAT_LEAVE_DATE )),
                'PAY_DATE' => date("Ymd", strtotime($request->PAY_DATE)),
                'PAY_NOTE' => $request->PAY_NOTE,
                'VND_FEE' => $request->VND_FEE,
                'USD_FEE' => $request->USD_FEE,
                'PAID_VND_FEE' => $request->PAID_VND_FEE,
                'PAID_USD_FEE' => $request->PAID_USD_FEE,
                'REAL_PAY_DATE' =>$request->REAL_PAY_DATE =="undefined" ? null : date("Ymd", strtotime($request->REAL_PAY_DATE)),
                'CUST_NO' => $request->CUST_NO,
                'ORDER_FROM' => $request->ORDER_FROM,
                'ORDER_TO' => $request->ORDER_TO,
            ]);
        $data = BoatFeeD::des($request->BOAT_FEE_MONTH, $request->JOB_NO);
        return $data;
    }
}

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
    public static function des($fee, $jobno)
    {
        $data = DB::table('BOAT_FEE_D')
            ->where('FEE_TYPE', $fee)
            ->where('JOB_NO', $jobno)
            ->first();
        return $data;
    }
    public static function edit($request)
    {
        try{
            $query =  DB::table('BOAT_FEE_D')
            // ->where('BOAT_FEE_MONTH', $request->BOAT_FEE_MONTH)
            ->where('FEE_TYPE', $request->FEE_TYPE)
            ->where('JOB_NO', $request->JOB_NO)
            ->update([
                'BOAT_LEAVE_DATE' => $request->BOAT_LEAVE_DATE == "1970-01-01" ? null : date("Ymd", strtotime($request->BOAT_LEAVE_DATE)),
                'PAY_DATE' => date("Ymd", strtotime($request->PAY_DATE)),
                'PAY_NOTE' => $request->PAY_NOTE,
                'VND_FEE' => ($request->VND_FEE == null) || ($request->VND_FEE == 'null') || ($request->VND_FEE == 'undefined') ? 0 : $request->VND_FEE,
                'USD_FEE' => ($request->USD_FEE == null) || ($request->USD_FEE == 'null') || ($request->USD_FEE == 'undefined') ? 0.00 :  $request->USD_FEE,
                // 'PAID_VND_FEE' => $request->PAID_VND_FEE == null ? 0 :  $request->PAID_VND_FEE,
                // 'PAID_USD_FEE' => $request->PAID_USD_FEE == null ? 0 : $request->PAID_USD_FEE,
                'REAL_PAY_DATE' => $request->REAL_PAY_DATE == "1970-01-01" ? null : date("Ymd", strtotime($request->REAL_PAY_DATE)),
                'CUST_NO' => $request->CUST_NO,
                'ORDER_FROM' => $request->ORDER_FROM,
                'ORDER_TO' => $request->ORDER_TO,
            ]);
        $data = BoatFeeD::des($request->FEE_TYPE, $request->JOB_NO);
        return $data;
        }
        catch(\Exception $e){
            return $e;
        }
    }
}

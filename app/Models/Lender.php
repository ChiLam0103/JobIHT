<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lender extends Model
{
    // C,T,U
    public static function list()
    {
        $data = DB::table(config('constants.LENDER_TABLE'))->orderBy('LENDER_NO', 'desc')->take(100)->get();
        return $data;
    }
    public static function des($id)
    {
        $data = DB::table(config('constants.LENDER_TABLE'))
            ->where('LENDER_NO', $id)->first();
        return $data;
    }
    public static function generateLenderNo()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $countRecordToday = DB::table(config('constants.LENDER_TABLE'))->where('LENDER_DATE', date("Ymd"))->count();
        $countRecordToday = (int) $countRecordToday + 1;
        do {
            $lender_no = sprintf("%s%'.03d", date('ymd'), $countRecordToday);
            $countRecordToday++;
        } while (DB::table(config('constants.LENDER_TABLE'))->where('LENDER_NO', $lender_no)->first());
        return $lender_no;
    }
    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $lender_no = Lender::generateLenderNo();
            DB::table(config('constants.LENDER_TABLE'))
                ->insert(
                    [
                        'LENDER_NO' => $lender_no,
                        "LENDER_DATE" => date("Ymd"),
                        "LENDER_TYPE" => $request['LENDER_TYPE'],
                        "PNL_NO" => $request['PNL_NO'],
                        "PNL_NAME" => $request['PNL_NAME'],
                        "DOR_NO" => $request['DOR_NO'],
                        "TOTAL_AMT" => $request['TOTAL_AMT'],
                        "LEND_REASON" => $request['LEND_REASON'],
                        "JOB_NO" => $request['JOB_NO'],
                        "CUST_NO" => $request['CUST_NO'],
                        "ORDER_FROM" => $request['ORDER_FROM'],
                        "ORDER_TO" => $request['ORDER_TO'],
                        "CONTAINER_QTY" => $request['CONTAINER_QTY'],
                        "BRANCH_ID" => $request['BRANCH_ID'],
                        "INPUT_USER" => $request['INPUT_USER'],
                        "INPUT_DT" => date("YmdHis")
                    ]
                );
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function edit($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table(config('constants.LENDER_TABLE'))
            ->where('LENDER_NO',$request['LENDER_NO'])
                ->update(
                    [
                        "LENDER_DATE" => date("Ymd"),
                        "LENDER_TYPE" => $request['LENDER_TYPE'],
                        "PNL_NO" => $request['PNL_NO'],
                        "PNL_NAME" => $request['PNL_NAME'],
                        "DOR_NO" => $request['DOR_NO'],
                        "TOTAL_AMT" => $request['TOTAL_AMT'],
                        "LEND_REASON" => $request['LEND_REASON'],
                        "JOB_NO" => $request['JOB_NO'],
                        "CUST_NO" => $request['CUST_NO'],
                        "ORDER_FROM" => $request['ORDER_FROM'],
                        "ORDER_TO" => $request['ORDER_TO'],
                        "CONTAINER_QTY" => $request['CONTAINER_QTY'],
                        'MODIFY_USER' =>  $request['MODIFY_USER'],
                        'MODIFY_DT' =>  date("YmdHis"),
                    ]
                );
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function remove($request)
    {
        try {
            DB::table(config('constants.LENDER_TABLE'))
                ->where('LENDER_NO', $request['LENDER_NO'])
                ->delete();
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
}

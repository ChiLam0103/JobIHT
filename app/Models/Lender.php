<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lender extends Model
{
    // C:chi truc tiep,T: chi tam ung,U:phieu tam ung
    public static function list()
    {
        $data = DB::table('LENDER as l')
            ->rightJoin('LENDER_TYPE as lt', 'l.LENDER_TYPE', 'lt.LENDER_TYPE')
            ->select('lt.LENDER_NAME', 'l.*')
            ->where('l.BRANCH_ID', 'IHTVN1')
            ->orderBy('l.LENDER_NO', 'desc')
            ->take(9000)->get();
        return $data;
    }
    public static function listAdvance()
    {
        $data = DB::table('LENDER as l')
            ->select( 'l.LENDER_NO')
            ->where('l.BRANCH_ID', 'IHTVN1')
            ->where('l.LENDER_TYPE', 'U')
            ->orderBy('l.LENDER_NO', 'desc')
            ->take(9000)->get();
        return $data;
    }
    public static function search($type, $value)
    {
        //type=1 (advance no), 2.advance person, 3.job no
        $a = DB::table('LENDER as l');

        if ($type == '6') { //advance no
            $a->where('l.LENDER_NO', 'LIKE', '%' . $value . '%');
        } elseif ($type == '7') { //advance person
            $a->where('l.PNL_NAME', 'LIKE', '%' . $value . '%');
        } elseif ($type == '8') { //job no
            $a->where('l.JOB_NO', 'LIKE', '%' . $value . '%');
        }
        $data = $a->select('l.*')
            ->where('l.BRANCH_ID', 'IHTVN1')
            ->take(9000)
            ->get();
        return $data;
    }
    public static function des($id)
    {
        $data = DB::table('LENDER as l')
            ->join('LENDER_TYPE as lt', 'l.LENDER_TYPE', 'lt.LENDER_TYPE')
            ->leftJoin('CUSTOMER as c', 'l.CUST_NO', 'c.CUST_NO')
            ->select('lt.LENDER_NAME', 'c.CUST_NAME', 'l.*')
            ->where('l.LENDER_NO', $id)
            ->where('l.BRANCH_ID', 'IHTVN1')
            ->first();
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
                        "AMOUNT_1" => $request['AMOUNT_1'],
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
            $data = Lender::des($lender_no);
            return $data;
        } catch (\Exception $e) {
            return '201';
        }
    }
    public static function edit($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table(config('constants.LENDER_TABLE'))
                ->where('LENDER_NO', $request['LENDER_NO'])
                ->update(
                    [
                        "LENDER_DATE" => date("Ymd"),
                        "LENDER_TYPE" => $request['LENDER_TYPE'],
                        "PNL_NO" => $request['PNL_NO'],
                        "PNL_NAME" => $request['PNL_NAME'],
                        "DOR_NO" => $request['DOR_NO'],
                        "AMOUNT_2" => $request['AMOUNT_2'] ? $request['AMOUNT_2'] : "",
                        "AMOUNT_3" => $request['AMOUNT_3'] ? $request['AMOUNT_3'] : "",
                        "AMOUNT_4" => $request['AMOUNT_4'] ? $request['AMOUNT_4'] : "",
                        "AMOUNT_5" => $request['AMOUNT_5'] ? $request['AMOUNT_5'] : "",
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
            $data = Lender::des($request['LENDER_NO']);
            return $data;
        } catch (\Exception $e) {
            return '201';
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

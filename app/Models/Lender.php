<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lender extends Model
{
    // C:chi truc tiep,T: chi tam ung,U:phieu tam ung

    public static function query()
    {
        $query = DB::table('LENDER as l')
            ->rightJoin('LENDER_TYPE as lt', 'l.LENDER_TYPE', 'lt.LENDER_TYPE')
            ->where('l.BRANCH_ID', 'IHTVN1')
            ->orderBy('l.LENDER_NO', 'desc');
        return $query;
    }

    public static function list()
    {
        try {
            $take = 5000;
            $query =  Lender::query();
            $data =  $query
                ->take($take)
                ->select('lt.LENDER_NAME', 'l.*')
                ->get();
            return $data;
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    public static function listPage($page)
    {
        try {
            $take = 10;
            $skip = ($page - 1) * $take;
            $query =  Lender::query();
            $count = $query->count();
            $data =  $query->skip($skip)
                ->take($take)
                ->select('lt.LENDER_NAME', 'l.*')
                ->get();
            return ['total_page' => $count, 'list' => $data];
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    public static function listAdvance()
    {
        $data = DB::table('LENDER as l')
            ->select('l.LENDER_NO')
            ->where('l.BRANCH_ID', 'IHTVN1')
            ->where('l.LENDER_TYPE', 'U')
            ->orderBy('l.LENDER_NO', 'desc')
            ->take(5000)->get();
        return $data;
    }
    public static function search($type, $value)
    {
        //type=6 (advance no), 7.advance person, 8.job no
        $a = DB::table('LENDER as l')->leftJoin('LENDER_TYPE as lt', 'l.LENDER_TYPE', 'lt.LENDER_TYPE');

        if ($type == '6') { //advance no
            $a->where('l.LENDER_NO', 'LIKE', '%' . $value . '%');
        } elseif ($type == '7') { //advance person
            $a->where('l.PNL_NAME', 'LIKE', '%' . $value . '%');
        } elseif ($type == '8') { //job no
            $a->where('l.JOB_NO', 'LIKE', '%' . $value . '%');
        }
        $data = $a->select('lt.LENDER_NAME', 'l.*')
            ->where('l.BRANCH_ID', 'IHTVN1')
            ->take(5000)
            ->get();
        return $data;
    }
    public static function des($id)
    {
        $data = DB::table('LENDER as l')
            ->join('LENDER_TYPE as lt', 'l.LENDER_TYPE', 'lt.LENDER_TYPE')
            ->leftJoin('CUSTOMER as c', 'l.CUST_NO', 'c.CUST_NO')
            ->leftJoin('JOB_ORDER_D as jod', 'jod.JOB_NO', 'l.JOB_NO')
            ->select('lt.LENDER_NAME', 'c.CUST_NAME', 'l.LENDER_NO', 'l.LENDER_DATE', 'l.LENDER_TYPE', 'l.PNL_NO', 'l.PNL_NAME', 'l.DOR_NO', 'l.LEND_REASON', 'l.JOB_NO', 'l.CUST_NO', 'l.ORDER_FROM', 'l.ORDER_TO', 'l.CONTAINER_QTY', 'l.INPUT_USER', 'l.INPUT_DT', 'l.MODIFY_USER', 'l.MODIFY_DT', 'l.BRANCH_ID', 'l.DUYET_KT', 'l.NGAYDUYET')
            ->selectRaw('sum(jod.PORT_AMT) as  sum_PORT_AMT, sum(jod.INDUSTRY_ZONE_AMT) as sum_INDUSTRY_ZONE_AMT')
            ->groupBy('lt.LENDER_NAME', 'c.CUST_NAME', 'l.LENDER_NO', 'l.LENDER_DATE', 'l.LENDER_TYPE', 'l.PNL_NO', 'l.PNL_NAME', 'l.DOR_NO', 'l.LEND_REASON', 'l.JOB_NO', 'l.CUST_NO', 'l.ORDER_FROM', 'l.ORDER_TO', 'l.CONTAINER_QTY', 'l.INPUT_USER', 'l.INPUT_DT', 'l.MODIFY_USER', 'l.MODIFY_DT', 'l.BRANCH_ID', 'l.DUYET_KT', 'l.NGAYDUYET')
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
                        "PNL_NO" => ($request['PNL_NO'] != 'undefined' || $request['PNL_NO'] != 'null' || $request['PNL_NO'] != null)   ? $request['PNL_NO'] : '',
                        "PNL_NAME" => ($request['PNL_NAME'] != 'undefined' || $request['PNL_NAME'] != 'null' || $request['PNL_NAME'] != null) ? $request['PNL_NAME'] : '',
                        "DOR_NO" => ($request['DOR_NO'] != 'undefined' || $request['DOR_NO'] != 'null' || $request['DOR_NO'] != null) ? $request['DOR_NO'] : 'VND',
                        "LEND_REASON" => ($request['LEND_REASON'] != 'undefined' || $request['LEND_REASON'] != 'null' || $request['LEND_REASON'] != null) ? $request['LEND_REASON'] : '',
                        "JOB_NO" => ($request['JOB_NO'] != 'undefined' || $request['JOB_NO'] != 'null' || $request['JOB_NO'] != null) ? $request['JOB_NO'] : '',
                        "CUST_NO" => ($request['CUST_NO'] != 'undefined' || $request['CUST_NO'] != 'null' || $request['CUST_NO'] != null) ? $request['CUST_NO'] : '',
                        "ORDER_FROM" => ($request['ORDER_FROM'] != 'undefined' || $request['ORDER_FROM'] != 'null' || $request['ORDER_FROM'] != null) ? $request['ORDER_FROM'] : '',
                        "ORDER_TO" => ($request['ORDER_TO'] != 'undefined' || $request['ORDER_TO'] != 'null' || $request['ORDER_TO'] != null) ? $request['ORDER_TO'] : '',
                        "CONTAINER_QTY" => ($request['CONTAINER_QTY'] != 'undefined' || $request['CONTAINER_QTY'] != 'null' || $request['CONTAINER_QTY'] != null) ? $request['CONTAINER_QTY'] : '',
                        "BRANCH_ID" => ($request['BRANCH_ID'] != 'undefined' || $request['BRANCH_ID'] != 'null' || $request['BRANCH_ID'] != null) ? $request['BRANCH_ID'] : 'IHTVN1',
                        "INPUT_USER" => ($request['INPUT_USER'] != 'undefined' || $request['INPUT_USER'] != 'null' || $request['INPUT_USER'] != null) ? $request['INPUT_USER'] : '',
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
                        "PNL_NO" => ($request['PNL_NO'] != 'undefined' || $request['PNL_NO'] != 'null' || $request['PNL_NO'] != null)   ? $request['PNL_NO'] : '',
                        "PNL_NAME" => ($request['PNL_NAME'] != 'undefined' || $request['PNL_NAME'] != 'null' || $request['PNL_NAME'] != null) ? $request['PNL_NAME'] : '',
                        "DOR_NO" => ($request['DOR_NO'] != 'undefined' || $request['DOR_NO'] != 'null' || $request['DOR_NO'] != null) ? $request['DOR_NO'] : 'VND',
                        "LEND_REASON" => ($request['LEND_REASON'] != 'undefined' || $request['LEND_REASON'] != 'null' || $request['LEND_REASON'] != null) ? $request['LEND_REASON'] : '',
                        "JOB_NO" => ($request['JOB_NO'] != 'undefined' || $request['JOB_NO'] != 'null' || $request['JOB_NO'] != null) ? $request['JOB_NO'] : '',
                        "CUST_NO" => ($request['CUST_NO'] != 'undefined' || $request['CUST_NO'] != 'null' || $request['CUST_NO'] != null) ? $request['CUST_NO'] : '',
                        "ORDER_FROM" => ($request['ORDER_FROM'] != 'undefined' || $request['ORDER_FROM'] != 'null' || $request['ORDER_FROM'] != null) ? $request['ORDER_FROM'] : '',
                        "ORDER_TO" => ($request['ORDER_TO'] != 'undefined' || $request['ORDER_TO'] != 'null' || $request['ORDER_TO'] != null) ? $request['ORDER_TO'] : '',
                        "CONTAINER_QTY" => ($request['CONTAINER_QTY'] != 'undefined' || $request['CONTAINER_QTY'] != 'null' || $request['CONTAINER_QTY'] != null) ? $request['CONTAINER_QTY'] : '',
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

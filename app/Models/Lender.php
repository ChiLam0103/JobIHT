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
            ->leftJoin('LENDER_TYPE as lt', 'l.LENDER_TYPE', 'lt.LENDER_TYPE')
            ->where('l.BRANCH_ID', 'IHTVN1')
            ->where('l.LENDER_DATE','>=','20190101')
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
    public static function listTake($take)
    {
        try {
            $take = $take;
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
    public static function listJobNotCreated()
    {
        try {
            $take = 100;
            $data =  DB::table('JOB_START as js')
                ->leftJoin('LENDER as l', 'js.JOB_NO', 'l.JOB_NO')
                ->whereNull('l.JOB_NO')
                ->where('js.BRANCH_ID', 'IHTVN1')->select('lt.LENDER_NAME', 'l.*')
                ->take($take)
                ->orderByDesc('js.JOB_NO')
                ->select('js.JOB_NO')
                ->get();
            return $data;
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    public static function search($type, $value, $page)
    {

        $take = 10;
        $skip = ($page - 1) * $take;
        $query =  Lender::query();
        $query->leftJoin('PERSONAL as p', 'p.PNL_NO', 'l.PNL_NO')
            ->where('p.BRANCH_ID', 'IHTVN1');
        switch ($type) {
            case 'job_no':
                $query->where('l.JOB_NO', 'LIKE', '%' . $value . '%');
                break;
            case 'lender_no':
                $query->where('l.LENDER_NO', 'LIKE', '%' . $value . '%');
                break;
            case  'pnl_no':
                $query->where('p.PNL_NO', 'LIKE', '%' . $value . '%');
                break;
            case  'pnl_name':
                $query->where('p.PNL_NAME', 'LIKE', '%' . $value . '%');
                break;
            default:
                break;
        }
        $count = $query->count();
        $data =  $query->skip($skip)->take($take)->select('lt.LENDER_NAME', 'p.PNL_NAME as PNL_NAME2', 'l.*')->get();
        return ['total_page' => $count, 'list' => $data];
    }
    public static function des($id)
    {
        $query =  Lender::query();
        $data =  $query->leftJoin('CUSTOMER as c', 'l.CUST_NO', 'c.CUST_NO')
            ->leftJoin('JOB_ORDER_D as jod', 'jod.JOB_NO', 'l.JOB_NO')
            ->select('lt.LENDER_NAME', 'c.CUST_NAME', 'l.LENDER_NO', 'l.LENDER_DATE', 'l.LENDER_TYPE', 'l.PNL_NO', 'l.PNL_NAME', 'l.DOR_NO', 'l.LEND_REASON', 'l.JOB_NO', 'l.CUST_NO', 'l.ORDER_FROM', 'l.ORDER_TO', 'l.CONTAINER_QTY', 'l.INPUT_USER', 'l.INPUT_DT', 'l.MODIFY_USER', 'l.MODIFY_DT', 'l.BRANCH_ID', 'l.DUYET_KT', 'l.NGAYDUYET')
            ->selectRaw('sum(jod.PORT_AMT) as  sum_PORT_AMT, sum(jod.INDUSTRY_ZONE_AMT) as sum_INDUSTRY_ZONE_AMT')
            ->groupBy('lt.LENDER_NAME', 'c.CUST_NAME', 'l.LENDER_NO', 'l.LENDER_DATE', 'l.LENDER_TYPE', 'l.PNL_NO', 'l.PNL_NAME', 'l.DOR_NO', 'l.LEND_REASON', 'l.JOB_NO', 'l.CUST_NO', 'l.ORDER_FROM', 'l.ORDER_TO', 'l.CONTAINER_QTY', 'l.INPUT_USER', 'l.INPUT_DT', 'l.MODIFY_USER', 'l.MODIFY_DT', 'l.BRANCH_ID', 'l.DUYET_KT', 'l.NGAYDUYET')
            ->where('l.LENDER_NO', $id)
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

            if ($request['JOB_NO'] == 'undefined' || $request['JOB_NO'] == 'null' || $request['JOB_NO'] == null) {

                date_default_timezone_set('Asia/Ho_Chi_Minh');
                $lender_no = Lender::generateLenderNo();
                DB::table(config('constants.LENDER_TABLE'))
                    ->insert(
                        [
                            'LENDER_NO' => $lender_no,
                            "LENDER_DATE" => date("Ymd"),
                            "LENDER_TYPE" => $request['LENDER_TYPE'],
                            "PNL_NO" => ($request['PNL_NO'] == 'undefined' || $request['PNL_NO'] == 'null' || $request['PNL_NO'] == null) ? null : $request['PNL_NO'],
                            "PNL_NAME" => ($request['PNL_NAME'] == 'undefined' || $request['PNL_NAME'] == 'null' || $request['PNL_NAME'] == null) ? null : $request['PNL_NAME'],
                            "DOR_NO" => ($request['DOR_NO'] == 'undefined' || $request['DOR_NO'] == 'null' || $request['DOR_NO'] == null) ? 'VND' :  $request['DOR_NO'],
                            "LEND_REASON" => ($request['LEND_REASON'] == 'undefined' || $request['LEND_REASON'] == 'null' || $request['LEND_REASON'] == null) ? '' : $request['LEND_REASON'],
                            "JOB_NO" => ($request['JOB_NO'] == 'undefined' || $request['JOB_NO'] == 'null' || $request['JOB_NO'] == null) ?  null : $request['JOB_NO'],
                            "CUST_NO" => ($request['CUST_NO'] == 'undefined' || $request['CUST_NO'] == 'null' || $request['CUST_NO'] == null) ? '' : $request['CUST_NO'],
                            "ORDER_FROM" => ($request['ORDER_FROM'] == 'undefined' || $request['ORDER_FROM'] == 'null' || $request['ORDER_FROM'] == null) ? '' : $request['ORDER_FROM'],
                            "ORDER_TO" => ($request['ORDER_TO'] == 'undefined' || $request['ORDER_TO'] == 'null' || $request['ORDER_TO'] == null) ? '' : $request['ORDER_TO'],
                            "CONTAINER_QTY" => ($request['CONTAINER_QTY'] == 'undefined' || $request['CONTAINER_QTY'] == 'null' || $request['CONTAINER_QTY'] == null) ? '' : $request['CONTAINER_QTY'],
                            "QTY" => ($request['QTY'] == 'undefined' || $request['QTY'] == 'null' || $request['QTY'] == null) ? '' : $request['QTY'],
                            "BRANCH_ID" => ($request['BRANCH_ID'] == 'undefined' || $request['BRANCH_ID'] == 'null' || $request['BRANCH_ID'] == null) ?  'IHTVN1' : $request['BRANCH_ID'],
                            "INPUT_USER" => ($request['INPUT_USER'] == 'undefined' || $request['INPUT_USER'] == 'null' || $request['INPUT_USER'] == null) ? '' : $request['INPUT_USER'],
                            "INPUT_DT" => date("YmdHis")
                        ]
                    );
            } else {
                $exist = DB::table('LENDER')->where('JOB_NO', $request['JOB_NO'])->count();
                if ($exist == 0) {
                    date_default_timezone_set('Asia/Ho_Chi_Minh');
                    $lender_no = Lender::generateLenderNo();
                    DB::table(config('constants.LENDER_TABLE'))
                        ->insert(
                            [
                                'LENDER_NO' => $lender_no,
                                "LENDER_DATE" => date("Ymd"),
                                "LENDER_TYPE" => $request['LENDER_TYPE'],
                                "PNL_NO" => ($request['PNL_NO'] == 'undefined' || $request['PNL_NO'] == 'null' || $request['PNL_NO'] == null) ? null : $request['PNL_NO'],
                                "PNL_NAME" => ($request['PNL_NAME'] == 'undefined' || $request['PNL_NAME'] == 'null' || $request['PNL_NAME'] == null) ? null : $request['PNL_NAME'],
                                "DOR_NO" => ($request['DOR_NO'] == 'undefined' || $request['DOR_NO'] == 'null' || $request['DOR_NO'] == null) ? 'VND' :  $request['DOR_NO'],
                                "LEND_REASON" => ($request['LEND_REASON'] == 'undefined' || $request['LEND_REASON'] == 'null' || $request['LEND_REASON'] == null) ? '' : $request['LEND_REASON'],
                                "JOB_NO" => ($request['JOB_NO'] == 'undefined' || $request['JOB_NO'] == 'null' || $request['JOB_NO'] == null) ?  null : $request['JOB_NO'],
                                "CUST_NO" => ($request['CUST_NO'] == 'undefined' || $request['CUST_NO'] == 'null' || $request['CUST_NO'] == null) ? '' : $request['CUST_NO'],
                                "ORDER_FROM" => ($request['ORDER_FROM'] == 'undefined' || $request['ORDER_FROM'] == 'null' || $request['ORDER_FROM'] == null) ? '' : $request['ORDER_FROM'],
                                "ORDER_TO" => ($request['ORDER_TO'] == 'undefined' || $request['ORDER_TO'] == 'null' || $request['ORDER_TO'] == null) ? '' : $request['ORDER_TO'],
                                "CONTAINER_QTY" => ($request['CONTAINER_QTY'] == 'undefined' || $request['CONTAINER_QTY'] == 'null' || $request['CONTAINER_QTY'] == null) ? '' : $request['CONTAINER_QTY'],
                                "QTY" => ($request['QTY'] == 'undefined' || $request['QTY'] == 'null' || $request['QTY'] == null) ? '' : $request['QTY'],
                                "BRANCH_ID" => ($request['BRANCH_ID'] == 'undefined' || $request['BRANCH_ID'] == 'null' || $request['BRANCH_ID'] == null) ?  'IHTVN1' : $request['BRANCH_ID'],
                                "INPUT_USER" => ($request['INPUT_USER'] == 'undefined' || $request['INPUT_USER'] == 'null' || $request['INPUT_USER'] == null) ? '' : $request['INPUT_USER'],
                                "INPUT_DT" => date("YmdHis")
                            ]
                        );
                } else {
                    $data = '201';
                }
            }
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
                        "PNL_NO" => ($request['PNL_NO'] == 'undefined' || $request['PNL_NO'] == 'null' || $request['PNL_NO'] == null)   ? '' : $request['PNL_NO'],
                        "PNL_NAME" => ($request['PNL_NAME'] == 'undefined' || $request['PNL_NAME'] == 'null' || $request['PNL_NAME'] == null) ? '' : $request['PNL_NAME'],
                        "DOR_NO" => ($request['DOR_NO'] == 'undefined' || $request['DOR_NO'] == 'null' || $request['DOR_NO'] == null) ? 'VND' :  $request['DOR_NO'],
                        "LEND_REASON" => ($request['LEND_REASON'] == 'undefined' || $request['LEND_REASON'] == 'null' || $request['LEND_REASON'] == null) ? '' : $request['LEND_REASON'],
                        "JOB_NO" => ($request['JOB_NO'] == 'undefined' || $request['JOB_NO'] == 'null' || $request['JOB_NO'] == null) ?  null : $request['JOB_NO'],
                        "CUST_NO" => ($request['CUST_NO'] == 'undefined' || $request['CUST_NO'] == 'null' || $request['CUST_NO'] == null) ? '' : $request['CUST_NO'],
                        "ORDER_FROM" => ($request['ORDER_FROM'] == 'undefined' || $request['ORDER_FROM'] == 'null' || $request['ORDER_FROM'] == null) ? '' : $request['ORDER_FROM'],
                        "ORDER_TO" => ($request['ORDER_TO'] == 'undefined' || $request['ORDER_TO'] == 'null' || $request['ORDER_TO'] == null) ? '' : $request['ORDER_TO'],
                        "CONTAINER_QTY" => ($request['CONTAINER_QTY'] == 'undefined' || $request['CONTAINER_QTY'] == 'null' || $request['CONTAINER_QTY'] == null) ? '' : $request['CONTAINER_QTY'],
                        "QTY" => ($request['QTY'] == 'undefined' || $request['QTY'] == 'null' || $request['QTY'] == null) ? '' : $request['QTY'],
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JobD extends Model
{
    public static function getJob($id)
    {
        $data = DB::table('JOB_ORDER_M as jm')
            ->leftjoin('JOB_ORDER_D as jd', 'jm.JOB_NO', '=', 'jd.JOB_NO')
            ->leftJoin('PAY_TYPE as pt', 'jd.ORDER_TYPE', '=', 'pt.PAY_NO')
            ->where('jm.JOB_NO', $id)
            ->select('pt.PAY_NAME as ORDER_TYPE_NAME', 'jd.*')
            ->get();
        return $data;
    }
    public static function add($request)
    {
        try {
            $JOB_NO = $request->JOB_NO;
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            foreach ($request->data as $res) {
                DB::table(config('constants.JOB_D_TABLE'))
                    ->insert(
                        [
                            "JOB_NO" => $JOB_NO,
                            "ORDER_TYPE" => $res['ORDER_TYPE'],
                            "SER_NO" => $res['SER_NO'],
                            "DESCRIPTION" => $res['DESCRIPTION'],
                            "REV_TYPE" => $res['REV_TYPE'],
                            "INV_NO" => $res['INV_NO'],
                            "PORT_AMT" => $res['PORT_AMT'],
                            "INDUSTRY_ZONE_AMT" => $res['INDUSTRY_ZONE_AMT'],
                            "UNIT" => $res['UNIT'],
                            "QTY" => $res['QTY'],
                            "PRICE" => $res['PRICE'],
                            "TAX_NOTE" => $res['TAX_NOTE'],
                            "TAX_AMT" => $res['TAX_AMT'],
                            "NOTE" => $res['NOTE'],
                            "THANH_TOAN_MK" => $res['THANH_TOAN_MK'],
                            "BRANCH_ID" => $res['BRANCH_ID'],
                            "INPUT_USER" => $res['INPUT_USER'],
                            "INPUT_DT" => date("YmdHis")
                        ]
                    );
            }
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function edit($request)
    {
        try {
            $JOB_NO = $request->JOB_NO;
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            foreach ($request->data as $res) {
                DB::table(config('constants.JOB_D_TABLE'))
                ->where('JOB_NO', $JOB_NO)
                ->where('SER_NO', $res['SER_NO'])
                ->where('ORDER_TYPE', $res['ORDER_TYPE'])
                    ->update(
                        [
                            "DESCRIPTION" => $res['DESCRIPTION'],
                            "REV_TYPE" => $res['REV_TYPE'],
                            "INV_NO" => $res['INV_NO'],
                            "PORT_AMT" => $res['PORT_AMT'],
                            "INDUSTRY_ZONE_AMT" => $res['INDUSTRY_ZONE_AMT'],
                            "UNIT" => $res['UNIT'],
                            "QTY" => $res['QTY'],
                            "PRICE" => $res['PRICE'],
                            "TAX_NOTE" => $res['TAX_NOTE'],
                            "TAX_AMT" => $res['TAX_AMT'],
                            "NOTE" => $res['NOTE'],
                            "THANH_TOAN_MK" => $res['THANH_TOAN_MK'],
                            'MODIFY_USER' =>  $res['MODIFY_USER'],
                            'MODIFY_DT' =>  date("YmdHis"),
                        ]
                    );
            }
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function remove($request)
    {
        try {
            DB::table(config('constants.JOB_D_TABLE'))
                ->where('JOB_NO', $request['JOB_NO'])
                ->where('SER_NO', $request['SER_NO'])
                ->where('ORDER_TYPE', $request['ORDER_TYPE'])
                ->delete();
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
}

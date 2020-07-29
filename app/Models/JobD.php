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
    public static function generateSerNo($job_no, $order_type)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $count = DB::table(config('constants.JOB_D_TABLE'))
            ->where('JOB_NO', $job_no)
            ->where('ORDER_TYPE', $order_type)
            ->count();
        $count = (int) $count + 1;
        $data = sprintf("%'.02d", $count);
        return $data;
    }
    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            foreach ($request->data as $res) {
                $ser_no = JobD::generateSerNo($request->JOB_NO, $res['ORDER_TYPE']);
                DB::table(config('constants.JOB_D_TABLE'))
                    ->insert(
                        [
                            "JOB_NO" => $request->JOB_NO,
                            "ORDER_TYPE" => $res['ORDER_TYPE'] != 'undefined' ? $request['ORDER_TYPE'] : '',
                            "SER_NO" => $ser_no,
                            "DESCRIPTION" => $res['DESCRIPTION'] != 'undefined' ? $request['DESCRIPTION'] : '',
                            "REV_TYPE" => $res['REV_TYPE'] != 'undefined' ? $request['REV_TYPE'] : '',
                            "INV_NO" => $res['INV_NO'] != 'undefined' ? $request['INV_NO'] : '',
                            "PORT_AMT" => $res['PORT_AMT'] != 'undefined' ? $request['PORT_AMT'] : '',
                            "INDUSTRY_ZONE_AMT" => $res['INDUSTRY_ZONE_AMT'] != 'undefined' ? $request['INDUSTRY_ZONE_AMT'] : '',
                            "UNIT" => $res['UNIT'] != 'undefined' ? $request['UNIT'] : '',
                            "QTY" => $res['QTY'] != 'undefined' ? $request['QTY'] : '',
                            "PRICE" => $res['PRICE'] != 'undefined' ? $request['PRICE'] : '',
                            "TAX_NOTE" => $res['TAX_NOTE'] != 'undefined' ? $request['TAX_NOTE'] : '',
                            "TAX_AMT" => $res['TAX_AMT'] != 'undefined' ? $request['TAX_AMT'] : '',
                            "NOTE" => $res['NOTE'] != 'undefined' ? $request['NOTE'] : '',
                            "THANH_TOAN_MK" => $res['THANH_TOAN_MK'] != 'undefined' ? $request['THANH_TOAN_MK'] : '',
                            "BRANCH_ID" => $res['BRANCH_ID'] != 'undefined' ? $request['BRANCH_ID'] : 'IHTVN1',
                            "INPUT_USER" => $res['INPUT_USER'] != 'undefined' ? $request['INPUT_USER'] : '',
                            "INPUT_DT" => date("YmdHis")
                        ]
                    );
            }
            return '200';
        } catch (\Exception $e) {
            return '201';
        }
    }
    public static function edit($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $JOB_NO = $request->JOB_NO;
            JobD::remove($JOB_NO);
            foreach ($request->data as $res) {
                $ser_no = JobD::generateSerNo($JOB_NO, $res['ORDER_TYPE']);
                DB::table(config('constants.JOB_D_TABLE'))
                    ->insert(
                        [
                            "JOB_NO" => $JOB_NO,
                            "ORDER_TYPE" => $res['ORDER_TYPE'] != 'undefined' ? $request['ORDER_TYPE'] : '',
                            "SER_NO" => $ser_no,
                            "DESCRIPTION" => $res['DESCRIPTION'] != 'undefined' ? $request['DESCRIPTION'] : '',
                            "REV_TYPE" => $res['REV_TYPE'] != 'undefined' ? $request['REV_TYPE'] : '',
                            "INV_NO" => $res['INV_NO'] != 'undefined' ? $request['INV_NO'] : '',
                            "PORT_AMT" => $res['PORT_AMT'] != 'undefined' ? $request['PORT_AMT'] : '',
                            "INDUSTRY_ZONE_AMT" => $res['INDUSTRY_ZONE_AMT'] != 'undefined' ? $request['INDUSTRY_ZONE_AMT'] : '',
                            "UNIT" => $res['UNIT'] != 'undefined' ? $request['UNIT'] : '',
                            "QTY" => $res['QTY'] != 'undefined' ? $request['QTY'] : '',
                            "PRICE" => $res['PRICE'] != 'undefined' ? $request['PRICE'] : '',
                            "TAX_NOTE" => $res['TAX_NOTE'] != 'undefined' ? $request['TAX_NOTE'] : '',
                            "TAX_AMT" => $res['TAX_AMT'] != 'undefined' ? $request['TAX_AMT'] : '',
                            "NOTE" => $res['NOTE'] != 'undefined' ? $request['NOTE'] : '',
                            "THANH_TOAN_MK" => $res['THANH_TOAN_MK'] != 'undefined' ? $request['THANH_TOAN_MK'] : '',
                            "BRANCH_ID" => $res['BRANCH_ID'] != 'undefined' ? $request['BRANCH_ID'] : 'IHTVN1',
                            "INPUT_USER" => $res['INPUT_USER'] != 'undefined' ? $request['INPUT_USER'] : '',
                            "INPUT_DT" => date("YmdHis"),
                            'MODIFY_USER' =>  $res['MODIFY_USER'] != 'undefined' ? $request['MODIFY_USER'] : '',
                            'MODIFY_DT' =>  date("YmdHis"),
                            'CUST_NO4' => $res['CUST_NO4'] != 'undefined' ? $request['CUST_NO4'] : '',
                        ]
                    );
            }
            return '200';
        } catch (\Exception $e) {
            return '201';
        }
    }
    public static function change($request)
    {
        try {
            $count = DB::table(config('constants.JOB_D_TABLE'))
                ->where('JOB_NO',  $request['JOB_NO'])
                ->count();
            if ($count == 0) {
                $data = JobD::add($request);
            } else {
                $data = JobD::edit($request);
            }
            if ($data == '200') {
                $data = JobD::getJob($request['JOB_NO']);
            } else {
                $data = 'Lỗi vui lòng kiểm tra lại!!! ';
            }
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function remove($JOB_NO)
    {
        DB::table(config('constants.JOB_D_TABLE'))
            ->where('JOB_NO', $JOB_NO)->delete();
    }
}

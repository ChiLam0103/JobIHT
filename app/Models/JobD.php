<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Double;
use App\Models\LenderD;

class JobD extends Model
{
    public static function getJob($id, $type)
    {
        $a = "";
        $a = DB::table('JOB_ORDER_D as jd')
            ->leftJoin('PAY_TYPE as pt', 'jd.ORDER_TYPE', '=', 'pt.PAY_NO')
            ->where('jd.JOB_NO', $id)
            ->where('jd.BRANCH_ID', 'IHTVN1');
        if ($type == 'JOB_ORDER') {
            $data = $a->select(
                'pt.PAY_NAME as ORDER_TYPE_NAME',
                'jd.JOB_NO',
                'jd.ORDER_TYPE',
                'jd.SER_NO',
                'jd.DESCRIPTION',
                'jd.REV_TYPE',
                'jd.INV_NO',
                'jd.PORT_AMT',
                'jd.INDUSTRY_ZONE_AMT',
                'jd.NOTE',
                'jd.INPUT_USER',
                'jd.INPUT_DT',
                'jd.MODIFY_USER',
                'jd.MODIFY_DT',
                'jd.THANH_TOAN_MK'
            )
                ->selectRaw("(CASE WHEN (jd.THANH_TOAN_MK = 'Y') THEN 'Approved' ELSE 'Pending' END) as THANH_TOAN_TEXT")
                ->get();
        } else {
            $data = $a->select(
                'pt.PAY_NAME as ORDER_TYPE_NAME',
                'jd.ORDER_TYPE',
                'jd.SER_NO',
                'jd.DESCRIPTION',
                'jd.UNIT',
                'jd.QTY', //sl
                'jd.PRICE', //gia truoc thue
                'jd.TAX_NOTE', //thue %
                'jd.TAX_AMT', //tien thue
                'jd.NOTE',
                'jd.INPUT_USER',
                'jd.INPUT_DT',
                'jd.MODIFY_USER',
                'jd.MODIFY_DT',
                'jd.THANH_TOAN_MK'
            )
                ->selectRaw('(CASE WHEN (jd.QTY = 0) THEN (jd.PRICE + jd.TAX_AMT) ELSE (jd.PRICE + (jd.TAX_AMT/jd.QTY))  END) AS SAU_THUE')
                ->selectRaw('(CASE WHEN (jd.QTY = 0) THEN  (jd.PRICE + jd.TAX_AMT) ELSE ((jd.PRICE + (jd.TAX_AMT/jd.QTY))*jd.QTY) END) AS TONG_TIEN')
                ->selectRaw("(CASE WHEN (jd.THANH_TOAN_MK = 'Y') THEN 'Approved' ELSE 'Pending' END) as THANH_TOAN_TEXT")
                ->get();
        }
        return $data;
    }
    public static function getJobD($id)
    {
        return  DB::table(config('constants.JOB_D_TABLE'))
            ->where('JOB_NO', $id)
            ->select('JOB_NO', 'PORT_AMT', 'INDUSTRY_ZONE_AMT')
            ->get();
    }
    public static function generateSerNo($job_no, $order_type)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $query = DB::table(config('constants.JOB_D_TABLE'))
            ->where('JOB_NO', $job_no)
            ->where('BRANCH_ID', 'IHTVN1')
            ->where('ORDER_TYPE', $order_type);
        $count = $query->count();
        $job = $query->orderByDesc('JOB_NO')->take(1)->select('SER_NO')->first();
        // dd($count,$job);
        if ($count == 0) {
            $count = (int) $count + 1;
        } else {
            $count = (int) $job->SER_NO + 1;
        }
        $data = sprintf("%'.02d", $count);
        return $data;
    }
    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $ser_no = JobD::generateSerNo($request->JOB_NO, $request['ORDER_TYPE']);
            if ($request->TYPE == 'JOB_ORDER') {
                DB::table(config('constants.JOB_D_TABLE'))
                    ->insert(
                        [
                            "JOB_NO" => $request->JOB_NO,
                            "ORDER_TYPE" => ($request['ORDER_TYPE'] == 'undefined' || $request['ORDER_TYPE'] == 'null' || $request['ORDER_TYPE'] == null)  ? '' : $request['ORDER_TYPE'],
                            "SER_NO" => $ser_no,
                            "DESCRIPTION" => ($request['DESCRIPTION'] == 'undefined' || $request['DESCRIPTION'] == 'null' || $request['DESCRIPTION'] == null)  ? '' : $request['DESCRIPTION'],
                            "REV_TYPE" => ($request['REV_TYPE'] == 'undefined' || $request['REV_TYPE'] == 'null' || $request['REV_TYPE'] == null)  ? 'N' : $request['REV_TYPE'],
                            "INV_NO" => ($request['INV_NO'] == 'undefined' || $request['INV_NO'] == 'null' || $request['INV_NO'] == null)  ? '' : $request['INV_NO'],
                            "PORT_AMT" => ($request['PORT_AMT'] == 'undefined' || $request['PORT_AMT'] == 'null' || $request['PORT_AMT'] == null)  ? 0 : $request['PORT_AMT'],
                            "INDUSTRY_ZONE_AMT" => ($request['INDUSTRY_ZONE_AMT'] == 'undefined' || $request['INDUSTRY_ZONE_AMT'] == 'null' || $request['INDUSTRY_ZONE_AMT'] == null)  ? 0 : $request['INDUSTRY_ZONE_AMT'],
                            "NOTE" => ($request['NOTE'] == 'undefined' || $request['NOTE'] == 'null' || $request['NOTE'] == null)  ? '' : $request['NOTE'],
                            "THANH_TOAN_MK" => ($request['THANH_TOAN_MK'] == 'undefined' || $request['THANH_TOAN_MK'] == 'null' || $request['THANH_TOAN_MK'] == null)  ? 'N' : $request['THANH_TOAN_MK'],
                            "BRANCH_ID" => ($request['BRANCH_ID'] == 'undefined' || $request['BRANCH_ID'] == 'null' || $request['BRANCH_ID'] == null)  ? 'IHTVN1' : $request['BRANCH_ID'],
                            "INPUT_USER" => ($request['INPUT_USER'] == 'undefined' || $request['INPUT_USER'] == 'null' || $request['INPUT_USER'] == null)  ? '' : $request['INPUT_USER'],
                            "INPUT_DT" => date("YmdHis"),
                            //book tau
                            "QTY" => ($request['QTY'] == 'undefined' || $request['QTY'] == 'null' || $request['QTY'] == null)  ? 0 : $request['QTY'],
                            "PRICE" => ($request['PRICE'] == 'undefined' || $request['PRICE'] == 'null' || $request['PRICE'] == null)  ? 0 : $request['PRICE'],
                            "TAX_NOTE" => ($request['TAX_NOTE'] == 'undefined' || $request['TAX_NOTE'] == 'null' || $request['TAX_NOTE'] == null) ? 0 : $request['TAX_NOTE'],
                            "TAX_AMT" => ($request['TAX_AMT'] == 'undefined' || $request['TAX_AMT'] == 'null' || $request['TAX_AMT'] == null)  ? 0 : $request['TAX_AMT'],
                        ]
                    );
            } elseif ($request->TYPE == 'JOB_ORDER_BOAT') {
                DB::table(config('constants.JOB_D_TABLE'))
                    ->insert(
                        [
                            "JOB_NO" => $request->JOB_NO,
                            "ORDER_TYPE" => ($request['ORDER_TYPE'] == 'undefined' || $request['ORDER_TYPE'] == 'null' || $request['ORDER_TYPE'] == null)  ? '' : $request['ORDER_TYPE'],
                            "SER_NO" => $ser_no,
                            "DESCRIPTION" => ($request['DESCRIPTION'] == 'undefined' || $request['DESCRIPTION'] == 'null' || $request['DESCRIPTION'] == null)  ? '' : $request['DESCRIPTION'],
                            "UNIT" => ($request['UNIT'] == 'undefined' || $request['UNIT'] == 'null' || $request['UNIT'] == null)  ? '' : $request['UNIT'],
                            "QTY" => ($request['QTY'] == 'undefined' || $request['QTY'] == 'null' || $request['QTY'] == null)  ? 0 : $request['QTY'],
                            "PRICE" => ($request['PRICE'] == 'undefined' || $request['PRICE'] == 'null' || $request['PRICE'] == null)  ? 0 : $request['PRICE'],
                            "TAX_NOTE" => ($request['TAX_NOTE'] == 'undefined' || $request['TAX_NOTE'] == 'null' || $request['TAX_NOTE'] == null) ? 0 : $request['TAX_NOTE'],
                            "TAX_AMT" => ($request['TAX_AMT'] == 'undefined' || $request['TAX_AMT'] == 'null' || $request['TAX_AMT'] == null)  ? 0 : $request['TAX_AMT'],
                            "NOTE" => ($request['NOTE'] == 'undefined' || $request['NOTE'] == 'null' || $request['NOTE'] == null)  ? '' : $request['NOTE'],
                            "THANH_TOAN_MK" => ($request['THANH_TOAN_MK'] == 'undefined' || $request['THANH_TOAN_MK'] == 'null' || $request['THANH_TOAN_MK'] == null)  ? 'N' : $request['THANH_TOAN_MK'],
                            "BRANCH_ID" => ($request['BRANCH_ID'] == 'undefined' || $request['BRANCH_ID'] == 'null' || $request['BRANCH_ID'] == null)  ? 'IHTVN1' : $request['BRANCH_ID'],
                            "INPUT_USER" => ($request['INPUT_USER'] == 'undefined' || $request['INPUT_USER'] == 'null' || $request['INPUT_USER'] == null)  ? '' : $request['INPUT_USER'],
                            "INPUT_DT" => date("YmdHis"),
                            //job order
                            "PORT_AMT" => ($request['PORT_AMT'] == 'undefined' || $request['PORT_AMT'] == 'null' || $request['PORT_AMT'] == null)  ? 0 : $request['PORT_AMT'],
                            "INDUSTRY_ZONE_AMT" => ($request['INDUSTRY_ZONE_AMT'] == 'undefined' || $request['INDUSTRY_ZONE_AMT'] == 'null' || $request['INDUSTRY_ZONE_AMT'] == null)  ? 0 : $request['INDUSTRY_ZONE_AMT'],
                        ]
                    );
            }
            $data = JobD::getJob($request->JOB_NO, $request->TYPE);
            return $data;
        } catch (\Exception $e) {
            dd($e);
            return '201';
        }
    }
    public static function edit($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $JOB_NO = $request->JOB_NO;
            $query = DB::table('JOB_ORDER_D')->where('JOB_NO', $JOB_NO)->where('ORDER_TYPE', $request['ORDER_TYPE'])->where('SER_NO', $request['SER_NO']);
            if ($request->TYPE == 'JOB_ORDER') {
                $query->update(
                    [
                        "DESCRIPTION" => ($request['DESCRIPTION'] == 'undefined' || $request['DESCRIPTION'] == 'null' || $request['DESCRIPTION'] == null)  ? '' : $request['DESCRIPTION'],
                        "REV_TYPE" => ($request['REV_TYPE'] == 'undefined' || $request['REV_TYPE'] == 'null' || $request['REV_TYPE'] == null)  ? 'N' : $request['REV_TYPE'],
                        "INV_NO" => ($request['INV_NO'] == 'undefined' || $request['INV_NO'] == 'null' || $request['INV_NO'] == null)  ? '' : $request['INV_NO'],
                        "PORT_AMT" => ($request['PORT_AMT'] == 'undefined' || $request['PORT_AMT'] == 'null' || $request['PORT_AMT'] == null)  ? 0 : $request['PORT_AMT'],
                        "INDUSTRY_ZONE_AMT" => ($request['INDUSTRY_ZONE_AMT'] == 'undefined' || $request['INDUSTRY_ZONE_AMT'] == 'null' || $request['INDUSTRY_ZONE_AMT'] == null)  ? 0 : $request['INDUSTRY_ZONE_AMT'],
                        "NOTE" => ($request['NOTE'] == 'undefined' || $request['NOTE'] == 'null' || $request['NOTE'] == null)  ? '' : $request['NOTE'],
                        "THANH_TOAN_MK" => ($request['THANH_TOAN_MK'] == 'undefined' || $request['THANH_TOAN_MK'] == 'null' || $request['THANH_TOAN_MK'] == null)  ? 'N' : $request['THANH_TOAN_MK'],
                        'MODIFY_USER' =>  $request['MODIFY_USER'] == 'undefined' ? '' : $request['MODIFY_USER'],
                        'MODIFY_DT' =>  date("YmdHis"),
                        //book tau
                        "QTY" => ($request['QTY'] == 'undefined' || $request['QTY'] == 'null' || $request['QTY'] == null)  ? 0 : $request['QTY'],
                        "PRICE" => ($request['PRICE'] == 'undefined' || $request['PRICE'] == 'null' || $request['PRICE'] == null)  ? 0 : $request['PRICE'],
                        "TAX_NOTE" => ($request['TAX_NOTE'] == 'undefined' || $request['TAX_NOTE'] == 'null' || $request['TAX_NOTE'] == null) ? 0 : $request['TAX_NOTE'],
                        "TAX_AMT" => ($request['TAX_AMT'] == 'undefined' || $request['TAX_AMT'] == 'null' || $request['TAX_AMT'] == null)  ? 0 : $request['TAX_AMT'],
                    ]
                );
            } elseif ($request->TYPE == 'JOB_ORDER_BOAT') {
                $query->update(
                    [
                        "DESCRIPTION" => ($request['DESCRIPTION'] == 'undefined' || $request['DESCRIPTION'] == 'null' || $request['DESCRIPTION'] == null)  ? '' : $request['DESCRIPTION'],
                        "UNIT" => ($request['UNIT'] == 'undefined' || $request['UNIT'] == 'null' || $request['UNIT'] == null)  ? '' : $request['UNIT'],
                        "QTY" => ($request['QTY'] == 'undefined' || $request['QTY'] == 'null' || $request['QTY'] == null)  ? 0 : $request['QTY'],
                        "PRICE" => ($request['PRICE'] == 'undefined' || $request['PRICE'] == 'null' || $request['PRICE'] == null)  ? 0 : $request['PRICE'],
                        "TAX_NOTE" => ($request['TAX_NOTE'] == 'undefined' || $request['TAX_NOTE'] == 'null' || $request['TAX_NOTE'] == null) ? 0 : $request['TAX_NOTE'],
                        "TAX_AMT" => ($request['TAX_AMT'] == 'undefined' || $request['TAX_AMT'] == 'null' || $request['TAX_AMT'] == null)  ? 0 : $request['TAX_AMT'],
                        "NOTE" => ($request['NOTE'] == 'undefined' || $request['NOTE'] == 'null' || $request['NOTE'] == null)  ? '' : $request['NOTE'],
                        "THANH_TOAN_MK" => ($request['THANH_TOAN_MK'] == 'undefined' || $request['THANH_TOAN_MK'] == 'null' || $request['THANH_TOAN_MK'] == null)  ? 'N' : $request['THANH_TOAN_MK'],
                        'MODIFY_USER' =>  $request['MODIFY_USER'] == 'undefined' ? '' : $request['MODIFY_USER'],
                        'MODIFY_DT' =>  date("YmdHis"),
                        //job order
                        "PORT_AMT" => ($request['PORT_AMT'] == 'undefined' || $request['PORT_AMT'] == 'null' || $request['PORT_AMT'] == null)  ? 0 : $request['PORT_AMT'],
                        "INDUSTRY_ZONE_AMT" => ($request['INDUSTRY_ZONE_AMT'] == 'undefined' || $request['INDUSTRY_ZONE_AMT'] == 'null' || $request['INDUSTRY_ZONE_AMT'] == null)  ? 0 : $request['INDUSTRY_ZONE_AMT'],
                    ]
                );
            }
            $data = JobD::getJob($request->JOB_NO, $request->TYPE);
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function remove($request)
    {
        try {
            $title = '';
            $query = DB::table('JOB_ORDER_M as jm')->where('jm.JOB_NO', $request['JOB_NO']);
            //check CHK_MK
            $check_chk_mk =  $query->where('jm.CHK_MK', 'Y')->first();
            //check có dữ liệu trong job_order_d
            $check_job_order_d =  $query->leftjoin('JOB_ORDER_D as jd', 'jm.JOB_NO', '=', 'jd.JOB_NO')->select('jd.JOB_NO')->get();
            if ($check_chk_mk == null && count($check_job_order_d) == 0) {
                $title = 'Đã xóa thành công';
                DB::table('JOB_ORDER_D')->where('JOB_NO', $request['JOB_NO'])->where('ORDER_TYPE', $request['ORDER_TYPE'])->where('SER_NO', $request['SER_NO'])->delete();
                return $title;
            } elseif ($check_chk_mk != null) {
                $title = 'Đơn này đã được duyệt, bạn không thể xóa dữ liệu!';
                return $title;
            } elseif (count($check_job_order_d) != 0) {
                $title = 'Đã có dữ liệu chi tiết, không thể xóa!';
                return $title;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
}

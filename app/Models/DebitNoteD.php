<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DebitNoteD extends Model
{
    public static function des($id)
    {
        $data = DB::table('DEBIT_NOTE_D as dnd')
            // ->leftJoin('DM_NOIDUNG as dmnd', 'dnd.DESCRIPTION_CODE', 'dmnd.DESCRIPTION')
            ->where('dnd.JOB_NO', $id)
            ->select('dmnd.DESCRIPTION_NAME_VN', 'dnd.*')
            ->select('dnd.*')
            ->where('dnd.BRANCH_ID', 'IHTVN1')
            ->get();
        return $data;
    }
    public static function generateSerNo($job_no)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $count = DB::table(config('constants.DEBIT_NOTE_D_TABLE'))
            ->where('JOB_NO', $job_no)
            ->where('BRANCH_ID', 'IHTVN1')
            ->count();
        $count = (int) $count + 1;
        $data = sprintf("%'.02d", $count);
        return $data;
    }
    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $ser_no = DebitNoteD::generateSerNo($request->JOB_NO);
            DB::table(config('constants.DEBIT_NOTE_D_TABLE'))
                ->insert(
                    [
                        'JOB_NO' => ($request['JOB_NO'] == 'undefined' || $request['JOB_NO'] == 'null' || $request['JOB_NO'] == null) ? null : $request['JOB_NO'],
                        'SER_NO' => $ser_no,
                        'INV_YN' => ($request['INV_YN'] == 'undefined' || $request['INV_YN'] == 'null' || $request['INV_YN'] == null) ? null : $request['INV_YN'],
                        "INV_NO" => ($request['INV_NO'] == 'undefined' || $request['INV_NO'] == 'null' || $request['INV_NO'] == null) ? null : $request['INV_NO'],
                        // "TRANS_DATE" => ($request['TRANS_DATE'] == 'undefined' || $request['TRANS_DATE'] == 'null' || $request['TRANS_DATE'] == null) ? null : date('Ymd', strtotime($request['TRANS_DATE'])),
                        // "CUSTOM_NO" => ($request['CUSTOM_NO'] == 'undefined' || $request['CUSTOM_NO'] == 'null' || $request['CUSTOM_NO'] == null) ? null : $request['CUSTOM_NO'],
                        "DESCRIPTION" => ($request['DESCRIPTION'] == 'undefined' || $request['DESCRIPTION'] == 'null' || $request['DESCRIPTION'] == null) ? null : $request['DESCRIPTION'],
                        "UNIT" => ($request['UNIT'] == 'undefined' || $request['UNIT'] == 'null' || $request['UNIT'] == null) ? null : $request['UNIT'],
                        "QUANTITY" => ($request['QUANTITY'] == 'undefined' || $request['QUANTITY'] == 'null' || $request['QUANTITY'] == null) ? 0 : $request['QUANTITY'],
                        "PRICE" => ($request['PRICE'] == 'undefined' || $request['PRICE'] == 'null' || $request['PRICE'] == null) ? 0 : $request['PRICE'],
                        "TAX_NOTE" => ($request['TAX_NOTE'] == 'undefined' || $request['TAX_NOTE'] == 'null' || $request['TAX_NOTE'] == null) ? null : $request['TAX_NOTE'],
                        "TAX_AMT" => ($request['TAX_AMT'] == 'undefined' || $request['TAX_AMT'] == 'null' || $request['TAX_AMT'] == null) ? 0 : $request['TAX_AMT'],
                        "TOTAL_AMT" => ($request['TOTAL_AMT'] == 'undefined' || $request['TOTAL_AMT'] == 'null' || $request['TOTAL_AMT'] == 0) ? null : $request['TOTAL_AMT'],
                        // "NOTE" => ($request['NOTE'] == 'undefined' || $request['NOTE'] == 'null' || $request['NOTE'] == null) ? null : $request['NOTE'],
                        "DOR_NO" => ($request['DOR_NO'] == 'undefined' || $request['DOR_NO'] == 'null' || $request['DOR_NO'] == null) ? null : $request['DOR_NO'],
                        "DOR_AMT" => ($request['DOR_AMT'] == 'undefined' || $request['DOR_AMT'] == 'null' || $request['DOR_AMT'] == null) ? 0 : $request['DOR_AMT'],
                        "DOR_RATE" => ($request['DOR_RATE'] == 'undefined' || $request['DOR_RATE'] == 'null' || $request['DOR_RATE'] == null) ? 0 : $request['DOR_RATE'],
                        "DEB_TYPE" => ($request['DEB_TYPE'] == 'undefined' || $request['DEB_TYPE'] == 'null' || $request['DEB_TYPE'] == null) ? null : $request['DEB_TYPE'],
                        "BRANCH_ID" => $request['BRANCH_ID'] == 'undefined' ? 'IHTVN1' : $request['BRANCH_ID'],
                        "INPUT_USER" => ($request['INPUT_USER'] == 'undefined' || $request['INPUT_USER'] == 'null' || $request['INPUT_USER'] == null) ? null : $request['INPUT_USER'],
                        "INPUT_DT" => date("YmdHis")
                    ]
                );
            $data = DebitNoteD::des($request['JOB_NO']);
            return $data;
        } catch (\Exception $e) {
            return '201';
        }
    }
    public static function edit($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');

            DB::table(config('constants.DEBIT_NOTE_D_TABLE'))
                ->where('JOB_NO', $request['JOB_NO'])
                ->where('SER_NO', $request['SER_NO'])
                ->update(
                    [
                        'INV_YN' => ($request['INV_YN'] == 'undefined' || $request['INV_YN'] == 'null' || $request['INV_YN'] == null) ? null : $request['INV_YN'],
                        "INV_NO" => ($request['INV_NO'] == 'undefined' || $request['INV_NO'] == 'null' || $request['INV_NO'] == null) ? null : $request['INV_NO'],
                        // "TRANS_DATE" => ($request['TRANS_DATE'] == 'undefined' || $request['TRANS_DATE'] == 'null' || $request['TRANS_DATE'] == null) ? null : date('Ymd', strtotime($request['TRANS_DATE'])),
                        // "CUSTOM_NO" => ($request['CUSTOM_NO'] == 'undefined' || $request['CUSTOM_NO'] == 'null' || $request['CUSTOM_NO'] == null) ? null : $request['CUSTOM_NO'],
                        "DESCRIPTION" => ($request['DESCRIPTION'] == 'undefined' || $request['DESCRIPTION'] == 'null' || $request['DESCRIPTION'] == null) ? null : $request['DESCRIPTION'],
                        "UNIT" => ($request['UNIT'] == 'undefined' || $request['UNIT'] == 'null' || $request['UNIT'] == null) ? null : $request['UNIT'],
                        "QUANTITY" => ($request['QUANTITY'] == 'undefined' || $request['QUANTITY'] == 'null' || $request['QUANTITY'] == null) ? 0 : $request['QUANTITY'],
                        "PRICE" => ($request['PRICE'] == 'undefined' || $request['PRICE'] == 'null' || $request['PRICE'] == null) ? 0 : $request['PRICE'],
                        "TAX_NOTE" => ($request['TAX_NOTE'] == 'undefined' || $request['TAX_NOTE'] == 'null' || $request['TAX_NOTE'] == null) ? null : $request['TAX_NOTE'],
                        "TAX_AMT" => ($request['TAX_AMT'] == 'undefined' || $request['TAX_AMT'] == 'null' || $request['TAX_AMT'] == null) ? 0 : $request['TAX_AMT'],
                        "TOTAL_AMT" => ($request['TOTAL_AMT'] == 'undefined' || $request['TOTAL_AMT'] == 'null' || $request['TOTAL_AMT'] == null) ? 0 : $request['TOTAL_AMT'],
                        // "NOTE" => ($request['NOTE'] == 'undefined' || $request['NOTE'] == 'null' || $request['NOTE'] == null) ? null : $request['NOTE'],
                        "DOR_NO" => ($request['DOR_NO'] == 'undefined' || $request['DOR_NO'] == 'null' || $request['DOR_NO'] == null) ? null : $request['DOR_NO'],
                        "DOR_AMT" => ($request['DOR_AMT'] == 'undefined' || $request['DOR_AMT'] == 'null' || $request['DOR_AMT'] == null) ? 0 : $request['DOR_AMT'],
                        "DOR_RATE" => ($request['DOR_RATE'] == 'undefined' || $request['DOR_RATE'] == 'null' || $request['DOR_RATE'] == null) ? 0 : $request['DOR_RATE'],
                        "DEB_TYPE" => ($request['DEB_TYPE'] == 'undefined' || $request['DEB_TYPE'] == 'null' || $request['DEB_TYPE'] == null) ? null : $request['DEB_TYPE'],
                        'MODIFY_USER' =>  $request['MODIFY_USER'] == 'undefined' ? null : $request['MODIFY_USER'],
                        'MODIFY_DT' =>  date("YmdHis"),
                    ]
                );

            $data = DebitNoteD::des($request['JOB_NO']);
            return $data;
        } catch (\Exception $e) {
            return '201';
        }
    }
    public static function remove($request)
    {
        try {
            $title = '';
            $query = DB::table('DEBIT_NOTE_M as dnm')->where('dnm.JOB_NO', $request['JOB_NO']);
            //check CHK_MK
            $check_payment_mk =  $query->where('dnm.PAYMENT_CHK', 'Y')->first();
            //check có dữ liệu trong job_order_d
            $check_debit_d =  $query->leftjoin('DEBIT_NOTE_D as dnd', 'dnm.JOB_NO', '=', 'dnd.JOB_NO')->select('dnd.JOB_NO')->get();
            if ($check_payment_mk == null && count($check_debit_d) == 0) {
                $title = 'Đã xóa ' . $request['JOB_NO'] . ' thành công';
                DB::table('DEBIT_NOTE_D')->where('JOB_NO', $request['JOB_NO'])->where('SER_NO', $request['SER_NO'])->delete();
                return $title;
            } elseif ($check_payment_mk != null) {
                $title = 'Đơn này đã được duyệt, bạn không thể xóa dữ liệu!';
                return $title;
            } elseif (count($check_debit_d) != 0) {
                $title = 'Đã có dữ liệu chi tiết, không thể xóa!';
                return $title;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
}

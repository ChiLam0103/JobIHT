<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DebitNoteD extends Model
{
    public static function des($id)
    {
        $data = DB::table('DEBIT_NOTE_D as dnd')
            ->where('dnd.JOB_NO', $id)
            ->select('dnd.*')
            // ->selectRaw("(CASE WHEN (dnd.TAX_NOTE = '0%') THEN  (dnd.QUANTITY * dnd.PRICE)  ELSE (dnd.QUANTITY * dnd.PRICE) + (dnd.QUANTITY * dnd.PRICE) * dnd.TAX_NOTE/100 END) as TOTAL_AMT")
            ->where('dnd.BRANCH_ID', 'IHTVN1')
            ->get();
        return $data;
    }
    public static function generateSerNo($job_no)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $query = DB::table(config('constants.DEBIT_NOTE_D_TABLE'))
            ->where('JOB_NO', $job_no)
            ->where('BRANCH_ID', 'IHTVN1');
        $count = $query->count();
        $job = $query->orderByDesc('JOB_NO')->take(1)->select('SER_NO')->first();
        if ($count == 0) {
            $count = (int) $count + 1;
        } else {
            $count = (int) $job->SER_NO + 1;
        }
        $data = sprintf("%'.02d", $count);
        return $data;
    }
    public static function importDebitNoteD($array)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            foreach ($array as $request) {
                DB::table('DEBIT_NOTE_D')->where('JOB_NO', $request['job_no'])->delete();
            }
            foreach ($array as $request) {
                DB::table('DEBIT_NOTE_D')
                    ->insert(
                        [
                            'JOB_NO' => $request['job_no'],
                            'SER_NO' => $request['ser_no'],
                            'INV_YN' => 'N',
                            "INV_NO" =>  $request['inv_no'],
                            "DESCRIPTION" =>  $request['description'],
                            "UNIT" =>  $request['unit'],
                            "QUANTITY" => ($request['quantity'] == 'undefined' || $request['quantity'] == 'null' || $request['quantity'] == null) ? 0 : $request['quantity'],
                            "PRICE" => ($request['price'] == 'undefined' || $request['price'] == 'null' || $request['price'] == null) ? 0 :  $request['price'],
                            "TAX_NOTE" => $request['tax_note'],
                            "TAX_AMT" => ($request['tax_amt'] == 'undefined' || $request['tax_amt'] == 'null' || $request['tax_amt'] == null) ? 0 : $request['tax_amt'],
                            "TOTAL_AMT" => ($request['total_amt'] == 'undefined' || $request['total_amt'] == 'null' || $request['total_amt'] == null) ? 0 :   $request['total_amt'],
                            "DOR_NO" =>   $request['dor_no'],
                            "DOR_AMT" => ($request['dor_amt'] == 'undefined' || $request['dor_amt'] == 'null' || $request['dor_amt'] == null) ? 0 : $request['dor_amt'],
                            "DOR_RATE" => ($request['dor_rate'] == 'undefined' || $request['dor_rate'] == 'null' || $request['dor_rate'] == null) ? 0 : $request['dor_rate'],
                            "DEB_TYPE" => $request['deb_type'],
                            "BRANCH_ID" => 'IHTVN1',
                            "INPUT_USER" => $request['input_user'],
                            "INPUT_DT" => date("YmdHis")
                        ]
                    );
            }
            return true;
        } catch (\Exception $e) {
            return $e;
        }
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
                        "TOTAL_AMT" => ($request['TOTAL_AMT'] == 'undefined' || $request['TOTAL_AMT'] == 'null' || $request['TOTAL_AMT'] == 0) ? 0 : $request['TOTAL_AMT'],
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

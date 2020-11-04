<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DebitNoteD extends Model
{
    public static function des($id)
    {
        $data = DB::table(config('constants.DEBIT_NOTE_D_TABLE'))
            ->where('JOB_NO', $id)->get();
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
                        'JOB_NO' => ($request['JOB_NO'] != 'undefined' || $request['JOB_NO'] != 'null' || $request['JOB_NO'] != null) ? $request['JOB_NO'] : '',
                        'SER_NO' => $ser_no,
                        'INV_YN' => ($request['INV_YN'] != 'undefined' || $request['INV_YN'] != 'null' || $request['INV_YN'] != null) ? $request['INV_YN'] : '',
                        "INV_NO" => ($request['INV_NO'] != 'undefined' || $request['INV_NO'] != 'null' || $request['INV_NO'] != null) ? $request['INV_NO'] : '',
                        "TRANS_DATE" => ($request['TRANS_DATE'] != 'undefined' || $request['TRANS_DATE'] != 'null' || $request['TRANS_DATE'] != null) ? date('Ymd', strtotime($request['TRANS_DATE'])) : '',
                        "CUSTOM_NO" => ($request['CUSTOM_NO'] != 'undefined' || $request['CUSTOM_NO'] != 'null' || $request['CUSTOM_NO'] != null) ? $request['CUSTOM_NO'] : '',
                        "DESCRIPTION" => ($request['DESCRIPTION'] != 'undefined' || $request['DESCRIPTION'] != 'null' || $request['DESCRIPTION'] != null) ? $request['DESCRIPTION'] : '',
                        "UNIT" => ($request['UNIT'] != 'undefined' || $request['UNIT'] != 'null' || $request['UNIT'] != null) ? $request['UNIT'] : '',
                        "QUANTITY" => ($request['QUANTITY'] != 'undefined' || $request['QUANTITY'] != 'null' || $request['QUANTITY'] != null) ? $request['QUANTITY'] : '',
                        "PRICE" => ($request['PRICE'] != 'undefined' || $request['PRICE'] != 'null' || $request['PRICE'] != null) ? $request['PRICE'] : '',
                        "TAX_NOTE" => ($request['TAX_NOTE'] != 'undefined' || $request['TAX_NOTE'] != 'null' || $request['TAX_NOTE'] != null) ? $request['TAX_NOTE'] : '',
                        "TAX_AMT" => ($request['TAX_AMT'] != 'undefined' || $request['TAX_AMT'] != 'null' || $request['TAX_AMT'] != null) ? $request['TAX_AMT'] : '',
                        "TOTAL_AMT" => ($request['TOTAL_AMT'] != 'undefined' || $request['TOTAL_AMT'] != 'null' || $request['TOTAL_AMT'] != null) ? $request['TOTAL_AMT'] : '',
                        "NOTE" => ($request['NOTE'] != 'undefined' || $request['NOTE'] != 'null' || $request['NOTE'] != null) ? $request['NOTE'] : '',
                        "DOR_NO" => ($request['DOR_NO'] != 'undefined' || $request['DOR_NO'] != 'null' || $request['DOR_NO'] != null) ? $request['DOR_NO'] : '',
                        "DOR_AMT" => ($request['DOR_AMT'] != 'undefined' || $request['DOR_AMT'] != 'null' || $request['DOR_AMT'] != null) ? $request['DOR_AMT'] : '',
                        "DOR_RATE" => ($request['DOR_RATE'] != 'undefined' || $request['DOR_RATE'] != 'null' || $request['DOR_RATE'] != null) ? $request['DOR_RATE'] : '',
                        "DEB_TYPE" => ($request['DEB_TYPE'] != 'undefined' || $request['DEB_TYPE'] != 'null' || $request['DEB_TYPE'] != null) ? $request['DEB_TYPE'] : '',
                        "BRANCH_ID" => $request['BRANCH_ID'] != 'undefined' ? $request['BRANCH_ID'] : 'IHTVN1',
                        "INPUT_USER" => ($request['INPUT_USER'] != 'undefined' || $request['INPUT_USER'] != 'null' || $request['INPUT_USER'] != null) ? $request['INPUT_USER'] : '',
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
                        'INV_YN' => ($request['INV_YN'] != 'undefined' || $request['INV_YN'] != 'null' || $request['INV_YN'] != null) ? $request['INV_YN'] : '',
                        "INV_NO" => ($request['INV_NO'] != 'undefined' || $request['INV_NO'] != 'null' || $request['INV_NO'] != null) ? $request['INV_NO'] : '',
                        "TRANS_DATE" => ($request['TRANS_DATE'] != 'undefined' || $request['TRANS_DATE'] != 'null' || $request['TRANS_DATE'] != null) ? date('Ymd', strtotime($request['TRANS_DATE'])) : '',
                        "CUSTOM_NO" => ($request['CUSTOM_NO'] != 'undefined' || $request['CUSTOM_NO'] != 'null' || $request['CUSTOM_NO'] != null) ? $request['CUSTOM_NO'] : '',
                        "DESCRIPTION" => ($request['DESCRIPTION'] != 'undefined' || $request['DESCRIPTION'] != 'null' || $request['DESCRIPTION'] != null) ? $request['DESCRIPTION'] : '',
                        "UNIT" => ($request['UNIT'] != 'undefined' || $request['UNIT'] != 'null' || $request['UNIT'] != null) ? $request['UNIT'] : '',
                        "QUANTITY" => ($request['QUANTITY'] != 'undefined' || $request['QUANTITY'] != 'null' || $request['QUANTITY'] != null) ? $request['QUANTITY'] : '',
                        "PRICE" => ($request['PRICE'] != 'undefined' || $request['PRICE'] != 'null' || $request['PRICE'] != null) ? $request['PRICE'] : '',
                        "TAX_NOTE" => ($request['TAX_NOTE'] != 'undefined' || $request['TAX_NOTE'] != 'null' || $request['TAX_NOTE'] != null) ? $request['TAX_NOTE'] : '',
                        "TAX_AMT" => ($request['TAX_AMT'] != 'undefined' || $request['TAX_AMT'] != 'null' || $request['TAX_AMT'] != null) ? $request['TAX_AMT'] : '',
                        "TOTAL_AMT" => ($request['TOTAL_AMT'] != 'undefined' || $request['TOTAL_AMT'] != 'null' || $request['TOTAL_AMT'] != null) ? $request['TOTAL_AMT'] : '',
                        "NOTE" => ($request['NOTE'] != 'undefined' || $request['NOTE'] != 'null' || $request['NOTE'] != null) ? $request['NOTE'] : '',
                        "DOR_NO" => ($request['DOR_NO'] != 'undefined' || $request['DOR_NO'] != 'null' || $request['DOR_NO'] != null) ? $request['DOR_NO'] : '',
                        "DOR_AMT" => ($request['DOR_AMT'] != 'undefined' || $request['DOR_AMT'] != 'null' || $request['DOR_AMT'] != null) ? $request['DOR_AMT'] : '',
                        "DOR_RATE" => ($request['DOR_RATE'] != 'undefined' || $request['DOR_RATE'] != 'null' || $request['DOR_RATE'] != null) ? $request['DOR_RATE'] : '',
                        "DEB_TYPE" => ($request['DEB_TYPE'] != 'undefined' || $request['DEB_TYPE'] != 'null' || $request['DEB_TYPE'] != null) ? $request['DEB_TYPE'] : '',
                        'MODIFY_USER' =>  $request['MODIFY_USER'] != 'undefined' ? $request['MODIFY_USER'] : '',
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
            DB::table(config('constants.DEBIT_NOTE_D_TABLE'))
                ->where('LENDER_NO', $request['LENDER_NO'])
                ->delete();
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
}

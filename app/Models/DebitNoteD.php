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
    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            foreach ($request->data as $res) {
                DB::table(config('constants.DEBIT_NOTE_D_TABLE'))
                    ->insert(
                        [
                            'JOB_NO' => $res['JOB_NO'] != 'undefined' ? $request['JOB_NO'] : '',
                            'SER_NO' => $res['SER_NO'] != 'undefined' ? $request['SER_NO'] : '',
                            'INV_YN' => $res['INV_YN'] != 'undefined' ? $request['INV_YN'] : '',
                            "INV_NO" => $res['INV_NO'] != 'undefined' ? $request['INV_NO'] : '',
                            "TRANS_DATE" => $res['TRANS_DATE'] != 'undefined' ? date('Ymd', strtotime($request['TRANS_DATE'])) : '',
                            "CUSTOM_NO" => $res['CUSTOM_NO'] != 'undefined' ? $request['CUSTOM_NO'] : '',
                            "DESCRIPTION" => $res['DESCRIPTION'] != 'undefined' ? $request['DESCRIPTION'] : '',
                            "UNIT" => $res['UNIT'] != 'undefined' ? $request['UNIT'] : '',
                            "QUANTITY" => $res['QUANTITY'] != 'undefined' ? $request['QUANTITY'] : '',
                            "PRICE" => $res['PRICE'] != 'undefined' ? $request['PRICE'] : '',
                            "TAX_NOTE" => $res['TAX_NOTE'] != 'undefined' ? $request['TAX_NOTE'] : '',
                            "TAX_AMT" => $res['TAX_AMT'] != 'undefined' ? $request['TAX_AMT'] : '',
                            "TOTAL_AMT" => $res['TOTAL_AMT'] != 'undefined' ? $request['TOTAL_AMT'] : '',
                            "NOTE" => $res['NOTE'] != 'undefined' ? $request['NOTE'] : '',
                            "DOR_NO" => $res['DOR_NO'] != 'undefined' ? $request['DOR_NO'] : '',
                            "DOR_AMT" => $res['DOR_AMT'] != 'undefined' ? $request['DOR_AMT'] : '',
                            "DOR_RATE" => $res['DOR_RATE'] != 'undefined' ? $request['DOR_RATE'] : '',
                            "DEB_TYPE" => $res['DEB_TYPE'] != 'undefined' ? $request['DEB_TYPE'] : '',
                            "BRANCH_ID" => $res['BRANCH_ID'] != 'undefined' ? $request['BRANCH_ID'] : 'IHTVN1',
                            "INPUT_USER" => $res['INPUT_USER'] != 'undefined' ? $request['INPUT_USER'] : '',
                            "INPUT_DT" => date("YmdHis")
                        ]
                    );
            }
            $data = DebitNoteM::des($request['JOB_NO']);
            return $data;
        } catch (\Exception $e) {
            return '201';
        }
    }
    public static function edit($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            foreach ($request->data as $res) {
                DB::table(config('constants.DEBIT_NOTE_D_TABLE'))
                    ->where('JOB_NO', $request['JOB_NO'])
                    ->where('SER_NO', $request['SER_NO'])
                    ->update(
                        [
                            'INV_YN' => $res['INV_YN'] != 'undefined' ? $request['INV_YN'] : '',
                            "INV_NO" => $res['INV_NO'] != 'undefined' ? $request['INV_NO'] : '',
                            "TRANS_DATE" => $res['TRANS_DATE'] != 'undefined' ? date('Ymd', strtotime($request['TRANS_DATE'])) : '',
                            "CUSTOM_NO" => $res['CUSTOM_NO'] != 'undefined' ? $request['CUSTOM_NO'] : '',
                            "DESCRIPTION" => $res['DESCRIPTION'] != 'undefined' ? $request['DESCRIPTION'] : '',
                            "UNIT" => $res['UNIT'] != 'undefined' ? $request['UNIT'] : '',
                            "QUANTITY" => $res['QUANTITY'] != 'undefined' ? $request['QUANTITY'] : '',
                            "PRICE" => $res['PRICE'] != 'undefined' ? $request['PRICE'] : '',
                            "TAX_NOTE" => $res['TAX_NOTE'] != 'undefined' ? $request['TAX_NOTE'] : '',
                            "TAX_AMT" => $res['TAX_AMT'] != 'undefined' ? $request['TAX_AMT'] : '',
                            "TOTAL_AMT" => $res['TOTAL_AMT'] != 'undefined' ? $request['TOTAL_AMT'] : '',
                            "NOTE" => $res['NOTE'] != 'undefined' ? $request['NOTE'] : '',
                            "DOR_NO" => $res['DOR_NO'] != 'undefined' ? $request['DOR_NO'] : '',
                            "DOR_AMT" => $res['DOR_AMT'] != 'undefined' ? $request['DOR_AMT'] : '',
                            "DOR_RATE" => $res['DOR_RATE'] != 'undefined' ? $request['DOR_RATE'] : '',
                            "DEB_TYPE" => $res['DEB_TYPE'] != 'undefined' ? $request['DEB_TYPE'] : '',
                            'MODIFY_USER' =>  $res['MODIFY_USER'] != 'undefined' ? $request['MODIFY_USER'] : '',
                            'MODIFY_DT' =>  date("YmdHis"),
                        ]
                    );
            }
            $data = DebitNoteM::des($request['JOB_NO']);
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

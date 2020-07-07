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
                            'JOB_NO' => $res['JOB_NO'],
                            'SER_NO' => $res['SER_NO'],
                            'INV_YN' => $res['INV_YN'],
                            "INV_NO" => $res['INV_NO'],
                            "TRANS_DATE" => $res['TRANS_DATE'],
                            "CUSTOM_NO" => $res['CUSTOM_NO'],
                            "DESCRIPTION" => $res['DESCRIPTION'],
                            "UNIT" => $res['UNIT'],
                            "QUANTITY" => $res['QUANTITY'],
                            "PRICE" => $res['PRICE'],
                            "TAX_NOTE" => $res['TAX_NOTE'],
                            "TAX_AMT" => $res['TAX_AMT'],
                            "TOTAL_AMT" => $res['TOTAL_AMT'],
                            "NOTE" => $res['NOTE'],
                            "DOR_NO" => $res['DOR_NO'],
                            "DOR_AMT" => $res['DOR_AMT'],
                            "DOR_RATE" => $res['DOR_RATE'],
                            "DEB_TYPE" => $res['DEB_TYPE'],
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
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            foreach ($request->data as $res) {
                DB::table(config('constants.DEBIT_NOTE_D_TABLE'))
                    ->where('JOB_NO', $request['JOB_NO'])
                    ->where('SER_NO', $request['SER_NO'])
                    ->update(
                        [
                            'INV_YN' => $res['INV_YN'],
                            "INV_NO" => $res['INV_NO'],
                            "TRANS_DATE" => $res['TRANS_DATE'],
                            "CUSTOM_NO" => $res['CUSTOM_NO'],
                            "DESCRIPTION" => $res['DESCRIPTION'],
                            "UNIT" => $res['UNIT'],
                            "QUANTITY" => $res['QUANTITY'],
                            "PRICE" => $res['PRICE'],
                            "TAX_NOTE" => $res['TAX_NOTE'],
                            "TAX_AMT" => $res['TAX_AMT'],
                            "TOTAL_AMT" => $res['TOTAL_AMT'],
                            "NOTE" => $res['NOTE'],
                            "DOR_NO" => $res['DOR_NO'],
                            "DOR_AMT" => $res['DOR_AMT'],
                            "DOR_RATE" => $res['DOR_RATE'],
                            "DEB_TYPE" => $res['DEB_TYPE'],
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
            DB::table(config('constants.DEBIT_NOTE_D_TABLE'))
                ->where('LENDER_NO', $request['LENDER_NO'])
                ->delete();
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
}

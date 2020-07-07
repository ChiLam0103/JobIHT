<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DebitNoteM extends Model
{
    public static function list()
    {
        $data = DB::table(config('constants.DEBIT_NOTE_M_TABLE'))->orderBy('JOB_NO', 'desc')->take(100)->get();
        return $data;
    }
    public static function listNotCreated()
    {
        $data = DB::table('JOB_ORDER_M as jom')
        ->leftJoin('DEBIT_NOTE_M as dnm','jom.JOB_NO','dnm.JOB_NO')
        ->whereNull('dnm.JOB_NO')->select('jom.*')
        ->orderBy('jom.JOB_NO', 'desc')->take(100)->get();
        return $data;
    }
    public static function des($id)
    {
        $data = DB::table(config('constants.DEBIT_NOTE_M_TABLE'))
            ->where('JOB_NO', $id)->first();
        return $data;
    }
    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table(config('constants.DEBIT_NOTE_M_TABLE'))
                ->insert(
                    [
                        'JOB_NO' => $request['JOB_NO'],
                        'CUST_NO' => $request['CUST_NO'],
                        "DEBIT_DATE" => date("Ymd"),
                        "CONSIGNEE" => $request['CONSIGNEE'],
                        "SHIPPER" => $request['SHIPPER'],
                        "TRANS_FROM" => $request['TRANS_FROM'],
                        "TRANS_TO" => $request['TRANS_TO'],
                        "CONTAINER_NO" => $request['CONTAINER_NO'],
                        "CONTAINER_QTY" => $request['CONTAINER_QTY'],
                        "CUSTOMS_NO" => $request['CUSTOMS_NO'],
                        "CUSTOMS_DATE" => $request['CUSTOMS_DATE'],
                        "CUST_NO" => $request['CUST_NO'],
                        "BILL_NO" => $request['BILL_NO'],
                        "NW" => $request['NW'],
                        "GW" => $request['GW'],
                        "POL" => $request['POL'],
                        "POD" => $request['POD'],
                        "ETD_ETA" => $request['ETD_ETA'],
                        "PO_NO" => $request['PO_NO'],
                        "ORDER_NO" => $request['ORDER_NO'],
                        "PAYMENT_CHK" => 'N',
                        "INVOICE_NO" => $request['INVOICE_NO'],
                        "NOTE" => $request['NOTE'],
                        "CUST_NO2" => $request['CUST_NO2'],
                        "CUST_NO3" => $request['CUST_NO3'],
                        "CUST_NO4" => $request['CUST_NO4'],
                        "CUST_NO5" => $request['CUST_NO5'],
                        "BRANCH_ID" => $request['BRANCH_ID'],
                        "INPUT_USER" => $request['INPUT_USER'],
                        "INPUT_DT" => date("YmdHis"),
                    ]
                );
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function edit($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table(config('constants.DEBIT_NOTE_M_TABLE'))
            ->where('JOB_NO',$request['JOB_NO'])
                ->update(
                    [
                        'CUST_NO' => $request['CUST_NO'],
                        "DEBIT_DATE" => date("Ymd"),
                        "CONSIGNEE" => $request['CONSIGNEE'],
                        "SHIPPER" => $request['SHIPPER'],
                        "TRANS_FROM" => $request['TRANS_FROM'],
                        "TRANS_TO" => $request['TRANS_TO'],
                        "CONTAINER_NO" => $request['CONTAINER_NO'],
                        "CONTAINER_QTY" => $request['CONTAINER_QTY'],
                        "CUSTOMS_NO" => $request['CUSTOMS_NO'],
                        "CUSTOMS_DATE" => $request['CUSTOMS_DATE'],
                        "CUST_NO" => $request['CUST_NO'],
                        "BILL_NO" => $request['BILL_NO'],
                        "NW" => $request['NW'],
                        "GW" => $request['GW'],
                        "POL" => $request['POL'],
                        "POD" => $request['POD'],
                        "ETD_ETA" => $request['ETD_ETA'],
                        "PO_NO" => $request['PO_NO'],
                        "ORDER_NO" => $request['ORDER_NO'],
                        "INVOICE_NO" => $request['INVOICE_NO'],
                        "NOTE" => $request['NOTE'],
                        "CUST_NO2" => $request['CUST_NO2'],
                        "CUST_NO3" => $request['CUST_NO3'],
                        "CUST_NO4" => $request['CUST_NO4'],
                        "CUST_NO5" => $request['CUST_NO5'],
                        'MODIFY_USER' =>  $request['MODIFY_USER'],
                        'MODIFY_DT' =>  date("YmdHis"),
                    ]
                );
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function remove($request)
    {
        try {
            DB::table(config('constants.DEBIT_NOTE_M_TABLE'))
                ->where('JOB_NO', $request['JOB_NO'])
                ->delete();
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
}

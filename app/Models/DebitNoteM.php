<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DebitNoteM extends Model
{
    public static function list()
    {
        $data = DB::table(config('constants.DEBIT_NOTE_M_TABLE'))
            ->orderBy('JOB_NO', 'desc')
            ->where('BRANCH_ID', 'IHTVN1')
            ->whereNotIn('PAYMENT_CHK', ['Y'])
            ->take(1000)->get();
        return $data;
    }
    public static function search($type, $value)
    {

        $a = DB::table('DEBIT_NOTE_M as dnm')
            ->where('dnm.BRANCH_ID', 'IHTVN1')
            ->whereNotIn('dnm.PAYMENT_CHK', ['Y']);

        if ($type == '1') { //job no
            $a->where('dnm.JOB_NO', 'LIKE', '%' . $value . '%');
        } elseif ($type == '2') { //customer no
            $a->where('dnm.CUST_NO', 'LIKE', '%' . $value . '%');
        } elseif ($type == '3') { //container no
            $a->where('dnm.CONTAINER_NO', 'LIKE', '%' . $value . '%');
        } elseif ($type == '4') { //customns no
            $a->where('dnm.CUSTOMS_NO', 'LIKE', '%' . $value . '%');
        }
        $data = $a->select('dnm.*')
            ->orderBy('dnm.JOB_NO', 'desc')
            ->take(9000)
            ->get();
        return $data;
    }
    public static function listNotCreated()
    {
        $data = DB::table('JOB_ORDER_M as jom')
            ->leftJoin('DEBIT_NOTE_M as dnm', 'jom.JOB_NO', 'dnm.JOB_NO')
            ->whereNull('dnm.JOB_NO')->select('jom.JOB_NO')
            ->where('dnm.BRANCH_ID', 'IHTVN1')
            ->orderBy('jom.JOB_NO', 'desc')->get();
        return $data;
    }

    public static function desJobNotCreated($id)
    {
        $data = DB::table('JOB_ORDER_M as jom')
            ->leftJoin('DEBIT_NOTE_M as dnm', 'jom.JOB_NO', 'dnm.JOB_NO')
            ->where('jom.JOB_NO', $id)
            ->where('dnm.BRANCH_ID', 'IHTVN1')
            ->whereNull('dnm.JOB_NO')->select('jom.*')->first();
        return $data;
    }
    public static function listPending()
    {
        $data = DB::table('DEBIT_NOTE_M as dnm')
            ->leftJoin('CUSTOMER as c', 'c.CUST_NO', 'dnm.CUST_NO')
            ->leftJoin('DEBIT_NOTE_D as dnd', 'dnd.JOB_NO', 'dnm.JOB_NO')
            ->where(function ($query) {
                $query->where('dnm.PAYMENT_CHK', null)
                    ->orWhere('dnm.PAYMENT_CHK', 'N');
            })
            ->where('dnm.BRANCH_ID', 'IHTVN1')
            ->orderBy('dnm.JOB_NO', 'desc')
            ->select('c.CUST_NAME', 'dnm.JOB_NO', 'dnm.CUST_NO', 'dnm.DEBIT_DATE', 'dnm.TRANS_FROM', 'dnm.TRANS_TO', 'PAYMENT_DATE')
            ->selectRaw('sum(dnd.QUANTITY * dnd.PRICE + CASE WHEN dnd.TAX_AMT = 0 THEN 0 ELSE (dnd.QUANTITY * dnd.PRICE)/dnd.TAX_NOTE END) as sum_AMT')
            ->groupBy('c.CUST_NAME', 'dnm.JOB_NO', 'dnm.CUST_NO', 'dnm.DEBIT_DATE', 'dnm.TRANS_FROM', 'dnm.TRANS_TO', 'PAYMENT_DATE')
            ->take(5000)
            ->get();
        return $data;
    }
    public static function listPaid()
    {
        $data = DB::table('DEBIT_NOTE_M as dnm')
            ->leftJoin('CUSTOMER as c', 'c.CUST_NO', 'dnm.CUST_NO')
            ->leftJoin('DEBIT_NOTE_D as dnd', 'dnd.JOB_NO', 'dnm.JOB_NO')
            ->where('dnm.PAYMENT_CHK', 'Y')
            ->where('dnm.BRANCH_ID', 'IHTVN1')
            ->orderBy('dnm.JOB_NO', 'desc')
            ->select('c.CUST_NAME', 'dnm.JOB_NO', 'dnm.CUST_NO', 'dnm.DEBIT_DATE', 'dnm.TRANS_FROM', 'dnm.TRANS_TO', 'PAYMENT_DATE')
            ->selectRaw('sum(dnd.QUANTITY * dnd.PRICE + CASE WHEN dnd.TAX_AMT = 0 THEN 0 ELSE (dnd.QUANTITY * dnd.PRICE)/dnd.TAX_NOTE END) as sum_AMT')
            ->groupBy('c.CUST_NAME', 'dnm.JOB_NO', 'dnm.CUST_NO', 'dnm.DEBIT_DATE', 'dnm.TRANS_FROM', 'dnm.TRANS_TO', 'PAYMENT_DATE')
            ->take(5000)
            ->get();
        return $data;
    }
    public static function des($id)
    {
        $data = DB::table(config('constants.DEBIT_NOTE_M_TABLE'))
            ->where('BRANCH_ID', 'IHTVN1')
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
                        'JOB_NO' => $request['JOB_NO'] != 'undefined' ? $request['JOB_NO'] : '',
                        'CUST_NO' => $request['CUST_NO'] != 'undefined' ? $request['CUST_NO'] : '',
                        "CONSIGNEE" => $request['CONSIGNEE'] != 'undefined' ? $request['CONSIGNEE'] : '',
                        "SHIPPER" => $request['SHIPPER'] != 'undefined' ? $request['SHIPPER'] : '',
                        "TRANS_FROM" => $request['TRANS_FROM'] != 'undefined' ? $request['TRANS_FROM'] : '',
                        "TRANS_TO" => $request['TRANS_TO'] != 'undefined' ? $request['TRANS_TO'] : '',
                        "CONTAINER_NO" => $request['CONTAINER_NO'] != 'undefined' ? $request['CONTAINER_NO'] : '',
                        "CONTAINER_QTY" => $request['CONTAINER_QTY'] != 'undefined' ? $request['CONTAINER_QTY'] : '',
                        "CUSTOMS_NO" => $request['CUSTOMS_NO'] != 'undefined' ? $request['CUSTOMS_NO'] : '',
                        "CUSTOMS_DATE" => $request['CUSTOMS_DATE'] != 'undefined' ? date('Ymd', strtotime($request['CUSTOMS_DATE'])) : '',
                        "BILL_NO" => $request['BILL_NO'] != 'undefined' ? $request['BILL_NO'] : '',
                        "NW" => $request['NW'] != 'undefined' ? $request['NW'] : '',
                        "GW" => $request['GW'] != 'undefined' ? $request['GW'] : '',
                        "POL" => $request['POL'] != 'undefined' ? $request['POL'] : '',
                        "POD" => $request['POD'] != 'undefined' ? $request['POD'] : '',
                        "ETD_ETA" => $request['ETD_ETA'] != 'undefined' ? $request['ETD_ETA'] : '',
                        "PO_NO" => $request['PO_NO'] != 'undefined' ? $request['PO_NO'] : '',
                        "ORDER_NO" => $request['ORDER_NO'] != 'undefined' ? $request['ORDER_NO'] : '',
                        "PAYMENT_CHK" => 'N',
                        "INVOICE_NO" => $request['INVOICE_NO'] != 'undefined' ? $request['INVOICE_NO'] : '',
                        "NOTE" => $request['NOTE'] != 'undefined' ? $request['NOTE'] : '',
                        "CUST_NO2" => $request['CUST_NO2'] != 'undefined' ? $request['CUST_NO2'] : '',
                        "CUST_NO3" => $request['CUST_NO3'] != 'undefined' ? $request['CUST_NO3'] : '',
                        "CUST_NO4" => $request['CUST_NO4'] != 'undefined' ? $request['CUST_NO4'] : '',
                        "CUST_NO5" => $request['CUST_NO5'] != 'undefined' ? $request['CUST_NO5'] : '',
                        "DEBIT_DATE" => date("Ymd"),
                        "BRANCH_ID" => $request['BRANCH_ID'] != 'undefined' ? $request['BRANCH_ID'] : 'IHTVN1',
                        "INPUT_USER" => $request['INPUT_USER'] != 'undefined' ? $request['INPUT_USER'] : '',
                        "INPUT_DT" => date("YmdHis"),
                    ]
                );
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
            DB::table(config('constants.DEBIT_NOTE_M_TABLE'))
                ->where('JOB_NO', $request['JOB_NO'])
                ->update(
                    [
                        "CONSIGNEE" => $request['CONSIGNEE'] != 'undefined' ? $request['CONSIGNEE'] : '',
                        "SHIPPER" => $request['SHIPPER'] != 'undefined' ? $request['SHIPPER'] : '',
                        "TRANS_FROM" => $request['TRANS_FROM'] != 'undefined' ? $request['TRANS_FROM'] : '',
                        "TRANS_TO" => $request['TRANS_TO'] != 'undefined' ? $request['TRANS_TO'] : '',
                        "CONTAINER_NO" => $request['CONTAINER_NO'] != 'undefined' ? $request['CONTAINER_NO'] : '',
                        "CONTAINER_QTY" => $request['CONTAINER_QTY'] != 'undefined' ? $request['CONTAINER_QTY'] : '',
                        "CUSTOMS_NO" => $request['CUSTOMS_NO'] != 'undefined' ? $request['CUSTOMS_NO'] : '',
                        "CUSTOMS_DATE" => $request['CUSTOMS_DATE'] != 'undefined' ? date('Ymd', strtotime($request['CUSTOMS_DATE'])) : '',
                        "BILL_NO" => $request['BILL_NO'] != 'undefined' ? $request['BILL_NO'] : '',
                        "NW" => $request['NW'] != 'undefined' ? $request['NW'] : '',
                        "GW" => $request['GW'] != 'undefined' ? $request['GW'] : '',
                        "POL" => $request['POL'] != 'undefined' ? $request['POL'] : '',
                        "POD" => $request['POD'] != 'undefined' ? $request['POD'] : '',
                        "ETD_ETA" => $request['ETD_ETA'] != 'undefined' ? $request['ETD_ETA'] : '',
                        "PO_NO" => $request['PO_NO'] != 'undefined' ? $request['PO_NO'] : '',
                        "ORDER_NO" => $request['ORDER_NO'] != 'undefined' ? $request['ORDER_NO'] : '',
                        "INVOICE_NO" => $request['INVOICE_NO'] != 'undefined' ? $request['INVOICE_NO'] : '',
                        "NOTE" => $request['NOTE'] != 'undefined' ? $request['NOTE'] : '',
                        "CUST_NO2" => $request['CUST_NO2'] != 'undefined' ? $request['CUST_NO2'] : '',
                        "CUST_NO3" => $request['CUST_NO3'] != 'undefined' ? $request['CUST_NO3'] : '',
                        "CUST_NO4" => $request['CUST_NO4'] != 'undefined' ? $request['CUST_NO4'] : '',
                        "CUST_NO5" => $request['CUST_NO5'] != 'undefined' ? $request['CUST_NO5'] : '',
                        'MODIFY_USER' =>  $request['MODIFY_USER'],
                        'MODIFY_DT' =>  date("YmdHis"),
                    ]
                );
            $data = DebitNoteM::des($request['JOB_NO']);
            return $data;
        } catch (\Exception $e) {
            return '201';
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
    public static function change($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            if ($request->TYPE == 1) {
                DB::table(config('constants.DEBIT_NOTE_M_TABLE'))
                    ->where('JOB_NO', $request["JOB_NO"])
                    ->update([
                        "PAYMENT_CHK" => "Y",
                        "PAYMENT_DATE" => date("Ymd")
                    ]);
            } else {
                DB::table(config('constants.DEBIT_NOTE_M_TABLE'))
                    ->where('JOB_NO', $request["JOB_NO"])
                    ->update([
                        "PAYMENT_CHK" => "N",
                        "PAYMENT_DATE" => date("Ymd")
                    ]);
            }
            return '200';
        } catch (\Exception $e) {
            return '201';
        }
    }
    public static function checkData($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $today = date("Ymd");
            $from_date = ($request->FROM_DATE != 'undefined' ? $request->FROM_DATE : '19000101');
            $to_date = ($request->TO_DATE != 'undefined' ? $request->TO_DATE : $today);
            if ($request->TYPE == "1") { //chua thanh toan
                $a = DB::table('DEBIT_NOTE_M as dnm')
                    ->leftJoin('CUSTOMER as c', 'dnm.CUST_NO', 'c.CUST_NO')
                    ->where('dnm.PAYMENT_CHK', null)
                    ->orWhere('dnm.PAYMENT_CHK', 'N')
                    ->select('dnm.JOB_NO', 'dnm.CUST_NO', 'c.CUST_NAME', 'dnm.TRANS_FROM', 'dnm.TRANS_TO', 'dnm.PAYMENT_CHK', 'dnm.PAYMENT_DATE');
            } elseif ($request->TYPE == "2") { //da thanh toan
                $a = DB::table('DEBIT_NOTE_M as dnm')
                    ->leftJoin('CUSTOMER as c', 'dnm.CUST_NO', 'c.CUST_NO')
                    ->where('dnm.PAYMENT_CHK', 'Y')
                    ->select('dnm.JOB_NO', 'dnm.CUST_NO', 'c.CUST_NAME', 'dnm.TRANS_FROM', 'dnm.TRANS_TO', 'dnm.PAYMENT_CHK', 'dnm.PAYMENT_DATE');
            } elseif ($request->TYPE == "3") { //tat ca
                $a = DB::table('DEBIT_NOTE_M as dnm')
                    ->leftJoin('CUSTOMER as c', 'dnm.CUST_NO', 'c.CUST_NO')
                    ->select('dnm.JOB_NO', 'dnm.CUST_NO', 'c.CUST_NAME', 'dnm.TRANS_FROM', 'dnm.TRANS_TO', 'dnm.PAYMENT_CHK', 'dnm.PAYMENT_DATE');
            } elseif ($request->TYPE == "4") { //loi nhuan
                $a = DB::table('DEBIT_NOTE_M as dnm')
                    ->leftJoin('CUSTOMER as c', 'dnm.CUST_NO', 'c.CUST_NO')
                    ->leftJoin('DEBIT_NOTE_D as dnd', 'dnm.JOB_NO', 'dnd.JOB_NO')
                    ->select('dnm.JOB_NO', 'dnm.CUST_NO', 'c.CUST_NAME', DB::raw('SUM(dnd.PRICE) as PRICE'))
                    ->groupBy('dnm.JOB_NO', 'dnm.CUST_NO', 'c.CUST_NAME');
            }
            if ($request->JOB_NO != 'undefined') {
                $a->where('dnm.JOB_NO', $request->JOB_NO);
            }
            if ($request->CUST_NO != 'undefined') {
                $a->where('dnm.CUST_NO', $request->CUST_NO);
            }
            $data = $a->take(1000)
                ->where('dnm.BRANCH_ID', 'IHTVN1')
                ->whereBetween('dnm.PAYMENT_DATE', [$from_date, $to_date])
                ->orderByDesc('dnm.JOB_NO')
                ->get();
            return $data;
        } catch (\Exception $e) {
            return '201';
        }
    }
}

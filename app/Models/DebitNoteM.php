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
            ->leftJoin('DEBIT_NOTE_M as dnm', 'jom.JOB_NO', 'dnm.JOB_NO')
            ->whereNull('dnm.JOB_NO')->select('jom.*')
            ->orderBy('jom.JOB_NO', 'desc')->take(100)->get();
        return $data;
    }
    public static function listPending()
    {
        $data = DB::table(config('constants.DEBIT_NOTE_M_TABLE'))
            ->where(function ($query) {
                $query->where('PAYMENT_CHK', null)
                    ->orWhere('PAYMENT_CHK', 'N');
            })
            ->orderBy('JOB_NO', 'desc')->take(100)->get();
        return $data;
    }
    public static function listPaid()
    {
        $data = DB::table(config('constants.DEBIT_NOTE_M_TABLE'))
            ->where('PAYMENT_CHK', 'Y')
            ->orderBy('JOB_NO', 'desc')->take(1000)->get();
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
                foreach ($request->JOB as $job) {
                    DB::table(config('constants.DEBIT_NOTE_M_TABLE'))
                        ->where('JOB_NO', $job["JOB_NO"])
                        ->update([
                            "PAYMENT_CHK" => "Y",
                            "PAYMENT_DATE" => date("Ymd")
                        ]);
                }
            } else {
                foreach ($request->JOB as $job) {
                    DB::table(config('constants.DEBIT_NOTE_M_TABLE'))
                        ->where('JOB_NO', $job["JOB_NO"])
                        ->update([
                            "PAYMENT_CHK" => "N",
                            "PAYMENT_DATE" => date("Ymd")
                        ]);
                }
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
            $to_date = ($request->TO_DATE ? $request->TO_DATE : '19000101');
            $from_date = ($request->FROM_DATE ? $request->FROM_DATE : $today);
            if ($request->TYPE == "1") { //chua thanh toan
                $a = DB::table('DEBIT_NOTE_M as dnm')
                    ->rightJoin('CUSTOMER as c', 'dnm.CUST_NO', 'c.CUST_NO')
                    ->where('dnm.PAYMENT_CHK', 'Y')
                    ->select('dnm.JOB_NO', 'dnm.CUST_NO', 'c.CUST_NAME', 'dnm.TRANS_FROM', 'dnm.TRANS_TO', 'dnm.PAYMENT_CHK', 'dnm.PAYMENT_DATE');
               
            } elseif ($request->TYPE == "2") { //da thanh toan
                $a = DB::table('DEBIT_NOTE_M as dnm')
                    ->join('CUSTOMER as c', 'dnm.CUST_NO', 'c.CUST_NO')
                    ->where('dnm.PAYMENT_CHK', null)
                    ->orWhere('dnm.PAYMENT_CHK', 'N')
                    ->select('dnm.JOB_NO', 'dnm.CUST_NO', 'c.CUST_NAME', 'dnm.TRANS_FROM', 'dnm.TRANS_TO', 'dnm.PAYMENT_CHK', 'dnm.PAYMENT_DATE');
            } elseif ($request->TYPE == "3") { //tat ca
                $a = DB::table('DEBIT_NOTE_M as dnm')
                    ->rightJoin('CUSTOMER as c', 'dnm.CUST_NO', 'c.CUST_NO')
                    ->select('dnm.JOB_NO', 'dnm.CUST_NO', 'c.CUST_NAME', 'dnm.TRANS_FROM', 'dnm.TRANS_TO', 'dnm.PAYMENT_CHK', 'dnm.PAYMENT_DATE');
            } elseif ($request->TYPE == "4") { //loi nhuan
                $a = DB::table('DEBIT_NOTE_M as dnm')
                    ->rightJoin('CUSTOMER as c', 'dnm.CUST_NO', 'c.CUST_NO')
                    ->join('DEBIT_NOTE_D as dnd', 'dnm.JOB_NO', 'dnd.JOB_NO')
                    ->select('dnm.JOB_NO', 'dnm.CUST_NO', 'c.CUST_NAME', DB::raw('SUM(dnd.PRICE) as PRICE'))
                    ->groupBy('dnm.JOB_NO', 'dnm.CUST_NO', 'c.CUST_NAME');
            }
            if ($request->JOB_NO) {
                $a->where('dnm.JOB_NO', $request->JOB_NO);
            }
            if ($request->CUST_NO) {
                $a->where('dnm.CUST_NO', $request->CUST_NO);
            }
            if ($request->TO_DATE || $request->FROM_DATE) {
                $a->whereBetween('dnm.PAYMENT_DATE', [$to_date, $from_date]);
            }
            $data = $a->take(1000)->get();
            return $data;
        } catch (\Exception $e) {
            return '201';
        }
    }
}

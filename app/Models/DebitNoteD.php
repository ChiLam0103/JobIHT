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
        $count = (int) $count + 1;

        $data = sprintf("%'.02d", $count);
        return $data;
    }
    public static function importDebitNoteD($array)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $debit_note_m = DB::table('DEBIT_NOTE_M')->where('JOB_NO', $array['job_no'])->first();
            if (!$debit_note_m) {
                $job_order = DB::table('JOB_ORDER_M')->where('JOB_NO', $array['job_no'])->first();
                DB::table(config('constants.DEBIT_NOTE_M_TABLE'))
                    ->insert(
                        [
                            'JOB_NO' => $job_order->JOB_NO,
                            'CUST_NO' => $job_order->CUST_NO,
                            "CONSIGNEE" =>  $job_order->CONSIGNEE,
                            "SHIPPER" => $job_order->SHIPPER,
                            "TRANS_FROM" =>  $job_order->ORDER_FROM,
                            "TRANS_TO" =>  $job_order->ORDER_TO,
                            "CONTAINER_NO" => $job_order->CONTAINER_NO,
                            "CONTAINER_QTY" => $job_order->CONTAINER_QTY,
                            "QTY" => $job_order->QTY,
                            "CUSTOMS_NO" => $job_order->CUSTOMS_NO,
                            "CUSTOMS_DATE" => $job_order->CUSTOMS_DATE,
                            "BILL_NO" => $job_order->BILL_NO,
                            "NW" => $job_order->NW,
                            "GW" => $job_order->GW,
                            "POL" => $job_order->POL,
                            "POD" => $job_order->POD,
                            "ETD_ETA" =>  $job_order->ETD_ETA,
                            "PO_NO" => $job_order->PO_NO,
                            "INVOICE_NO" =>  $job_order->INVOICE_NO,
                            "NOTE" => $job_order->NOTE,
                            "DEBIT_DATE" => date("Ymd"),
                            "BRANCH_ID" => 'IHTVN1',
                            "INPUT_USER" => 'JENNY',
                            "INPUT_DT" => date("YmdHis"),
                            "PAYMENT_CHK" => 'N',
                        ]
                    );
            }

            $ser_no = DebitNoteD::generateSerNo($array['job_no']);
            DB::table(config('constants.DEBIT_NOTE_D_TABLE'))
                ->insert(
                    [
                        'JOB_NO' => $array['job_no'],
                        'SER_NO' => $ser_no,
                        'INV_YN' => 'N',
                        "INV_NO" =>  $array['invoice_no'],
                        "DESCRIPTION" =>  $array['description'],
                        "UNIT" =>  $array['unit'],
                        "QUANTITY" => ($array['qty'] == 'undefined' || $array['qty'] == 'null' || $array['qty'] == null) ? 0 : $array['qty'],
                        "PRICE" => ($array['price_vnd'] == 'undefined' || $array['price_vnd'] == 'null' || $array['price_vnd'] == null) ? 0 : $array['price_vnd'],
                        "TAX_AMT" => ($array['price_vnd'] * $array['tax'])/100,
                        "TAX_NOTE" => ($array['tax'] == 'undefined' || $array['tax'] == 'null' || $array['tax'] == null) ? 0 : $array['tax'],
                        "TOTAL_AMT" => ($array['total'] == 'undefined' || $array['total'] == 'null' || $array['total'] == null) ? 0 : $array['total'],
                        "DOR_NO" =>   $array['currency'],
                        "DOR_AMT" => ($array['price'] == 'undefined' || $array['price'] == 'null' || $array['price'] == null) ? 0 : $array['price'],
                        "DOR_RATE" => ($array['rate'] == 'undefined' || $array['rate'] == 'null' || $array['rate'] == null) ? 0 : $array['rate'],
                        "DEB_TYPE" => $array['type'],
                        "BRANCH_ID" => 'IHTVN1',
                        "INPUT_USER" => 'JENNY',
                        "INPUT_DT" => date("YmdHis")
                    ]
                );
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

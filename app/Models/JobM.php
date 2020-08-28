<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JobM extends Model
{
    public static function list()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $data = DB::table('JOB_START as js')
            ->leftjoin('JOB_ORDER_M as jm', 'js.JOB_NO', '=', 'jm.JOB_NO')
            ->leftjoin('CUSTOMER as c', 'jm.CUST_NO', '=', 'c.CUST_NO')
            ->select('c.CUST_NAME', 'jm.*')
            ->where('jm.BRANCH_ID', 'IHTVN1')
            ->orderBy('jm.JOB_NO', 'desc')
            ->take(1000)
            ->get();
        return $data;
    }
    public static function search($type, $value)
    {
        $a = DB::table('JOB_ORDER_M as jom')
            ->orderBy('jom.JOB_NO', 'desc')
            ->leftjoin('CUSTOMER as c', 'jom.CUST_NO', '=', 'c.CUST_NO')
            ->where('jom.BRANCH_ID', 'IHTVN1');

        if ($type == '1') { //jobno
            $a->where('jom.JOB_NO', 'LIKE', '%' . $value . '%');
        } elseif ($type == '2') { //bill no
            $a->where('jom.BILL_NO', 'LIKE', '%' . $value . '%');
        } elseif ($type == '3') { //note
            $a->where('jom.NOTE', 'LIKE', '%' . $value . '%');
        } elseif ($type == '4') { //nhan vien chung tu
            $a->where('jom.NV_CHUNGTU', 'LIKE', '%' . $value . '%');
        } elseif ($type == '5') { //ten khach hang
            $a->where('c.CUST_NAME', 'LIKE', '%' . $value . '%');
        }
        $data = $a->select('c.CUST_NAME', 'jom.*')
            ->take(1000)
            ->get();
        return $data;
    }
    public static function des($id)
    {
        $data = DB::table('JOB_START as js')
            ->rightjoin('JOB_ORDER_M as jm', 'js.JOB_NO', '=', 'jm.JOB_NO')
            ->leftjoin('CUSTOMER as c', 'jm.CUST_NO', '=', 'c.CUST_NO')
            ->where('jm.JOB_NO', $id)
            ->where('jm.BRANCH_ID', 'IHTVN1')
            ->select('c.CUST_NAME', 'jm.*')
            ->first();
        return $data;
    }
    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');

            DB::table(config('constants.JOB_M_TABLE'))
                ->insert(
                    [
                        'JOB_NO' => $request['JOB_NO'],
                        'ORDER_DATE' => date("Ymd"),
                        'CUST_NO' => $request['CUST_NO'] != 'undefined' ? $request['CUST_NO'] : '',
                        'CONSIGNEE' => $request['CONSIGNEE'] != 'undefined' ? $request['CONSIGNEE'] : '',
                        'SHIPPER' => $request['SHIPPER'] != 'undefined' ? $request['SHIPPER'] : '',
                        'ORDER_FROM' =>  $request['ORDER_FROM'] != 'undefined' ? $request['ORDER_FROM'] : '',
                        'ORDER_TO' =>  $request['ORDER_TO'] != 'undefined' ? $request['ORDER_TO'] : '',
                        'CONTAINER_NO' =>  $request['CONTAINER_NO'] != 'undefined' ? $request['CONTAINER_NO'] : '',
                        'CONTAINER_QTY' =>  $request['CONTAINER_QTY'] != 'undefined' ? $request['CONTAINER_QTY'] : '',
                        'CUSTOMS_NO' =>  $request['CUSTOMS_NO'] != 'undefined' ? $request['CUSTOMS_NO'] : '',
                        'CUST_NO2' =>  $request['CUST_NO2'] != 'undefined' ? $request['CUST_NO2'] : '',
                        'CUST_NO3' =>  $request['CUST_NO3'] != 'undefined' ? $request['CUST_NO3'] : '',
                        'CUSTOMS_DATE' =>  $request['CUSTOMS_DATE'] != 'undefined' ? date('Ymd', strtotime($request['CUSTOMS_DATE'])) : '',
                        'BILL_NO' =>  $request['BILL_NO'] != 'undefined' ? $request['BILL_NO'] : '',
                        'NW' =>  $request['NW'] != 'undefined' ? $request['NW'] : '',
                        'GW' =>  $request['GW'] != 'undefined' ? $request['GW'] : '',
                        'POL' =>  $request['POL'] != 'undefined' ? $request['POL'] : '',
                        'POD' =>  $request['POD'] != 'undefined' ? $request['POD'] : '',
                        'ETD_ETA' =>  $request['ETD_ETA'] != 'undefined' ? date('Ymd', strtotime($request['ETD_ETA'])) : '',
                        'PO_NO' =>  $request['PO_NO'] != 'undefined' ? $request['PO_NO'] : '',
                        'INVOICE_NO' =>  $request['INVOICE_NO'] != 'undefined' ? $request['INVOICE_NO'] : '',
                        'NOTE' =>  $request['NOTE'] != 'undefined' ? $request['NOTE'] : '',
                        'CHK_MK' =>  "N",
                        'INPUT_USER' =>  $request['INPUT_USER'] != 'undefined' ? $request['INPUT_USER'] : '',
                        'INPUT_DT' =>  date("YmdHis"),
                        'BRANCH_ID' =>  $request['BRANCH_ID'] != 'undefined' ? $request['BRANCH_ID'] : 'IHTVN1',

                    ]
                );
            $data = JobM::des($request['JOB_NO']);
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function edit($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table(config('constants.JOB_M_TABLE'))
                ->where('JOB_NO', $request['JOB_NO'])
                ->update(
                    [
                        'CONSIGNEE' => $request['CONSIGNEE'] != 'undefined' ? $request['CONSIGNEE'] : '',
                        'SHIPPER' => $request['SHIPPER'] != 'undefined' ? $request['SHIPPER'] : '',
                        'ORDER_FROM' =>  $request['ORDER_FROM'] != 'undefined' ? $request['ORDER_FROM'] : '',
                        'ORDER_TO' =>  $request['ORDER_TO'] != 'undefined' ? $request['ORDER_TO'] : '',
                        'CONTAINER_NO' =>  $request['CONTAINER_NO'] != 'undefined' ? $request['CONTAINER_NO'] : '',
                        'CONTAINER_QTY' =>  $request['CONTAINER_QTY'] != 'undefined' ? $request['CONTAINER_QTY'] : '',
                        'CUSTOMS_NO' =>  $request['CUSTOMS_NO'] != 'undefined' ? $request['CUSTOMS_NO'] : '',
                        'CUST_NO2' =>  $request['CUST_NO2'] != 'undefined' ? $request['CUST_NO2'] : '',
                        'CUST_NO3' =>  $request['CUST_NO3'] != 'undefined' ? $request['CUST_NO3'] : '',
                        'CUSTOMS_DATE' =>  $request['CUSTOMS_DATE'] != 'undefined' ? date('Ymd', strtotime($request['CUSTOMS_DATE'])) : '',
                        'BILL_NO' =>  $request['BILL_NO'] != 'undefined' ? $request['BILL_NO'] : '',
                        'NW' =>  $request['NW'] != 'undefined' ? $request['NW'] : '',
                        'GW' =>  $request['GW'] != 'undefined' ? $request['GW'] : '',
                        'POL' =>  $request['POL'] != 'undefined' ? $request['POL'] : '',
                        'POD' =>  $request['POD'] != 'undefined' ? $request['POD'] : '',
                        'ETD_ETA' =>  $request['ETD_ETA'] != 'undefined' ? date('Ymd', strtotime($request['ETD_ETA'])) : '',
                        'PO_NO' =>  $request['PO_NO'] != 'undefined' ? $request['PO_NO'] : '',
                        'INVOICE_NO' =>  $request['INVOICE_NO'] != 'undefined' ? $request['INVOICE_NO'] : '',
                        'NOTE' =>  $request['NOTE'] != 'undefined' ? $request['NOTE'] : '',
                        'MODIFY_USER' =>  $request['MODIFY_USER'] != 'undefined' ? $request['MODIFY_USER'] : '',
                        'MODIFY_DT' =>  date("YmdHis"),
                        'BRANCH_ID' =>  $request['BRANCH_ID'] != 'undefined' ? $request['BRANCH_ID'] : 'IHTVN1',

                    ]
                );
            $data = JobM::des($request['JOB_NO']);
            return $data;
        } catch (\Exception $e) {
            return '201';
        }
    }
    public static function removeCheck($id)
    {
        try {
            $exist = DB::table('JOB_ORDER_M as jm')
                ->leftjoin('JOB_ORDER_D as jd', 'jm.JOB_NO', '=', 'jd.JOB_NO')
                ->where('jm.JOB_NO', $id)
                ->count();
            if ($exist == 0) {
                //co the xoa
                return '200';
            } else {
                //khong the xoa
                return '201';
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function remove($request)
    {
        try {
            DB::table(config('constants.JOB_M_TABLE'))
                ->where('JOB_NO', $request['JOB_NO'])
                ->delete();
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    //3. duyet cuoc job
    public static function approved($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table(config('constants.JOB_M_TABLE'))
                ->where('JOB_NO', $request['JOB_NO'])
                ->update([
                    'CHK_MK' => "Y",
                    'CHK_DATE' =>  date("Ymd"),
                ]);
            $data = JobM::des($request['JOB_NO']);
            return $data;
        } catch (\Exception $e) {
            return '201';
        }
    }
    public static function listPending()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $data = DB::table('JOB_START as js')
            ->leftjoin('JOB_ORDER_M as jm', 'js.JOB_NO', '=', 'jm.JOB_NO')
            ->leftJoin('CUSTOMER as c','jm.CUST_NO','c.CUST_NO')
            ->where('jm.CHK_MK', '!=', 'Y')
            ->select('c.CUST_NAME','jm.*')
            ->orderBy('jm.JOB_NO', 'desc')
            ->take(9000)
            ->get();
        return $data;
    }
    public static function listApproved()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $data = DB::table('JOB_START as js')
            ->leftjoin('JOB_ORDER_M as jm', 'js.JOB_NO', '=', 'jm.JOB_NO')
            ->leftJoin('CUSTOMER as c','jm.CUST_NO','c.CUST_NO')
            ->where('jm.CHK_MK', 'Y')
            ->select('c.CUST_NAME','jm.*')
            ->orderBy('jm.JOB_NO', 'desc')
            ->take(9000)
            ->get();
        return $data;
    }
}

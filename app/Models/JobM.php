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
            ->select('jm.*')
            ->orderBy('jm.JOB_NO', 'desc')
            ->take(1000)
            ->get();
        return $data;
    }

    public static function des($id)
    {
        $data = DB::table('JOB_START as js')
            ->rightjoin('JOB_ORDER_M as jm', 'js.JOB_NO', '=', 'jm.JOB_NO')
            ->where('jm.JOB_NO', $id)
            ->select('jm.*')
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
                        'ORDER_DATE' => $request['ORDER_DATE'],
                        'CUST_NO' => $request['CUST_NO'],
                        'CONSIGNEE' => $request['CONSIGNEE'],
                        'SHIPPER' => $request['SHIPPER'],
                        'ORDER_FROM' =>  $request['ORDER_FROM'],
                        'ORDER_TO' =>  $request['ORDER_TO'],
                        'CONTAINER_NO' =>  $request['CONTAINER_NO'],
                        'CONTAINER_QTY' =>  $request['CONTAINER_QTY'],
                        'CUSTOMS_NO' =>  $request['CUSTOMS_NO'],
                        'CUSTOMS_DATE' =>  $request['CUSTOMS_DATE'],
                        'BILL_NO' =>  $request['BILL_NO'],
                        'NW' =>  $request['NW'],
                        'GW' =>  $request['GW'],
                        'POL' =>  $request['POL'],
                        'POD' =>  $request['POD'],
                        'ETD_ETA' =>  $request['ETD_ETA'],
                        'PO_NO' =>  $request['PO_NO'],
                        'INVOICE_NO' =>  $request['INVOICE_NO'],
                        'NOTE' =>  $request['NOTE'],
                        'CHK_MK' =>  "N",
                        'INPUT_USER' =>  $request['INPUT_USER'],
                        'INPUT_DT' =>  date("YmdHis"),
                        'BRANCH_ID' =>  $request['BRANCH_ID'],
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
                        'CONSIGNEE' => $request['CONSIGNEE'],
                        'SHIPPER' => $request['SHIPPER'],
                        'ORDER_FROM' =>  $request['ORDER_FROM'],
                        'ORDER_TO' =>  $request['ORDER_TO'],
                        'CONTAINER_NO' =>  $request['CONTAINER_NO'],
                        'CONTAINER_QTY' =>  $request['CONTAINER_QTY'],
                        'CUSTOMS_NO' =>  $request['CUSTOMS_NO'],
                        'CUSTOMS_DATE' =>  $request['CUSTOMS_DATE'],
                        'BILL_NO' =>  $request['BILL_NO'],
                        'NW' =>  $request['NW'],
                        'GW' =>  $request['GW'],
                        'POL' =>  $request['POL'],
                        'POD' =>  $request['POD'],
                        'ETD_ETA' =>  $request['ETD_ETA'],
                        'PO_NO' =>  $request['PO_NO'],
                        'INVOICE_NO' =>  $request['INVOICE_NO'],
                        'NOTE' =>  $request['NOTE'],
                        'MODIFY_USER' =>  $request['MODIFY_USER'],
                        'MODIFY_DT' =>  date("YmdHis"),
                        'BRANCH_ID' =>  $request['BRANCH_ID'],

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
            ->where('jm.CHK_MK', '!=', 'Y')
            ->select('jm.*')
            ->orderBy('jm.JOB_NO', 'desc')
            ->take(1000)
            ->get();
        return $data;
    }
    public static function listApproved()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $data = DB::table('JOB_START as js')
            ->leftjoin('JOB_ORDER_M as jm', 'js.JOB_NO', '=', 'jm.JOB_NO')
            ->where('jm.CHK_MK', 'Y')
            ->select('jm.*')
            ->orderBy('jm.JOB_NO', 'desc')
            ->take(1000)
            ->get();
        return $data;
    }
}

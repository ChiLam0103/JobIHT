<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JobStart extends Model
{
    public static function list()
    {
        $data = DB::table('JOB_START as js')
            ->orderBy('js.JOB_NO', 'desc')
            ->leftjoin('CUSTOMER as c', 'js.CUST_NO', '=', 'c.CUST_NO')
            ->select('c.CUST_NAME','js.*')
            ->take(1000)
            ->get();
        return $data;
    }
    public static function search($type,$value)
    {
        $a = DB::table('JOB_START as js')
            ->orderBy('js.JOB_NO', 'desc')
            ->leftjoin('CUSTOMER as c', 'js.CUST_NO', '=', 'c.CUST_NO');

            if($type=='1'){//jobno
                $a->where('js.JOB_NO','LIKE','%'.$value.'%');
            }
            elseif ($type=='2') {//bill no
                $a->where('js.BILL_NO','LIKE','%'.$value.'%');
            }
            elseif ($type=='3') {//note
                $a->where('js.NOTE','LIKE','%'.$value.'%');
            }
            elseif ($type=='4') {//nhan vien chung tu
                $a->where('js.NV_CHUNGTU','LIKE','%'.$value.'%');
            }
            elseif ($type=='5') {//ten khach hang
                $a->where('c.CUST_NAME','LIKE','%'.$value.'%');
            }
            $data=$a->select('c.CUST_NAME','js.*')
            ->take(1000)
            ->get();
        return $data;
    }
    public static function listNotCreatedOrder()
    {
        $data = DB::table('JOB_START as js')
            ->leftJoin('JOB_ORDER_M as jm', 'js.JOB_NO', '=', 'jm.JOB_NO')
            ->whereNull('jm.JOB_NO')
            ->orderBy('js.JOB_NO', 'desc')
            ->select('js.*')
            ->take(100)
            ->get();
        return $data;
    }
    public static function des($id)
    {
        $data = DB::table('JOB_START as js')
        ->leftjoin('CUSTOMER as c', 'js.CUST_NO', '=', 'c.CUST_NO')
        ->select('c.CUST_NAME','js.*')
        ->where('js.JOB_NO', $id)
        ->first();
        return $data;
    }
    public static function generateJobNo()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $countRecordToday = DB::table(config('constants.JOB_START_TABLE'))->where('JOB_DATE', date("Ymd"))->count();
        $countRecordToday = (int) $countRecordToday + 1;
        do {
            $job_no = sprintf("J%s%'.03d", date('ymd') . '-', $countRecordToday);
            $countRecordToday++;
        } while (DB::table(config('constants.JOB_START_TABLE'))->where('JOB_NO', $job_no)->first());
        return $job_no;
    }

    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $job_no = JobStart::generateJobNo();
            DB::table(config('constants.JOB_START_TABLE'))
                ->insert(
                    [
                        'JOB_NO' => $job_no,
                        'CUST_NO' => $request['CUST_NO'],
                        'NV_CHUNGTU' => $request['NV_CHUNGTU'],
                        'NV_GIAONHAN' => $request['NV_GIAONHAN'],
                        'JOB_DATE' => date("Ymd"),
                        'NOTE' =>  $request['NOTE'],
                        'INPUT_USER' =>  $request['INPUT_USER'],
                        'INPUT_DT' =>  date("YmdHis"),
                        'CUST_NO2' =>  $request['CUST_NO2'],
                        'CUST_NO3' =>  $request['CUST_NO3'],
                        'CUST_NO4' =>  $request['CUST_NO4'],
                        'CUST_NO5' =>  $request['CUST_NO5'],
                        'ORDER_FROM' =>  $request['ORDER_FROM'],
                        'ORDER_TO' =>  $request['ORDER_TO'],
                        'CONTAINER_QTY' =>  $request['CONTAINER_QTY'],
                        'POL' =>  $request['POL'],
                        'POD' =>  $request['POD'],
                        // 'ETA_ETD' =>  $request['ETA_ETD'],
                        'BRANCH_ID' =>  $request['BRANCH_ID'],
                        'CONTAINER_NO' =>  $request['CONTAINER_NO'],
                        'CUSTOMS_NO' =>  $request['CUSTOMS_NO'],
                        'CUSTOMS_DATE' =>   date('Ymd', strtotime($request['CUSTOMS_DATE'])),
                        'BILL_NO' =>  $request['BILL_NO'],
                        'NW' =>  $request['NW'],
                        'GW' =>  $request['GW'],
                        'INVOICE_NO' =>  $request['INVOICE_NO'],
                        'JOB_CAM_NO' =>  $request['JOB_CAM_NO'],
                    ]
                );
            $data = JobStart::des($job_no);
            return $data;
        } catch (\Exception $e) {
            return '201';
        }
    }
    public static function edit($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table(config('constants.JOB_START_TABLE'))
                ->where('JOB_NO', $request['JOB_NO'])
                ->update(
                    [
                        'CUST_NO' => $request['CUST_NO'],
                        'NV_CHUNGTU' => $request['NV_CHUNGTU'],
                        'NV_GIAONHAN' => $request['NV_GIAONHAN'],
                        'NOTE' =>  $request['NOTE'],
                        'MODIFY_USER' =>  $request['MODIFY_USER'],
                        'MODIFY_DT' =>  date("YmdHis"),
                        'CUST_NO2' =>  $request['CUST_NO2'],
                        'CUST_NO3' =>  $request['CUST_NO3'],
                        'CUST_NO4' =>  $request['CUST_NO4'],
                        'CUST_NO5' =>  $request['CUST_NO5'],
                        'ORDER_FROM' =>  $request['ORDER_FROM'],
                        'ORDER_TO' =>  $request['ORDER_TO'],
                        'CONTAINER_QTY' =>  $request['CONTAINER_QTY'],
                        'POL' =>  $request['POL'],
                        'POD' =>  $request['POD'],
                        'ETA_ETD' =>  date("Ymd", strtotime($request['ETA_ETD'])),
                        'BRANCH_ID' =>  $request['BRANCH_ID'],
                        'CONTAINER_NO' =>  $request['CONTAINER_NO'],
                        'CUSTOMS_NO' =>  $request['CUSTOMS_NO'],
                        'CUSTOMS_DATE' =>   date('Ymd', strtotime($request['CUSTOMS_DATE'])),
                        'BILL_NO' =>  $request['BILL_NO'],
                        'NW' =>  $request['NW'],
                        'GW' =>  $request['GW'],
                        'INVOICE_NO' =>  $request['INVOICE_NO'],
                        'JOB_CAM_NO' =>  $request['JOB_CAM_NO'],
                    ]
                );
            $data = JobStart::des($request['JOB_NO']);
            return $data;
        } catch (\Exception $e) {
            return '201';
        }
    }

    public static function remove($request)
    {
        try {
            DB::table(config('constants.JOB_START_TABLE'))
                ->where('JOB_NO', $request['JOB_NO'])
                ->delete();
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function removeCheck($id)
    {
        try {
            $exist = DB::table('JOB_START as js')
                ->leftjoin('JOB_ORDER_M as jm', 'js.JOB_NO', '=', 'jm.JOB_NO')
                ->where('js.JOB_NO', $id)
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

}

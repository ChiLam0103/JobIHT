<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JobStart extends Model
{
    public static function query()
    {
        $query = DB::table('JOB_START as js')
            ->orderBy('js.JOB_NO', 'desc')
            ->leftjoin('CUSTOMER as c', 'js.CUST_NO', '=', 'c.CUST_NO')
            ->where(function ($query) {
                $query->where('js.DEL', 'N')
                    ->orWhere('js.DEL', null);
            })
            ->where('js.BRANCH_ID', 'IHTVN1')
            ->where('c.BRANCH_ID', 'IHTVN1')
            ->where('js.JOB_DATE','>=','20190101');
        return $query;
    }
    public static function listPage($page)
    {
        try {
            $take = 10;
            $skip = ($page - 1) * $take;
            $query =  JobStart::query();
            $count = $query->count();
            $data =  $query->skip($skip)
                ->take($take)
                ->select('c.CUST_NAME', 'js.*')
                ->get();
            return ['total_page' => $count, 'list_job' => $data];
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    public static function list()
    {
        try {
            // $take = 9000;
            $query = JobStart::query();
            $data =  $query
                // ->take($take)
                ->select('c.CUST_NAME', 'js.*')
                ->get();
            return $data;
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    public static function listTake($take)
    {
        try {
            $take = $take;
            $query = JobStart::query();
            $data =  $query
                ->take($take)
                ->select('c.CUST_NAME', 'js.JOB_NO')
                ->get();
            return $data;
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    public static function search($type, $value, $page)
    {
        $take = 10;
        $skip = ($page - 1) * $take;
        $query =  JobStart::query();
        switch ($type) {
            case 'job_no':
                $query->where('js.JOB_NO', 'LIKE', '%' . $value . '%');
                break;
            case 'cust_no':
                $query->where('c.CUST_NO', 'LIKE', '%' . $value . '%');
                break;
            case  'cust_name':
                $query->where('c.CUST_NAME', 'LIKE', '%' . $value . '%');
                break;
            case  'document_staff': //nhan vien chung tu
                $query->where('js.NV_CHUNGTU', 'LIKE', '%' . $value . '%');
                break;
            case  'container_no':
                $query->where('js.CONTAINER_NO', 'LIKE', '%' . $value . '%');
                break;
            case  'note':
                $query->where('js.NOTE', 'LIKE', '%' . $value . '%');
                break;
            case  'bill_no':
                $query->where('js.BILL_NO', 'LIKE', '%' . $value . '%');
                break;

            default:
                break;
        }
        $count = (int)($query->count());
        $data =  $query->skip($skip)
            ->take($take)
            ->select('c.CUST_NAME', 'js.JOB_NO')
            ->get();
        return ['total_page' => $count, 'list' => $data];
    }
    public static function listNotCreatedOrder()
    {
        $query = JobStart::query();
        $take = 5000;
        $data = $query->leftJoin('JOB_ORDER_M as jm', 'js.JOB_NO', '=', 'jm.JOB_NO')
            ->whereNull('jm.JOB_NO')
            ->select('c.CUST_NAME', 'js.JOB_NO')
            ->take($take)
            ->get();
        return $data;
    }
    public static function des($id)
    {
        $query = JobStart::query();
        $data = $query->select('c.CUST_NAME','js.ETA_ETD as ETD_ETA','js.*')
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
            if ($request['CUST_NO'] != 'undefined' || $request['CUST_NO'] != null || $request['CUST_NO'] != '') {
                $job =   DB::table(config('constants.JOB_START_TABLE'))
                    ->insert(
                        [
                            'JOB_NO' => $job_no,
                            'JOB_DATE' => date("Ymd"),
                            'CUST_NO' => ($request['CUST_NO'] == 'undefined' || $request['CUST_NO'] == 'null' || $request['CUST_NO'] == null) ? '' : $request['CUST_NO'],
                            'NV_CHUNGTU' => ($request['NV_CHUNGTU'] == 'undefined' || $request['NV_CHUNGTU'] == 'null' || $request['NV_CHUNGTU'] == null) ? '' : $request['NV_CHUNGTU'],
                            'NV_GIAONHAN' => ($request['NV_GIAONHAN'] == 'undefined' || $request['NV_GIAONHAN'] == 'null' || $request['NV_GIAONHAN'] == null) ? '' : $request['NV_GIAONHAN'],
                            'ORDER_FROM' => ($request['ORDER_FROM'] == 'undefined' || $request['ORDER_FROM'] == 'null' || $request['ORDER_FROM'] == null) ? '' : $request['ORDER_FROM'],
                            'ORDER_TO' => ($request['ORDER_TO'] == 'undefined' || $request['ORDER_TO'] == 'null' || $request['ORDER_TO'] == null) ? '' : $request['ORDER_TO'],
                            'CONTAINER_QTY' => ($request['CONTAINER_QTY'] == 'undefined' || $request['CONTAINER_QTY'] == 'null' || $request['CONTAINER_QTY'] == null) ? '' : $request['CONTAINER_QTY'],
                            'QTY' => ($request['QTY'] == 'undefined' || $request['QTY'] == 'null' || $request['QTY'] == null) ? '' : $request['QTY'],
                            'ETA_ETD' => ($request['ETA_ETD'] == 'undefined' || $request['ETA_ETD'] == 'null' || $request['ETA_ETD'] == null) ? null : date("Ymd", strtotime($request['ETA_ETD'])),
                            'NW' => ($request['NW'] == 'undefined' || $request['NW'] == 'null' || $request['NW'] == null) ? '' : $request['NW'],
                            'GW' => ($request['GW'] == 'undefined' || $request['GW'] == 'null' || $request['GW'] == null) ? '' : $request['GW'],
                            'JOB_CAM_NO' => ($request['JOB_CAM_NO'] == 'undefined' || $request['JOB_CAM_NO'] == 'null' || $request['JOB_CAM_NO'] == null) ? '' : $request['JOB_CAM_NO'],
                            'CONTAINER_NO' => ($request['CONTAINER_NO'] == 'undefined' || $request['CONTAINER_NO'] == 'null' || $request['CONTAINER_NO'] == null) ? '' : $request['CONTAINER_NO'],
                            'CUSTOMS_NO' => ($request['CUSTOMS_NO'] == 'undefined' || $request['CUSTOMS_NO'] == 'null' || $request['CUSTOMS_NO'] == null) ? '' : $request['CUSTOMS_NO'],
                            'CUSTOMS_DATE' => ($request['CUSTOMS_DATE'] == 'undefined' || $request['CUSTOMS_DATE'] == 'null' || $request['CUSTOMS_DATE'] == null) ? null : date('Ymd', strtotime($request['CUSTOMS_DATE'])),
                            'BILL_NO' => ($request['BILL_NO'] == 'undefined' || $request['BILL_NO'] == 'null' || $request['BILL_NO'] == null) ? '' : $request['BILL_NO'],
                            'INVOICE_NO' => ($request['INVOICE_NO'] == 'undefined' || $request['INVOICE_NO'] == 'null' || $request['INVOICE_NO'] == null) ? '' : $request['INVOICE_NO'],
                            'NOTE' => ($request['NOTE'] == 'undefined' || $request['NOTE'] == 'null' || $request['NOTE'] == null) ? '' : $request['NOTE'],
                            'POL' => ($request['POL'] == 'undefined' || $request['POL'] == 'null' || $request['POL'] == null) ? '' : $request['POL'],
                            'POD' => ($request['POD'] == 'undefined' || $request['POD'] == 'null' || $request['POD'] == null) ? '' : $request['POD'],
                            'BRANCH_ID' => ($request['BRANCH_ID'] == 'undefined' || $request['BRANCH_ID'] == 'null' || $request['BRANCH_ID'] == null) ? 'IHTVN1' : $request['BRANCH_ID'],
                            'INPUT_USER' =>  ($request['INPUT_USER'] == 'undefined' || $request['INPUT_USER'] == 'null' || $request['INPUT_USER'] == null) ? 'LAM' : $request['INPUT_USER'],
                            'INPUT_DT' =>  date("YmdHis"),
                        ]
                    );
                // dd($job);
                $data = JobStart::des($job_no);
                return $data;
            } else {
                return '202';
            }
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
                        'CUST_NO' => ($request['CUST_NO'] == 'undefined' || $request['CUST_NO'] == 'null' || $request['CUST_NO'] == null) ? '' : $request['CUST_NO'],
                        'NV_CHUNGTU' => ($request['NV_CHUNGTU'] == 'undefined' || $request['NV_CHUNGTU'] == 'null' || $request['NV_CHUNGTU'] == null) ? '' : $request['NV_CHUNGTU'],
                        'NV_GIAONHAN' => ($request['NV_GIAONHAN'] == 'undefined' || $request['NV_GIAONHAN'] == 'null' || $request['NV_GIAONHAN'] == null) ? '' : $request['NV_GIAONHAN'],
                        'ORDER_FROM' => ($request['ORDER_FROM'] == 'undefined' || $request['ORDER_FROM'] == 'null' || $request['ORDER_FROM'] == null) ? '' : $request['ORDER_FROM'],
                        'ORDER_TO' => ($request['ORDER_TO'] == 'undefined' || $request['ORDER_TO'] == 'null' || $request['ORDER_TO'] == null) ? '' : $request['ORDER_TO'],
                        'CONTAINER_QTY' => ($request['CONTAINER_QTY'] == 'undefined' || $request['CONTAINER_QTY'] == 'null' || $request['CONTAINER_QTY'] == null) ? '' : $request['CONTAINER_QTY'],
                        'QTY' => ($request['QTY'] == 'undefined' || $request['QTY'] == 'null' || $request['QTY'] == null) ? '' : $request['QTY'],
                        'ETA_ETD' => ($request['ETA_ETD'] == 'undefined' || $request['ETA_ETD'] == 'null' || $request['ETA_ETD'] == null) ? null : date("Ymd", strtotime($request['ETA_ETD'])),
                        'NW' => ($request['NW'] == 'undefined' || $request['NW'] == 'null' || $request['NW'] == null) ? '' : $request['NW'],
                        'GW' => ($request['GW'] == 'undefined' || $request['GW'] == 'null' || $request['GW'] == null) ? '' : $request['GW'],
                        'JOB_CAM_NO' => ($request['JOB_CAM_NO'] == 'undefined' || $request['JOB_CAM_NO'] == 'null' || $request['JOB_CAM_NO'] == null) ? '' : $request['JOB_CAM_NO'],
                        'CONTAINER_NO' => ($request['CONTAINER_NO'] == 'undefined' || $request['CONTAINER_NO'] == 'null' || $request['CONTAINER_NO'] == null) ? '' : $request['CONTAINER_NO'],
                        'CUSTOMS_NO' => ($request['CUSTOMS_NO'] == 'undefined' || $request['CUSTOMS_NO'] == 'null' || $request['CUSTOMS_NO'] == null) ? '' : $request['CUSTOMS_NO'],
                        'CUSTOMS_DATE' => ($request['CUSTOMS_DATE'] == 'undefined' || $request['CUSTOMS_DATE'] == 'null' || $request['CUSTOMS_DATE'] == null) ? null : date('Ymd', strtotime($request['CUSTOMS_DATE'])),
                        'BILL_NO' => ($request['BILL_NO'] == 'undefined' || $request['BILL_NO'] == 'null' || $request['BILL_NO'] == null) ? '' : $request['BILL_NO'],
                        'INVOICE_NO' => ($request['INVOICE_NO'] == 'undefined' || $request['INVOICE_NO'] == 'null' || $request['INVOICE_NO'] == null) ? '' : $request['INVOICE_NO'],
                        'NOTE' => ($request['NOTE'] == 'undefined' || $request['NOTE'] == 'null' || $request['NOTE'] == null) ? '' : $request['NOTE'],
                        'POL' => ($request['POL'] == 'undefined' || $request['POL'] == 'null' || $request['POL'] == null) ? '' : $request['POL'],
                        'POD' => ($request['POD'] == 'undefined' || $request['POD'] == 'null' || $request['POD'] == null) ? '' : $request['POD'],
                        'MODIFY_USER' => ($request['MODIFY_USER'] == 'undefined' || $request['MODIFY_USER'] == 'null' || $request['MODIFY_USER'] == null) ? '' : $request['MODIFY_USER'],
                        'MODIFY_DT' =>  date("YmdHis"),
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
            $title = 'Đã xóa ' . $request['JOB_NO'] . ' thành công';
            DB::table(config('constants.JOB_START_TABLE'))
                ->where('JOB_NO', $request['JOB_NO'])
                ->delete();
            return $title;
        } catch (\Exception $e) {
            return $e;
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JobM extends Model
{
    public static function query()
    {
        $query = DB::table('JOB_ORDER_M as jm')
            ->leftjoin('CUSTOMER as c', 'jm.CUST_NO', '=', 'c.CUST_NO')
            ->orderBy('jm.JOB_NO', 'desc')
            ->where(function ($query) {
                $query->where('jm.DEL', 'N')
                    ->orWhere('jm.DEL', null);
            })
            ->where('jm.BRANCH_ID', 'IHTVN1')
            ->where('c.BRANCH_ID', 'IHTVN1')
            ->where('jm.ORDER_DATE', '>=', '20190101');
        return $query;
    }
    public static function list()
    {
        try {
            $take = 5000;
            $query =  JobM::query();
            $data =  $query
                ->take($take)
                ->select('c.CUST_NAME', 'jm.JOB_NO')
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
            $query =  JobM::query();
            $data =  $query
                ->take($take)
                ->select('c.CUST_NAME', 'jm.*')
                ->get();
            return $data;
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    public static function listPage($page)
    {
        try {
            $take = 10;
            $skip = ($page - 1) * $take;
            $query =  JobM::query();
            $count = (int)($query->count());
            $data =  $query->skip($skip)
                ->take($take)
                ->select('c.CUST_NAME', 'jm.*')
                ->get();
            return ['total_page' => $count, 'list_job' => $data];
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    public static function searchPage($type, $value, $page)
    {
        $take = 10;
        $skip = ($page - 1) * $take;
        $query =  JobM::query();
        switch ($type) {
            case 'job_no':
                $query->where('jm.JOB_NO', 'LIKE', '%' . $value . '%');
                break;
            case 'cust_no':
                $query->where('c.CUST_NO', 'LIKE', '%' . $value . '%');
                break;
            case  'cust_name':
                $query->where('c.CUST_NAME', 'LIKE', '%' . $value . '%');
                break;
            case  'customs_no':
                $query->where('jm.CUSTOMS_NO', 'LIKE', '%' . $value . '%');
                break;
            case  'container_no':
                $query->where('jm.CONTAINER_NO', 'LIKE', '%' . $value . '%');
                break;
            case  'note':
                $query->where('jm.NOTE', 'LIKE', '%' . $value . '%');
                break;
            case  'bill_no':
                $query->where('jm.BILL_NO', 'LIKE', '%' . $value . '%');
                break;
            case  'po_no':
                $query->where('jm.PO_NO', 'LIKE', '%' . $value . '%');
                break;
            default:
                break;
        }
        $count = (int)($query->count());
        $data =  $query->select('c.CUST_NAME', 'jm.*')->skip($skip)->take($take)->get();
        return ['total_page' => $count, 'list' => $data];
    }
    public static function search($type, $value)
    {
        $take = 100;
        $query =  JobM::query();
        switch ($type) {
            case 'job_no':
                $query->where('jm.JOB_NO', 'LIKE', '%' . $value . '%');
                break;
            case 'cust_no':
                $query->where('c.CUST_NO', 'LIKE', '%' . $value . '%');
                break;
            case  'cust_name':
                $query->where('c.CUST_NAME', 'LIKE', '%' . $value . '%');
                break;
            case  'customs_no':
                $query->where('jm.CUSTOMS_NO', 'LIKE', '%' . $value . '%');
                break;
            case  'container_no':
                $query->where('jm.CONTAINER_NO', 'LIKE', '%' . $value . '%');
                break;
            case  'note':
                $query->where('jm.NOTE', 'LIKE', '%' . $value . '%');
                break;
            case  'bill_no':
                $query->where('jm.BILL_NO', 'LIKE', '%' . $value . '%');
                break;
            case  'po_no':
                $query->where('jm.PO_NO', 'LIKE', '%' . $value . '%');
                break;
            default:
                break;
        }
        $data =  $query->take($take)->select('c.CUST_NAME', 'jm.*')->get();
        return ['list' => $data];
    }
    public static function des($id)
    {
        $query =  JobM::query();
        $data =  $query->where('jm.JOB_NO', $id)->select('c.CUST_NAME', 'jm.*')->first();
        return $data;
    }
    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            if ($request['CUST_NO'] != 'undefined' || $request['CUST_NO'] != null || $request['CUST_NO'] != '' || $request['CUST_NO']) {
                DB::table(config('constants.JOB_M_TABLE'))
                    ->insert(
                        [
                            'JOB_NO' => $request['JOB_NO'],
                            'ORDER_DATE' => date("Ymd"),
                            'CHK_MK' =>  "N",
                            'CUST_NO' => ($request['CUST_NO'] == 'undefined' || $request['CUST_NO'] == 'null' || $request['CUST_NO'] == null) ? '' : $request['CUST_NO'],
                            'CUST_NO2' => ($request['CUST_NO2'] == 'undefined' || $request['CUST_NO2'] == 'null' || $request['CUST_NO2'] == null) ? '' : $request['CUST_NO2'],
                            'CUST_NO3' => ($request['CUST_NO3'] == 'undefined' || $request['CUST_NO3'] == 'null' || $request['CUST_NO3'] == null) ? '' : $request['CUST_NO3'],
                            'CONSIGNEE' => ($request['CONSIGNEE'] == 'undefined' || $request['CONSIGNEE'] == 'null' || $request['CONSIGNEE'] == null) ? '' : $request['CONSIGNEE'],
                            'SHIPPER' => ($request['SHIPPER'] == 'undefined' || $request['SHIPPER'] == 'null' || $request['SHIPPER'] == null) ? '' : $request['SHIPPER'],
                            'BILL_NO' => ($request['BILL_NO'] == 'undefined' || $request['BILL_NO'] == 'null' || $request['BILL_NO'] == null) ? '' : $request['BILL_NO'],
                            'ORDER_FROM' => ($request['ORDER_FROM'] == 'undefined' || $request['ORDER_FROM'] == 'null' || $request['ORDER_FROM'] == null) ? '' : $request['ORDER_FROM'],
                            'ORDER_TO' => ($request['ORDER_TO'] == 'undefined' || $request['ORDER_TO'] == 'null' || $request['ORDER_TO'] == null) ? '' : $request['ORDER_TO'],
                            'CUSTOMS_NO' => ($request['CUSTOMS_NO'] == 'undefined' || $request['CUSTOMS_NO'] == 'null' || $request['CUSTOMS_NO'] == null) ? '' : $request['CUSTOMS_NO'],
                            'CUSTOMS_DATE' => ($request['CUSTOMS_DATE'] == 'undefined' || $request['CUSTOMS_DATE'] == 'null' || $request['CUSTOMS_DATE'] == null) ? null : date('Ymd', strtotime($request['CUSTOMS_DATE'])),
                            'INVOICE_NO' => ($request['INVOICE_NO'] == 'undefined' || $request['INVOICE_NO'] == 'null' || $request['INVOICE_NO'] == null) ? '' : $request['INVOICE_NO'],
                            'CONTAINER_NO' => ($request['CONTAINER_NO'] == 'undefined' || $request['CONTAINER_NO'] == 'null' || $request['CONTAINER_NO'] == null) ? '' : $request['CONTAINER_NO'],
                            'CONTAINER_QTY' => ($request['CONTAINER_QTY'] == 'undefined' || $request['CONTAINER_QTY'] == 'null' || $request['CONTAINER_QTY'] == null) ? '' : $request['CONTAINER_QTY'],
                            'QTY' => ($request['QTY'] == 'undefined' || $request['QTY'] == 'null' || $request['QTY'] == null) ? '' : $request['QTY'],
                            'NW' => ($request['NW'] == 'undefined' || $request['NW'] == 'null' || $request['NW'] == null) ? '' : $request['NW'],
                            'GW' => ($request['GW'] == 'undefined' || $request['GW'] == 'null' || $request['GW'] == null) ? '' : $request['GW'],
                            'POL' => ($request['POL'] == 'undefined' || $request['POL'] == 'null' || $request['POL'] == null) ? '' : $request['POL'],
                            'POD' => ($request['POD'] == 'undefined' || $request['POD'] == 'null' || $request['POD'] == null) ? '' : $request['POD'],
                            'ETD_ETA' => ($request['ETD_ETA'] == 'undefined' || $request['ETD_ETA'] == 'null' || $request['ETD_ETA'] == null) ? null : date('Ymd', strtotime($request['ETD_ETA'])),
                            // 'ETD_ETA' => ($request['ETA_ETD'] == 'undefined' || $request['ETA_ETD'] == 'null' || $request['ETA_ETD'] == null) ? null : date('Ymd', strtotime($request['ETA_ETD'])),
                            'PO_NO' => ($request['PO_NO'] == 'undefined' || $request['PO_NO'] == 'null' || $request['PO_NO'] == null) ? '' : $request['PO_NO'],
                            'NOTE' => ($request['NOTE'] == 'undefined' || $request['NOTE'] == 'null' || $request['NOTE'] == null) ? '' : $request['NOTE'],
                            'JOB_CAM' => ($request['JOB_CAM'] == 'undefined' || $request['JOB_CAM'] == 'null' || $request['JOB_CAM'] == null) ? '' : $request['JOB_CAM'],
                            'INPUT_USER' => ($request['INPUT_USER'] == 'undefined' || $request['INPUT_USER'] == 'null' || $request['INPUT_USER'] == null) ? '' : $request['INPUT_USER'],
                            'INPUT_DT' =>  date("YmdHis"),
                            'BRANCH_ID' => ($request['BRANCH_ID'] == 'undefined' || $request['BRANCH_ID'] == 'null' || $request['BRANCH_ID'] == null) ?  'IHTVN1' : $request['BRANCH_ID'],

                        ]
                    );
                $data = JobM::des($request['JOB_NO']);
                return $data;
            } else {
                return '202';
            }
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
                        'CUST_NO' => ($request['CUST_NO'] == 'undefined' || $request['CUST_NO'] == 'null' || $request['CUST_NO'] == null) ? '' : $request['CUST_NO'],
                        'CUST_NO2' => ($request['CUST_NO2'] == 'undefined' || $request['CUST_NO2'] == 'null' || $request['CUST_NO2'] == null) ? '' : $request['CUST_NO2'],
                        'CUST_NO3' => ($request['CUST_NO3'] == 'undefined' || $request['CUST_NO3'] == 'null' || $request['CUST_NO3'] == null) ? '' : $request['CUST_NO3'],
                        'CONSIGNEE' => ($request['CONSIGNEE'] == 'undefined' || $request['CONSIGNEE'] == 'null' || $request['CONSIGNEE'] == null) ? '' : $request['CONSIGNEE'],
                        'SHIPPER' => ($request['SHIPPER'] == 'undefined' || $request['SHIPPER'] == 'null' || $request['SHIPPER'] == null) ? '' : $request['SHIPPER'],
                        'BILL_NO' => ($request['BILL_NO'] == 'undefined' || $request['BILL_NO'] == 'null' || $request['BILL_NO'] == null) ? '' : $request['BILL_NO'],
                        'ORDER_FROM' => ($request['ORDER_FROM'] == 'undefined' || $request['ORDER_FROM'] == 'null' || $request['ORDER_FROM'] == null) ? '' : $request['ORDER_FROM'],
                        'ORDER_TO' => ($request['ORDER_TO'] == 'undefined' || $request['ORDER_TO'] == 'null' || $request['ORDER_TO'] == null) ? '' : $request['ORDER_TO'],
                        'CUSTOMS_NO' => ($request['CUSTOMS_NO'] == 'undefined' || $request['CUSTOMS_NO'] == 'null' || $request['CUSTOMS_NO'] == null) ? '' : $request['CUSTOMS_NO'],
                        'CUSTOMS_DATE' => ($request['CUSTOMS_DATE'] == 'undefined' || $request['CUSTOMS_DATE'] == 'null' || $request['CUSTOMS_DATE'] == null) ? null : date('Ymd', strtotime($request['CUSTOMS_DATE'])),
                        'INVOICE_NO' => ($request['INVOICE_NO'] == 'undefined' || $request['INVOICE_NO'] == 'null' || $request['INVOICE_NO'] == null) ? '' : $request['INVOICE_NO'],
                        'CONTAINER_NO' => ($request['CONTAINER_NO'] == 'undefined' || $request['CONTAINER_NO'] == 'null' || $request['CONTAINER_NO'] == null) ? '' : $request['CONTAINER_NO'],
                        'CONTAINER_QTY' => ($request['CONTAINER_QTY'] == 'undefined' || $request['CONTAINER_QTY'] == 'null' || $request['CONTAINER_QTY'] == null) ? '' : $request['CONTAINER_QTY'],
                        'QTY' => ($request['QTY'] == 'undefined' || $request['QTY'] == 'null' || $request['QTY'] == null) ? '' : $request['QTY'],
                        'NW' => ($request['NW'] == 'undefined' || $request['NW'] == 'null' || $request['NW'] == null) ? '' : $request['NW'],
                        'GW' => ($request['GW'] == 'undefined' || $request['GW'] == 'null' || $request['GW'] == null) ? '' : $request['GW'],
                        'POL' => ($request['POL'] == 'undefined' || $request['POL'] == 'null' || $request['POL'] == null) ? '' : $request['POL'],
                        'POD' => ($request['POD'] == 'undefined' || $request['POD'] == 'null' || $request['POD'] == null) ? '' : $request['POD'],
                        'ETD_ETA' => ($request['ETD_ETA'] == 'undefined' || $request['ETD_ETA'] == 'null' || $request['ETD_ETA'] == null) ? null : date('Ymd', strtotime($request['ETD_ETA'])),
                        // 'ETD_ETA' => ($request['ETA_ETD'] == 'undefined' || $request['ETA_ETD'] == 'null' || $request['ETA_ETD'] == null) ? null : date('Ymd', strtotime($request['ETA_ETD'])),
                        'PO_NO' => ($request['PO_NO'] == 'undefined' || $request['PO_NO'] == 'null' || $request['PO_NO'] == null) ? '' : $request['PO_NO'],
                        'NOTE' => ($request['NOTE'] == 'undefined' || $request['NOTE'] == 'null' || $request['NOTE'] == null) ? '' : $request['NOTE'],
                        'JOB_CAM' => ($request['JOB_CAM'] == 'undefined' || $request['JOB_CAM'] == 'null' || $request['JOB_CAM'] == null) ? '' : $request['JOB_CAM'],
                        'MODIFY_USER' => ($request['MODIFY_USER'] == 'undefined' || $request['MODIFY_USER'] == 'null' || $request['MODIFY_USER'] == null) ? '' : $request['MODIFY_USER'],
                        'MODIFY_DT' =>  date("YmdHis"),
                    ]
                );
            $data = JobM::des($request['JOB_NO']);
            return $data;
        } catch (\Exception $e) {
            return '201';
        }
    }
    public static function remove($request)
    {
        try {
            $title = '';
            $query = DB::table('JOB_ORDER_M as jm')->where('jm.JOB_NO', $request['JOB_NO']);
            //check CHK_MK
            $check_chk_mk =  $query->where('jm.CHK_MK', 'Y')->first();
            //check có dữ liệu trong job_order_d
            $check_job_order_d =  $query->leftjoin('JOB_ORDER_D as jd', 'jm.JOB_NO', '=', 'jd.JOB_NO')->select('jd.JOB_NO')->get();
            if ($check_chk_mk == null && count($check_job_order_d) == 0) {
                $title = 'Đã xóa ' . $request['JOB_NO'] . ' thành công';
                DB::table('JOB_ORDER_M')->where('JOB_NO', $request['JOB_NO'])->delete();
                return $title;
            } elseif ($check_chk_mk != null) {
                $title = 'Đơn này đã được duyệt, bạn không thể xóa dữ liệu!';
                return $title;
            } elseif (count($check_job_order_d) != 0) {
                $title = 'Đã có dữ liệu chi tiết, không thể xóa!';
                return $title;
            }
        } catch (\Exception $e) {
            return $e;
        }
    }
    //4. duyet job
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
    public static function listPending($page)
    {
        try {
            $take = 10;
            $skip = ($page - 1) * $take;
            $query =  JobM::query();
            $query->take($take)
                ->where(function ($query) {
                    $query->where('jm.CHK_MK', 'N')
                        ->orWhere('jm.CHK_MK', null);
                })
                ->select('c.CUST_NAME', 'jm.*');
            $count = $query->count();
            $data =  $query->skip($skip)->get();
            return ['total_page' => (int)($count), 'list_job' => $data];
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    public static function listApproved($page)
    {
        try {
            $take = 10;
            $skip = ($page - 1) * $take;
            $query =  JobM::query();
            $query->take($take)
                ->where('jm.CHK_MK', 'Y')
                ->select('c.CUST_NAME', 'jm.*');
            $count = $query->count();
            $data =  $query->skip($skip)->get();
            return ['total_page' => (int)($count), 'list_job' => $data];
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    //5.duyet cuoc cont
    public static function listApprovedCont($type, $list, $year)
    {
        try {
            $year_from = $year . '0101';
            $year_to = $year . '1231';

            $query = DB::table('JOB_ORDER_M as jm')
                ->leftJoin('JOB_ORDER_D as jd', 'jm.JOB_NO', 'jd.JOB_NO')
                ->orderBy('jm.JOB_NO')
                ->where('jm.BRANCH_ID', 'IHTVN1')
                ->where('jd.BRANCH_ID', 'IHTVN1')
                ->where('jm.ORDER_DATE', '>=', '20190101')
                ->whereBetween('jm.ORDER_DATE', [$year_from, $year_to])
                ->where('jd.ORDER_TYPE', $type);
            if ($list == 'pending') {
                $query->where(function ($query) {
                    $query->where('jd.THANH_TOAN_MK', 'N')
                        ->orWhere('jd.THANH_TOAN_MK', null);
                });
            } elseif ($list == 'approved') {
                $query->where('jd.THANH_TOAN_MK', 'Y');
            }

            $data =  $query->select('jd.*')->get();
            return $data;
        } catch (\Exception $ex) {
            return $ex;
        }
    }
    public static function approvedCont($request)
    {
        try {
            if ($request->type == 'pending') {
                foreach ($request->data as $request) {
                    $query = DB::table('JOB_ORDER_D')
                        ->where('ID', $request['ID'])
                        // ->where('ORDER_TYPE', $request['ORDER_TYPE'])
                        // ->where('SER_NO', $request['SER_NO'])
                        ->update(['THANH_TOAN_MK' => 'N']);
                    // $query = DB::table('JOB_ORDER_D')
                    //     ->where('JOB_NO', $request['JOB_NO'])
                    //     ->where('ORDER_TYPE', $request['ORDER_TYPE'])
                    //     ->where('SER_NO', $request['SER_NO'])
                    //     ->update(['THANH_TOAN_MK' => 'N']);
                }
            } else {
                foreach ($request->data as $request) {
                    $query = DB::table('JOB_ORDER_D')
                        // ->where('JOB_NO', $request['JOB_NO'])
                        // ->where('ORDER_TYPE', $request['ORDER_TYPE'])
                        // ->where('SER_NO', $request['SER_NO'])
                        ->where('ID', $request['ID'])
                        ->update(['THANH_TOAN_MK' => 'Y']);
                }
            }
            return '200';
        } catch (\Exception $e) {
            return '201';
        }
    }
}

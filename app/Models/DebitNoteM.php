<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DebitNoteM extends Model
{
    public static function query()
    {
        $query = DB::table('DEBIT_NOTE_M as dnm')
            ->where('dnm.BRANCH_ID', 'IHTVN1')
            ->orderBy('dnm.JOB_NO', 'desc')
            ->where(function ($query) {
                $query->where('dnm.DEL', 'N')
                    ->orWhere('dnm.DEL', null);
            })
            ->where(function ($query) {
                $query->where('dnm.PAYMENT_CHK', 'N')
                    ->orWhere('dnm.PAYMENT_CHK', null);
            });
        return $query;
    }
    public static function queryNotCreated()
    {
        $query = DB::table('JOB_ORDER_M as jom')
            ->leftJoin('DEBIT_NOTE_M as dnm', 'jom.JOB_NO', 'dnm.JOB_NO')
            ->whereNull('dnm.JOB_NO')
            ->select('jom.JOB_NO')
            ->where(function ($query) {
                $query->where('dnm.DEL', 'N')
                    ->orWhere('dnm.DEL', null);
            })
            ->where('jom.BRANCH_ID', 'IHTVN1')
            ->orderBy('jom.JOB_NO', 'desc');
        return $query;
    }
    public static function queryPendingPaid()
    {
        $query = DB::table('DEBIT_NOTE_M as dnm')
            ->leftJoin('CUSTOMER as c', 'c.CUST_NO', 'dnm.CUST_NO')
            ->leftJoin('DEBIT_NOTE_D as dnd', 'dnd.JOB_NO', 'dnm.JOB_NO')
            ->where('dnm.BRANCH_ID', 'IHTVN1')
            ->where('c.BRANCH_ID', 'IHTVN1')
            ->where('dnd.BRANCH_ID', 'IHTVN1')
            ->where(function ($query) {
                $query->where('dnm.DEL', 'N')
                    ->orWhere('dnm.DEL', null);
            })
            ->orderBy('dnm.JOB_NO', 'desc')
            ->select('c.CUST_NAME', 'dnm.JOB_NO', 'dnm.CUST_NO', 'dnm.DEBIT_DATE', 'dnm.TRANS_FROM', 'dnm.TRANS_TO', 'dnm.PAYMENT_DATE')
            ->selectRaw('sum(dnd.QUANTITY * dnd.PRICE + CASE WHEN dnd.TAX_AMT = 0 THEN 0 ELSE (dnd.QUANTITY * dnd.PRICE)/dnd.TAX_NOTE END) as sum_AMT')
            ->groupBy('c.CUST_NAME', 'dnm.JOB_NO', 'dnm.CUST_NO', 'dnm.DEBIT_DATE', 'dnm.TRANS_FROM', 'dnm.TRANS_TO', 'dnm.PAYMENT_DATE');
        return $query;
    }
    public static function queryCheckData()
    {
        $query = DB::table('DEBIT_NOTE_M as dnm')
            ->leftJoin('CUSTOMER as c', 'dnm.CUST_NO', 'c.CUST_NO')
            ->where(function ($query) {
                $query->where('dnm.DEL', 'N')
                    ->orWhere('dnm.DEL', null);
            })
            ->select('dnm.JOB_NO', 'dnm.CUST_NO', 'c.CUST_NAME', 'dnm.TRANS_FROM', 'dnm.TRANS_TO', 'dnm.PAYMENT_CHK', 'dnm.PAYMENT_DATE');
        return $query;
    }
    public static function list()
    {
        $take = 9000;
        $query =  DebitNoteM::query();
        $data =  $query->take($take)->get();
        return $data;
    }
    public static function listPage($page)
    {
        $take = 10;
        $skip = ($page - 1) * $take;
        $query =  DebitNoteM::query();
        $count = $query->count();
        $data =  $query->skip($skip)
            ->take($take)
            ->get();
        return ['total_page' => $count, 'list' => $data];
    }
    public static function search($type, $value, $page)
    {
        $take = 10;
        $skip = ($page - 1) * $take;
        $query =  DebitNoteM::query();
        switch ($type) {
            case 'job_no':
                $query->where('dnm.JOB_NO', 'LIKE', '%' . $value . '%');
                break;
            case 'cust_no':
                $query->where('dnm.CUST_NO', 'LIKE', '%' . $value . '%');
                break;
            case  'container_no':
                $query->where('dnm.CONTAINER_NO', 'LIKE', '%' . $value . '%');
                break;
            case  'customs_no':
                $query->where('dnm.CUSTOMS_NO', 'LIKE', '%' . $value . '%');
                break;
            case  'po_no':
                $query->where('dm.PO_NO', 'LIKE', '%' . $value . '%');
                break;
            default:
                break;
        }
        $count = $query->count();
        $data =  $query->skip($skip)->take($take)->get();
        return ['total_page' => $count, 'list' => $data];
    }
    public static function listNotCreated()
    {
        $query = DebitNoteM::queryNotCreated();
        $data = $query->select('jom.JOB_NO')->get();
        return $data;
    }
    //print
    public static function listCustomer($customer)
    {
        $query =  DebitNoteM::query();
        $data =  $query->leftJoin('CUSTOMER as c', 'c.CUST_NO', 'dnm.CUST_NO')->where('dnm.CUST_NO', $customer)->select('dnm.JOB_NO', 'c.CUST_NAME')->get();
        return $data;
    }
    //print
    public static function listJobHasD()
    {
        $query =  DebitNoteM::query();
        $data =  $query->rightJoin('DEBIT_NOTE_D as dnd', 'dnd.JOB_NO', 'dnm.JOB_NO')->select('dnm.JOB_NO')->take(9000)->distinct()->get();
        return $data;
    }
    public static function desJobNotCreated($id)
    {
        $query = DebitNoteM::queryNotCreated();
        $data = $query->join('CUSTOMER as c', 'c.CUST_NO', 'jom.CUST_NO')->where('jom.JOB_NO', $id)->select('c.CUST_NAME', 'jom.*')->first();
        return $data;
    }
    public static function listPending()
    {
        $take = 5000;
        $query =  DebitNoteM::queryPendingPaid();
        $data =  $query->where(function ($query) {
            $query->where('dnm.PAYMENT_CHK', null)
                ->orWhere('dnm.PAYMENT_CHK', 'N');
        })->take($take)->get();
        return $data;
    }
    public static function listPaid()
    {
        $take = 5000;
        $query =  DebitNoteM::queryPendingPaid();
        $data =  $query->where('dnm.PAYMENT_CHK', 'Y')->take($take)->get();
        return  $data;
    }
    public static function listPendingPage($page)
    {
        $take = 10;
        $skip = ($page - 1) * $take;
        $query =  DebitNoteM::queryPendingPaid();
        $count = $query->where(function ($query) {
            $query->where('dnm.PAYMENT_CHK', null)
                ->orWhere('dnm.PAYMENT_CHK', 'N');
        })->count();

        $data =  $query->skip($skip)->take($take)->get();
        return ['total_page' => $count, 'list' => $data];
    }
    public static function listPaidPage($page)
    {
        $take = 10;
        $skip = ($page - 1) * $take;
        $query =  DebitNoteM::queryPendingPaid();
        $count = $query->where('dnm.PAYMENT_CHK', 'Y')->count();
        $data =  $query->skip($skip)->take($take)->get();
        return ['total_page' => $count, 'list' => $data];
    }
    public static function des($id)
    {
        $query =  DebitNoteM::query();
        $data =  $query->leftJoin('CUSTOMER as c', 'c.CUST_NO', 'dnm.CUST_NO')->where('dnm.JOB_NO', $id)->select('c.CUST_NAME', 'dnm.TRANS_FROM as ORDER_FROM', 'dnm.TRANS_TO as ORDER_TO', 'dnm.*')->first();
        return $data;
    }
    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table(config('constants.DEBIT_NOTE_M_TABLE'))
                ->insert(
                    [
                        'JOB_NO' => ($request['JOB_NO'] == 'undefined' || $request['JOB_NO'] == 'null' || $request['JOB_NO'] == null) ? '' : $request['JOB_NO'],
                        'CUST_NO' => ($request['CUST_NO'] == 'undefined' || $request['CUST_NO'] == 'null' || $request['CUST_NO'] == null) ? '' : $request['CUST_NO'],
                        "CONSIGNEE" => ($request['CONSIGNEE'] == 'undefined' || $request['CONSIGNEE'] == 'null' || $request['CONSIGNEE'] == null) ? '' : $request['CONSIGNEE'],
                        "SHIPPER" => ($request['SHIPPER'] == 'undefined' || $request['SHIPPER'] == 'null' || $request['SHIPPER'] == null) ? '' : $request['SHIPPER'],
                        "TRANS_FROM" => ($request['TRANS_FROM'] == 'undefined' || $request['TRANS_FROM'] == 'null' || $request['TRANS_FROM'] == null) ? '' : $request['TRANS_FROM'],
                        "TRANS_TO" => ($request['TRANS_TO'] == 'undefined' || $request['TRANS_TO'] == 'null' || $request['TRANS_TO'] == null) ? '' : $request['TRANS_TO'],
                        "CONTAINER_NO" => ($request['CONTAINER_NO'] == 'undefined' || $request['CONTAINER_NO'] == 'null' || $request['CONTAINER_NO'] == null) ? '' : $request['CONTAINER_NO'],
                        "CONTAINER_QTY" => ($request['CONTAINER_QTY'] == 'undefined' || $request['CONTAINER_QTY'] == 'null' || $request['CONTAINER_QTY'] == null) ? '' : $request['CONTAINER_QTY'],
                        "QTY" => ($request['QTY'] == 'undefined' || $request['QTY'] == 'null' || $request['QTY'] == null) ? '' : $request['QTY'],
                        "CUSTOMS_NO" => ($request['CUSTOMS_NO'] == 'undefined' || $request['CUSTOMS_NO'] == 'null' || $request['CUSTOMS_NO'] == null) ? '' : $request['CUSTOMS_NO'],
                        "CUSTOMS_DATE" => ($request['CUSTOMS_DATE'] == 'undefined' || $request['CUSTOMS_DATE'] == 'null' || $request['CUSTOMS_DATE'] == null) ? null : date('Ymd', strtotime($request['CUSTOMS_DATE'])),
                        "BILL_NO" => ($request['BILL_NO'] == 'undefined' || $request['BILL_NO'] == 'null' || $request['BILL_NO'] == null) ? '' : $request['BILL_NO'],
                        "NW" => ($request['NW'] == 'undefined' || $request['NW'] == 'null' || $request['NW'] == null) ? '' : $request['NW'],
                        "GW" => ($request['GW'] == 'undefined' || $request['GW'] == 'null' || $request['GW'] == null) ? '' : $request['GW'],
                        "POL" => ($request['POL'] == 'undefined' || $request['POL'] == 'null' || $request['POL'] == null) ? '' : $request['POL'],
                        "POD" => ($request['POD'] == 'undefined' || $request['POD'] == 'null' || $request['POD'] == null) ? '' : $request['POD'],
                        "ETD_ETA" => ($request['ETD_ETA'] == 'undefined' || $request['ETD_ETA'] == 'null' || $request['ETD_ETA'] == null) ? '' : $request['ETD_ETA'],
                        "PO_NO" => ($request['PO_NO'] == 'undefined' || $request['PO_NO'] == 'null' || $request['PO_NO'] == null) ? '' : $request['PO_NO'],
                        "ORDER_NO" => ($request['ORDER_NO'] == 'undefined' || $request['ORDER_NO'] == 'null' || $request['ORDER_NO'] == null) ? '' : $request['ORDER_NO'],
                        "INVOICE_NO" => ($request['INVOICE_NO'] == 'undefined' || $request['INVOICE_NO'] == 'null' || $request['INVOICE_NO'] == null) ? '' : $request['INVOICE_NO'],
                        "NOTE" => ($request['NOTE'] == 'undefined' || $request['NOTE'] == 'null' || $request['NOTE'] == null) ? '' : $request['NOTE'],
                        "CUST_NO2" => ($request['CUST_NO2'] == 'undefined' || $request['CUST_NO2'] == 'null' || $request['CUST_NO2'] == null) ? '' : $request['CUST_NO2'],
                        "CUST_NO3" => ($request['CUST_NO3'] == 'undefined' || $request['CUST_NO3'] == 'null' || $request['CUST_NO3'] == null) ? '' : $request['CUST_NO3'],
                        "CUST_NO4" => ($request['CUST_NO4'] == 'undefined' || $request['CUST_NO4'] == 'null' || $request['CUST_NO4'] == null) ? '' : $request['CUST_NO4'],
                        "CUST_NO5" => ($request['CUST_NO5'] == 'undefined' || $request['CUST_NO5'] == 'null' || $request['CUST_NO5'] == null) ? '' : $request['CUST_NO5'],
                        "DEBIT_DATE" => date("Ymd"),
                        "BRANCH_ID" => ($request['BRANCH_ID'] == 'undefined' || $request['BRANCH_ID'] == 'null' || $request['BRANCH_ID'] == null) ? 'IHTVN1' : $request['BRANCH_ID'],
                        "INPUT_USER" => ($request['INPUT_USER'] == 'undefined' || $request['INPUT_USER'] == 'null' || $request['INPUT_USER'] == null) ? '' : $request['INPUT_USER'],
                        "INPUT_DT" => date("YmdHis"),
                        "PAYMENT_CHK" => 'N',

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
                        "CONSIGNEE" => ($request['CONSIGNEE'] == 'undefined' || $request['CONSIGNEE'] == 'null' || $request['CONSIGNEE'] == null) ? '' : $request['CONSIGNEE'],
                        "SHIPPER" => ($request['SHIPPER'] == 'undefined' || $request['SHIPPER'] == 'null' || $request['SHIPPER'] == null) ? '' : $request['SHIPPER'],
                        "TRANS_FROM" => ($request['TRANS_FROM'] == 'undefined' || $request['TRANS_FROM'] == 'null' || $request['TRANS_FROM'] == null) ? '' : $request['TRANS_FROM'],
                        "TRANS_TO" => ($request['TRANS_TO'] == 'undefined' || $request['TRANS_TO'] == 'null' || $request['TRANS_TO'] == null) ? '' : $request['TRANS_TO'],
                        "CONTAINER_NO" => ($request['CONTAINER_NO'] == 'undefined' || $request['CONTAINER_NO'] == 'null' || $request['CONTAINER_NO'] == null) ? '' : $request['CONTAINER_NO'],
                        "CONTAINER_QTY" => ($request['CONTAINER_QTY'] == 'undefined' || $request['CONTAINER_QTY'] == 'null' || $request['CONTAINER_QTY'] == null) ? '' : $request['CONTAINER_QTY'],
                        "QTY" => ($request['QTY'] == 'undefined' || $request['QTY'] == 'null' || $request['QTY'] == null) ? '' : $request['QTY'],
                        "CUSTOMS_NO" => ($request['CUSTOMS_NO'] == 'undefined' || $request['CUSTOMS_NO'] == 'null' || $request['CUSTOMS_NO'] == null) ? '' : $request['CUSTOMS_NO'],
                        "CUSTOMS_DATE" => ($request['CUSTOMS_DATE'] == 'undefined' || $request['CUSTOMS_DATE'] == 'null' || $request['CUSTOMS_DATE'] == null) ? null : date('Ymd', strtotime($request['CUSTOMS_DATE'])),
                        "BILL_NO" => ($request['BILL_NO'] == 'undefined' || $request['BILL_NO'] == 'null' || $request['BILL_NO'] == null) ? '' : $request['BILL_NO'],
                        "NW" => ($request['NW'] == 'undefined' || $request['NW'] == 'null' || $request['NW'] == null) ? '' : $request['NW'],
                        "GW" => ($request['GW'] == 'undefined' || $request['GW'] == 'null' || $request['GW'] == null) ? '' : $request['GW'],
                        "POL" => ($request['POL'] == 'undefined' || $request['POL'] == 'null' || $request['POL'] == null) ? '' : $request['POL'],
                        "POD" => ($request['POD'] == 'undefined' || $request['POD'] == 'null' || $request['POD'] == null) ? '' : $request['POD'],
                        "ETD_ETA" => ($request['ETD_ETA'] == 'undefined' || $request['ETD_ETA'] == 'null' || $request['ETD_ETA'] == null) ? '' : $request['ETD_ETA'],
                        "PO_NO" => ($request['PO_NO'] == 'undefined' || $request['PO_NO'] == 'null' || $request['PO_NO'] == null) ? '' : $request['PO_NO'],
                        "ORDER_NO" => ($request['ORDER_NO'] == 'undefined' || $request['ORDER_NO'] == 'null' || $request['ORDER_NO'] == null) ? '' : $request['ORDER_NO'],
                        "INVOICE_NO" => ($request['INVOICE_NO'] == 'undefined' || $request['INVOICE_NO'] == 'null' || $request['INVOICE_NO'] == null) ? '' : $request['INVOICE_NO'],
                        "NOTE" => ($request['NOTE'] == 'undefined' || $request['NOTE'] == 'null' || $request['NOTE'] == null) ? '' : $request['NOTE'],
                        "CUST_NO2" => ($request['CUST_NO2'] == 'undefined' || $request['CUST_NO2'] == 'null' || $request['CUST_NO2'] == null) ? '' : $request['CUST_NO2'],
                        "CUST_NO3" => ($request['CUST_NO3'] == 'undefined' || $request['CUST_NO3'] == 'null' || $request['CUST_NO3'] == null) ? '' : $request['CUST_NO3'],
                        "CUST_NO4" => ($request['CUST_NO4'] == 'undefined' || $request['CUST_NO4'] == 'null' || $request['CUST_NO4'] == null) ? '' : $request['CUST_NO4'],
                        "CUST_NO5" => ($request['CUST_NO5'] == 'undefined' || $request['CUST_NO5'] == 'null' || $request['CUST_NO5'] == null) ? '' : $request['CUST_NO5'],
                        'MODIFY_USER' => ($request['MODIFY_USER'] == 'undefined' || $request['MODIFY_USER'] == 'null' || $request['MODIFY_USER'] == null) ? '' : $request['MODIFY_USER'],
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
            $title = '';
            $query = DB::table('DEBIT_NOTE_M as dnm')->where('dnm.JOB_NO', $request['JOB_NO']);
            //check CHK_MK
            $check_payment_mk =  $query->where('dnm.PAYMENT_CHK', 'Y')->first();
            //check có dữ liệu trong job_order_d
            $check_debit_d =  $query->leftjoin('DEBIT_NOTE_D as dnd', 'dnm.JOB_NO', '=', 'dnd.JOB_NO')->select('dnd.JOB_NO')->get();
            if ($check_payment_mk == null && count($check_debit_d) == 0) {
                $title = 'Đã xóa ' . $request['JOB_NO'] . ' thành công';
                DB::table('DEBIT_NOTE_M')->where('JOB_NO', $request['JOB_NO'])->update([
                    'DEL' =>  'Y',
                    'DEL_DT' =>  date("YmdHis"),
                ]);
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
                        "PAYMENT_DATE" => null
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
            $from_date = ($request->FROM_DATE == 'undefined' || $request->FROM_DATE == 'null' || $request->FROM_DATE == null) ? '19000101' : $request->FROM_DATE;
            $to_date = ($request->TO_DATE == 'undefined' || $request->TO_DATE == 'null' || $request->TO_DATE == null)  ?  $today : $request->TO_DATE;
            $query =  DebitNoteM::queryCheckData();

            switch ($request->TYPE) {
                case 1: //chua thanh toan
                    $payment =  $query->where(function ($query) {
                        $query->where('dnm.PAYMENT_CHK', null)
                            ->orWhere('dnm.PAYMENT_CHK', 'N');
                    });
                    break;
                case 2: //da thanh toan
                    $payment =  $query->where('dnm.PAYMENT_CHK', 'Y');
                    break;
                case 3: //tat ca
                    $payment =  $query->where(function ($q) {
                        $q->where('dnm.PAYMENT_CHK', null)
                            ->orWhere('dnm.PAYMENT_CHK', 'N')
                            ->orWhere('dnm.PAYMENT_CHK', 'Y');
                    });
                    break;
                case 4: //loi nhuan
                    $payment = DB::table('DEBIT_NOTE_M as dnm')
                        ->leftJoin('CUSTOMER as c', 'dnm.CUST_NO', 'c.CUST_NO')
                        ->leftJoin('DEBIT_NOTE_D as dnd', 'dnm.JOB_NO', 'dnd.JOB_NO')
                        ->select('dnm.JOB_NO', 'dnm.CUST_NO', 'c.CUST_NAME', DB::raw('SUM(dnd.PRICE) as PRICE'))
                        ->groupBy('dnm.JOB_NO', 'dnm.CUST_NO', 'c.CUST_NAME');
                    break;
                default:
                    break;
            }

            ($request->JOB_NO == 'undefined' || $request->JOB_NO == 'null' || $request->JOB_NO == null) ?: $payment->where('dnm.JOB_NO', $request->JOB_NO);
            ($request->CUST_NO == 'undefined' || $request->CUST_NO == 'null' || $request->CUST_NO == null) ?: $payment->where('dnm.CUST_NO', $request->CUST_NO);
            // dd($from_date, $to_date);

            $data = $payment->take(1000)
                ->where('dnm.BRANCH_ID', 'IHTVN1')
                ->where('c.BRANCH_ID', 'IHTVN1')
                ->whereBetween("dnm.DEBIT_DATE", array($from_date, $to_date))
                ->orderByDesc('dnm.JOB_NO')
                ->get();
            // dd($data);
            return $data;
        } catch (\Exception $e) {
            return '201';
        }
    }
}

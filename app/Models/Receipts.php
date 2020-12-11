<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Receipts extends Model
{
    public static function query()
    {
        $query = DB::table('RECEIPT as r')
            ->leftJoin('CUSTOMER as c', 'c.CUST_NO', 'r.CUST_NO')
            ->where('r.BRANCH_ID', 'IHTVN1')
            ->orderByDesc('r.RECEIPT_NO');
        return $query;
    }
    public static function list()
    {
        $take = 5000;
        $query =  Receipts::query();
        $data =  $query
            ->take($take)
            ->select('r.RECEIPT_NO', 'r.INPUT_USER', 'c.CUST_NAME')
            ->get();
        return $data;
    }
    public static function listPage($page)
    {
        $take = 10;
        $skip = ($page - 1) * $take;
        $query =  Receipts::query();
        $count = $query->count();
        $data =  $query->skip($skip)
            ->take($take)
            ->select('r.RECEIPT_NO', 'r.INPUT_USER', 'c.CUST_NAME')
            ->get();
        return ['total_page' => $count, 'list' => $data];
    }
    public static function des($id)
    {
        $query =  Receipts::query();
        $data =  $query->where('r.RECEIPT_NO', $id)->select('r.*')->first();
        return $data;
    }

    public static function generateNo()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $countRecordToday = DB::table('RECEIPT')->where('RECEIPT_DATE', date("Ymd"))->count();
        $countRecordToday = (int) $countRecordToday + 1;
        do {
            $no = sprintf("T%s%'.03d", date('ymd'), $countRecordToday);
            $countRecordToday++;
        } while (DB::table('RECEIPT')->where('RECEIPT_NO', $no)->first());
        return $no;
    }
    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $RECEIPT_NO = Receipts::generateNo();
            DB::table('RECEIPT')->insert(
                [
                    'RECEIPT_NO' => $RECEIPT_NO,
                    'RECEIPT_DATE' => date("Ymd"),
                    'RECEIPT_TYPE' => ($request['RECEIPT_TYPE'] == 'undefined' || $request['RECEIPT_TYPE'] == 'null' || $request['RECEIPT_TYPE'] == null) ? '1' : $request['RECEIPT_TYPE'],
                    'CUST_NO' => ($request['CUST_NO'] == 'undefined' || $request['CUST_NO'] == 'null' || $request['CUST_NO'] == null) ? '' : $request['CUST_NO'],
                    'PNL_NO' => ($request['PNL_NO'] == 'undefined' || $request['PNL_NO'] == 'null' || $request['PNL_NO'] == null) ? '' : $request['PNL_NO'],
                    'DOR_NO' => ($request['DOR_NO'] == 'undefined' || $request['DOR_NO'] == 'null' || $request['DOR_NO'] == null) ?  'VND' : $request['DOR_NO'],
                    'TOTAL_AMT' => ($request['TOTAL_AMT'] == 'undefined' || $request['TOTAL_AMT'] == 'null' || $request['TOTAL_AMT'] == null) ? '' : $request['TOTAL_AMT'],
                    'RECEIPT_REASON' => ($request['RECEIPT_REASON'] == 'undefined' || $request['RECEIPT_REASON'] == 'null' || $request['RECEIPT_REASON'] == null) ? '' : $request['RECEIPT_REASON'],
                    'JOB_NO' => ($request['JOB_NO'] == 'undefined' || $request['JOB_NO'] == 'null' || $request['JOB_NO'] == null) ? '' : $request['JOB_NO'],
                    'TRANSFER_FEES' => ($request['TRANSFER_FEES'] == 'undefined' || $request['TRANSFER_FEES'] == 'null' || $request['TRANSFER_FEES'] == null) ? '' : $request['TRANSFER_FEES'],
                    'BRANCH_ID' => ($request['BRANCH_ID'] == 'undefined' || $request['BRANCH_ID'] == 'null' || $request['BRANCH_ID'] == null) ? 'IHTVN1' : $request['BRANCH_ID'],
                    'INPUT_USER' =>  $request['INPUT_USER'],
                    'INPUT_DT' =>  date("YmdHis"),
                ]
            );
            $data = Receipts::des($RECEIPT_NO);
            return $data;
        } catch (\Exception $e) {
            return '400';
        }
    }
    public static function edit($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table('RECEIPT')
                ->where('RECEIPT_NO', $request['RECEIPT_NO'])->update(
                    [
                        'RECEIPT_TYPE' => ($request['RECEIPT_TYPE'] == 'undefined' || $request['RECEIPT_TYPE'] == 'null' || $request['RECEIPT_TYPE'] == null) ? '1' : $request['RECEIPT_TYPE'],
                        'CUST_NO' => ($request['CUST_NO'] == 'undefined' || $request['CUST_NO'] == 'null' || $request['CUST_NO'] == null) ? '' : $request['CUST_NO'],
                        'PNL_NO' => ($request['PNL_NO'] == 'undefined' || $request['PNL_NO'] == 'null' || $request['PNL_NO'] == null) ? '' : $request['PNL_NO'],
                        'DOR_NO' => ($request['DOR_NO'] == 'undefined' || $request['DOR_NO'] == 'null' || $request['DOR_NO'] == null) ?  'VND' : $request['DOR_NO'],
                        'TOTAL_AMT' => ($request['TOTAL_AMT'] == 'undefined' || $request['TOTAL_AMT'] == 'null' || $request['TOTAL_AMT'] == null) ? '' : $request['TOTAL_AMT'],
                        'RECEIPT_REASON' => ($request['RECEIPT_REASON'] == 'undefined' || $request['RECEIPT_REASON'] == 'null' || $request['RECEIPT_REASON'] == null) ? '' : $request['RECEIPT_REASON'],
                        'JOB_NO' => ($request['JOB_NO'] == 'undefined' || $request['JOB_NO'] == 'null' || $request['JOB_NO'] == null) ? '' : $request['JOB_NO'],
                        'TRANSFER_FEES' => ($request['TRANSFER_FEES'] == 'undefined' || $request['TRANSFER_FEES'] == 'null' || $request['TRANSFER_FEES'] == null) ? '' : $request['TRANSFER_FEES'],
                        'MODIFY_USER' =>  $request['MODIFY_USER'],
                        'MODIFY_DT' => date("YmdHis"),
                    ]
                );
            $data = Receipts::des($request['RECEIPT_NO']);
            return $data;
        } catch (\Exception $e) {
            return '201';
        }
    }
    public static function remove($request)
    {
        try {
            DB::table('RECEIPT')
                ->where('RECEIPT_NO', $request['RECEIPT_NO'])
                ->delete();
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function search($type, $value, $page)
    {
        $take = 10;
        $skip = ($page - 1) * $take;
        $query =  Receipts::query();
        switch ($type) {
            case 'receipt_no':
                $query->where('r.RECEIPT_NO', 'LIKE', '%' . $value . '%');
                break;
            case 'cust_no':
                $query->where('r.CUST_NO', 'LIKE', '%' . $value . '%');
                break;
            case  'job_no':
                $query->where('r.JOB_NO', 'LIKE', '%' . $value . '%');
                break;
            default:
                break;
        }
        $count = $query->count();
        $data =  $query->skip($skip)->take($take)->get();
        return ['total_page' => $count, 'list' => $data];
    }
}

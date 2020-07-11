<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    //1.customer, 2.carriers, 3.agent, 4.garage
    public static function list($type)
    {
        $data = DB::table('CUSTOMER as c')
            ->join('CKICO_BRANCH as b', 'c.BRANCH_ID', '=', 'b.BRANCH_ID')
            ->where('c.CUST_TYPE', $type)->select('c.*', 'b.BRANCH_NAME')->get();
        return $data;
    }
    public static function des($id, $type)
    {
        try {
            $data = DB::table('CUSTOMER as c')
                ->join('CKICO_BRANCH as b', 'c.BRANCH_ID', '=', 'b.BRANCH_ID')
                ->where('c.CUST_TYPE', $type)->where('c.CUST_NO', $id)
                ->select('c.*', 'b.BRANCH_NAME')->first();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function generateNo($type)
    {
        $name = '';
        switch ($type) {
            case 1:
                $name = "KH";
                break;
            case 2:
                $name = "HT";
                break;
            case 3:
                $name = "DL";
                break;
            case 4:
                $name = "NX";
                break;
            default:
                $name = "CUST_TYPE không có trong danh sách";
        }

        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $count = DB::table(config('constants.CUSTOMER_TABLE'))->where('CUST_TYPE', $type)->count();
        $count = (int) $count + 1;

        do {
            $no = sprintf($name . "%05d", $count);
            $count++;
        } while (DB::table(config('constants.CUSTOMER_TABLE'))->where('CUST_NO', $no)->first());
        return $no;
    }
    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $CUST_NO = Customer::generateNo($request['CUST_TYPE']);
            DB::table(config('constants.CUSTOMER_TABLE'))->insert(
                [
                    'CUST_TYPE' => $request['CUST_TYPE'],
                    'CUST_NO' => $CUST_NO,
                    'CUST_NAME' => $request['CUST_NAME'],
                    'CUST_CNAME' => $request['CUST_CNAME'],
                    'CUST_ADDRESS' => $request['CUST_ADDRESS'],
                    'CUST_TEL1' => $request['CUST_TEL1'],
                    'CUST_TEL2' => $request['CUST_TEL2'],
                    'CUST_FAX' => $request['CUST_FAX'],
                    'CUST_TAX' => $request['CUST_TAX'],
                    'CUST_BOSS' => $request['CUST_BOSS'],
                    'INPUT_USER' => $request['INPUT_USER'],
                    'INPUT_DT' => date("YmdHis"),
                    'TEN_DON_VI' => $request['TEN_DON_VI'],
                    'BRANCH_ID' => $request['BRANCH_ID'],
                ]
            );
            $data = Customer::des($CUST_NO, $request['CUST_TYPE']);
            return $data;
        } catch (\Exception $e) {
            return '400';
        }
    }
    public static function edit($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table(config('constants.CUSTOMER_TABLE'))
                ->where('CUST_TYPE', $request['CUST_TYPE'])
                ->where('CUST_NO', $request['CUST_NO'])
                ->update(
                    [
                        'CUST_NAME' => $request['CUST_NAME'],
                        'CUST_CNAME' => $request['CUST_CNAME'],
                        'CUST_ADDRESS' => $request['CUST_ADDRESS'],
                        'CUST_TEL1' => $request['CUST_TEL1'],
                        'CUST_TEL2' => $request['CUST_TEL2'],
                        'CUST_FAX' => $request['CUST_FAX'],
                        'CUST_TAX' => $request['CUST_TAX'],
                        'CUST_BOSS' => $request['CUST_BOSS'],
                        'MODIFY_USER' =>  $request['MODIFY_USER'],
                        'MODIFY_DT' => date("YmdHis"),
                        'TEN_DON_VI' => $request['TEN_DON_VI'],
                    ]
                );
            $data = Customer::des($request['CUST_NO'], $request['CUST_TYPE']);
            return $data;
        } catch (\Exception $e) {
            return '400';
        }
    }
    public static function remove($request)
    {
        try {
            DB::table(config('constants.CUSTOMER_TABLE'))
                ->where('CUST_NO', $request['CUST_NO'])
                ->where('CUST_TYPE', $request['CUST_TYPE'])
                ->delete();
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
}

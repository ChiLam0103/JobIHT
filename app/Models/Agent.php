<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Agent extends Model
{
    public static function list()
    {
        $data = DB::table('CUSTOMER as c')
        ->join('CKICO_BRANCH as b', 'c.BRANCH_ID', '=', 'b.BRANCH_ID')
        ->where('c.CUST_TYPE', 3)->where('c.BRANCH_ID', 'IHTVN1')
        ->select('c.*','b.BRANCH_NAME')->get();
        return $data;
    }
    public static function des($id)
    {
        try {
            $data = DB::table('CUSTOMER as c')
            ->join('CKICO_BRANCH as b', 'c.BRANCH_ID', '=', 'b.BRANCH_ID')
            ->where('c.CUST_TYPE', 3)->where('c.BRANCH_ID', 'IHTVN1')->where('c.CUST_NO', $id)
            ->select('c.*','b.BRANCH_NAME')->first();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table(config('constants.AGENT_TABLE'))->insert(
                [
                    'CUST_TYPE' => 3,
                    'CUST_NO' => $request['CUST_NO'],
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
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function edit($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table(config('constants.AGENT_TABLE'))
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
                        'BRANCH_ID' => $request['BRANCH_ID'],
                    ]
                );
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function remove($request)
    {
        try {
            DB::table(config('constants.AGENT_TABLE'))
                ->where('CUST_NO', $request['CUST_NO'])
                ->where('CUST_TYPE', $request['CUST_TYPE'])
                ->delete();
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    //list
    public static function listCustomer()
    {
        $data = DB::table('CUSTOMER')->where('CUST_TYPE', 1)->where('BRANCH_ID', 'IHTVN1')->get();
        return $data;
    }
    public static function listCarriers()
    {
        $data = DB::table('CUSTOMER')->where('CUST_TYPE', 2)->where('BRANCH_ID', 'IHTVN1')->get();
        return $data;
    }
    public static function listAgent()
    {
        $data = DB::table('CUSTOMER')->where('CUST_TYPE', 3)->where('BRANCH_ID', 'IHTVN1')->get();
        return $data;
    }
 
    public static function listGarage()
    {
        $data = DB::table('CUSTOMER')->where('CUST_TYPE', 4)->where('BRANCH_ID', 'IHTVN1')->get();
        return $data;
    }
    //action customer
    public static function desCustomer($id)
    {
        try {
            $data = DB::table('CUSTOMER')->where('CUST_TYPE', 1)->where('CUST_NO', $id)->get();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function addCustomer($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table('CUSTOMER')->insert(
                [
                    'CUST_TYPE' => $request['CUST_TYPE'],
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
                    'INPUT_DT' => date("Ymd"),
                    'TEN_DON_VI' => $request['TEN_DON_VI'],
                    'BRANCH_ID' => $request['BRANCH_ID'],
                ]
            );
            dd(1);
            return '200';
        } catch (\Exception $e) {
            dd(2);
            return $e;
        }
    }
    public static function editCustomer($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table('CUSTOMER')
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
                        'MODIFY_DT' => date("Ymd"),
                        'TEN_DON_VI' => $request['TEN_DON_VI'],
                        'BRANCH_ID' => $request['BRANCH_ID'],
                    ]
                );
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function deleteCustomer($request)
    {
        try {
            DB::table('CUSTOMER')
                ->where('CUST_NO', $request['CUST_NO'])
                ->delete();
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
   
}

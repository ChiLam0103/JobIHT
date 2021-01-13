<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{
    //1.customer(khach hang), 2.carriers(hang tau), 3.agent(dai ly), 4.garage(nha xe)
    public static function query()
    {
        $query = DB::table('CUSTOMER as c')
            ->join('CKICO_BRANCH as b', 'c.BRANCH_ID', '=', 'b.BRANCH_ID')
            ->where('c.BRANCH_ID', 'IHTVN1');
        return $query;
    }
    public static function list($type)
    {
        $type_name = "";
        switch ($type) {
            case 1:
                $type_name = "Khách hàng";
                break;
            case 2:
                $type_name = "Hãng tàu";
                break;
            case 3:
                $type_name = "Đại lý";
                break;
            case 4:
                $type_name = "Nhà xe";
                break;
            default:
                break;
        }
        $take = 9000;
        $query =  Customer::query();
        $data =  $query->take($take)->select('c.*', 'b.BRANCH_NAME')->where('c.CUST_TYPE', $type)->get();

        return ['list' => $data, 'type_name' => $type_name];
    }
    public static function listPage($type, $page)
    {
        $type_name = "";
        switch ($type) {
            case 1:
                $type_name = "Khách hàng";
                break;
            case 2:
                $type_name = "Hãng tàu";
                break;
            case 3:
                $type_name = "Đại lý";
                break;
            case 4:
                $type_name = "Nhà xe";
                break;
            default:
                break;
        }
        $take = 10;
        $skip =  $page == 0 ?  $take : $page * $take;
        $query =  Customer::query();
        $count = $query->where('c.CUST_TYPE', $type)->count();
        $data =  $query->skip($skip)->take($take)->select('c.*', 'b.BRANCH_NAME')->get();

        return ['total_page' => $count, 'list' => $data, 'type_name' => $type_name];
    }
    public static function search($group, $type, $value, $page)
    {
        $group_name = "";
        $cust_type = "";
        switch ($group) {
            case 'customer':
                $group_name = "Khách hàng";
                $cust_type = 1;
                break;
            case 'carriers':
                $group_name = "Hãng tàu";
                $cust_type = 2;
                break;
            case 'agency':
                $group_name = "Đại lý";
                $cust_type = 3;
                break;
            case 'garage':
                $group_name = "Nhà xe";
                $cust_type = 4;
                break;
        }
        $take = 10;
        $skip = ($page - 1) * $take;
        $query =  Customer::query();
        if ($type== 1 || $type =='no') {
                $query->where('c.CUST_NO', 'LIKE', '%' . $value . '%');
        }elseif ($type== 2 || $type =='name')   {
                $query->where('c.CUST_NAME', 'LIKE', '%' .trim($value) . '%');
        }
        $count = $query->where('c.CUST_TYPE', $cust_type)->count();
        $data =  $query->skip($skip)->take($take)->select('c.*', 'b.BRANCH_NAME')->where('c.CUST_TYPE', $cust_type)->get();

        return ['total_page' => $count, 'list' => $data, 'group_name' => $group_name];
    }
    public static function des($id, $type)
    {
        try {
            $query =  Customer::query();
            $data =  $query->select('c.*', 'b.BRANCH_NAME')
                ->where('c.CUST_TYPE', $type)
                ->where('c.CUST_NO', $id)->first();
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
                    'CUST_TYPE' => ($request['CUST_TYPE'] == 'undefined' || $request['CUST_TYPE'] == 'null' || $request['CUST_TYPE'] == null) ? '1' :  $request['CUST_TYPE'],
                    'CUST_NO' => $CUST_NO,
                    'CUST_NAME' => ($request['CUST_NAME'] == 'undefined' || $request['CUST_NAME'] == 'null' || $request['CUST_NAME'] == null) ? '' :  $request['CUST_NAME'],
                    'CUST_CNAME' => ($request['CUST_CNAME'] == 'undefined' || $request['CUST_CNAME'] == 'null' || $request['CUST_CNAME'] == null) ? '' :  $request['CUST_CNAME'],
                    'CUST_ADDRESS' => ($request['CUST_ADDRESS'] == 'undefined' || $request['CUST_ADDRESS'] == 'null' || $request['CUST_ADDRESS'] == null) ? '' : $request['CUST_ADDRESS'],
                    'CUST_TEL1' => ($request['CUST_TEL1'] == 'undefined' || $request['CUST_TEL1'] == 'null' || $request['CUST_TEL1'] == null) ? '' :  $request['CUST_TEL1'],
                    'CUST_TEL2' => ($request['CUST_TEL2'] == 'undefined' || $request['CUST_TEL2'] == 'null' || $request['CUST_TEL2'] == null) ? '' :  $request['CUST_TEL2'],
                    'CUST_FAX' => ($request['CUST_FAX'] == 'undefined' || $request['CUST_FAX'] == 'null' || $request['CUST_FAX'] == null) ? '' :  $request['CUST_FAX'],
                    'CUST_TAX' => ($request['CUST_TAX'] == 'undefined' || $request['CUST_TAX'] == 'null' || $request['CUST_TAX'] == null) ? '' :  $request['CUST_TAX'],
                    'CUST_BOSS' => ($request['CUST_BOSS'] == 'undefined' || $request['CUST_BOSS'] == 'null' || $request['CUST_BOSS'] == null) ? '' :  $request['CUST_BOSS'],
                    'TEN_DON_VI' => ($request['TEN_DON_VI'] == 'undefined' || $request['TEN_DON_VI'] == 'null' || $request['TEN_DON_VI'] == null) ? '' :  $request['TEN_DON_VI'],
                    'INPUT_USER' => ($request['INPUT_USER'] == 'undefined' || $request['INPUT_USER'] == 'null' || $request['INPUT_USER'] == null) ? '' :  $request['INPUT_USER'],
                    'INPUT_DT' => date("YmdHis"),
                    'BRANCH_ID' => ($request['BRANCH_ID'] == 'undefined' || $request['BRANCH_ID'] == 'null' || $request['BRANCH_ID'] == null) ? 'IHTVN1' :  $request['BRANCH_ID'],
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
                        'CUST_NAME' => ($request['CUST_NAME'] == 'undefined' || $request['CUST_NAME'] == 'null' || $request['CUST_NAME'] == null) ? '' :  $request['CUST_NAME'],
                        'CUST_CNAME' => ($request['CUST_CNAME'] == 'undefined' || $request['CUST_CNAME'] == 'null' || $request['CUST_CNAME'] == null) ? '' :  $request['CUST_CNAME'],
                        'CUST_ADDRESS' => ($request['CUST_ADDRESS'] == 'undefined' || $request['CUST_ADDRESS'] == 'null' || $request['CUST_ADDRESS'] == null) ? '' : $request['CUST_ADDRESS'],
                        'CUST_TEL1' => ($request['CUST_TEL1'] == 'undefined' || $request['CUST_TEL1'] == 'null' || $request['CUST_TEL1'] == null) ? '' :  $request['CUST_TEL1'],
                        'CUST_TEL2' => ($request['CUST_TEL2'] == 'undefined' || $request['CUST_TEL2'] == 'null' || $request['CUST_TEL2'] == null) ? '' :  $request['CUST_TEL2'],
                        'CUST_FAX' => ($request['CUST_FAX'] == 'undefined' || $request['CUST_FAX'] == 'null' || $request['CUST_FAX'] == null) ? '' :  $request['CUST_FAX'],
                        'CUST_TAX' => ($request['CUST_TAX'] == 'undefined' || $request['CUST_TAX'] == 'null' || $request['CUST_TAX'] == null) ? '' :  $request['CUST_TAX'],
                        'CUST_BOSS' => ($request['CUST_BOSS'] == 'undefined' || $request['CUST_BOSS'] == 'null' || $request['CUST_BOSS'] == null) ? '' :  $request['CUST_BOSS'],
                        'TEN_DON_VI' => ($request['TEN_DON_VI'] == 'undefined' || $request['TEN_DON_VI'] == 'null' || $request['TEN_DON_VI'] == null) ? '' :  $request['TEN_DON_VI'],
                        'MODIFY_USER' =>  $request['MODIFY_USER'] == 'undefined' ? '' : $request['MODIFY_USER'],
                        'MODIFY_DT' => date("YmdHis"),
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
    //print
    public static function arrayCustomer($custno)
    {
        $array_customer = explode(",", $custno);
        $query =  Customer::query();
        $data = $query->whereIn('c.CUST_NO', $array_customer)->get();
        return $data;
    }
    public static function postCustomer($custno)
    {
        $query =  Customer::query();
        $data = $query->whereIn('c.CUST_NO',$custno)->select('c.CUST_NAME','c.CUST_BOSS','c.CUST_ADDRESS','c.CUST_TEL1','c.CUST_FAX')->first();
        return $data;
    }
}

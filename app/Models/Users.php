<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Users extends Model
{

    public static function login($request)
    {
        if ($request->user_no && $request->user_pwd) {
            $user = DB::table(config('constants.USER_TABLE'))->where(trim('user_no'), trim($request->user_no))->where(trim('user_pwd'), trim($request->user_pwd))->first();
            if ($user) {
                return $user;
            } else {
                //sai thong tin
                return '401';
            }
        } else {
            //thieu du lieu
            return '404';
        }
    }
    public static function list()
    {
        $data = DB::table(config('constants.USER_TABLE'))->get();
        return $data;
    }
    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table(config('constants.USER_TABLE'))->insert(
                [
                    'USER_NO' => $request['USER_NO'],
                    'USER_PWD' => $request['USER_PWD'],
                    'USER_NAME' => $request['USER_NAME'],
                    'ADMIN_MK' => $request['ADMIN_MK'],
                    'INPUT_USER' => $request['INPUT_USER'],
                    'INPUT_DT' => date("YmdHis"),
                    'BRANCH_ID' => $request['BRANCH_ID'],
                ]
            );
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function des($id)
    {
        try {
            $data = DB::table(config('constants.USER_TABLE'))->where('USER_NO', $id)->first();
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function edit($request)
    {
        try {
            DB::table(config('constants.USER_TABLE'))
            ->where('USER_NO', $request['USER_NO'])
            ->update(
                [
                    // 'USER_NO' => $request['USER_NO'],
                    'USER_PWD' => $request['USER_PWD'],
                    'USER_NAME' => $request['USER_NAME'],
                    'ADMIN_MK' => $request['ADMIN_MK'],
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
            DB::table(config('constants.USER_TABLE'))
                ->where('USER_NO', $request['USER_NO'])
                ->delete();
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
}

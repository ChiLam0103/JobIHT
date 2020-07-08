<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Users extends Model
{

    public static function login($request)
    {
        try {
            $user = DB::table(config('constants.USER_TABLE'))
                ->where('USER_NO', $request->user_no)
                ->where('USER_PWD', $request->user_pwd)
                ->first();
            if ($user) {
                return $user;
            } else {
                //sai thong tin
                return '401';
            }
        } catch (Exception $e) {
            return $e;
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
            $data = Users::des($request['USER_NO']);
            return $data;
        } catch (\Exception $e) {
            return '201';
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
                        'USER_PWD' => $request['USER_PWD'],
                        'USER_NAME' => $request['USER_NAME'],
                        'ADMIN_MK' => $request['ADMIN_MK'],
                        'BRANCH_ID' => $request['BRANCH_ID'],
                    ]
                );
            $data = Users::des($request['USER_NO']);
            return $data;
        } catch (\Exception $e) {
            return '201';
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

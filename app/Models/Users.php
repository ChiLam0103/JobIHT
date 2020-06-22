<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Users extends Model
{
    public static function getAll()
    {
        $data = DB::table(config('constants.USER_TABLE'))->get();
        return $data;
    }
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
    public static function listUser()
    {
        $data = DB::table(config('constants.USER_TABLE'))->get();
        return $data;
    }
    public static function getUser($USER_NO)
    {
        $data = DB::table(config('constants.USER_RIGHT_TABLE'))->where('USER_NO',$USER_NO)->get();
        return $data;
    }
    public static function listMenuPro()
    {
        $data = DB::table(config('constants.PRO_MENU_TABLE'))->get();
        return $data;
    }
}

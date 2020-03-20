<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Users extends Model
{
    public static function getAll()
    {
        $data = DB::table('USERM')->get();
        return $data;
    }
    public static function login($request)
    {
        if($request->user_no && $request->user_pwd){
            $user=DB::table('USERM')->where(trim('user_no'),trim($request->user_no))->where(trim('user_pwd'),trim($request->user_pwd))->first();
            if($user){
                return $user;
            }else{
                //sai thong tin
                return '401';
            }
        }else{
            //thieu du lieu
            return '404';
        }
    }
}

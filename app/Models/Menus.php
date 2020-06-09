<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Menus extends Model
{
    public static function listProMenuGroup()
    {
        $data = DB::table('CKICO_PRO_MENU_GROUP')->get();
        return $data;
    }
    public static function listProMenu()
    {
        $data = DB::table('CKICO_PRO_MENU')->get();
        return $data;
    }
    public static function getInfoCompany()
    {
        $data = DB::table('COMPANY')->get();
        return $data;
    }
   
}

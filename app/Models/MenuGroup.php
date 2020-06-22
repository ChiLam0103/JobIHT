<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MenuGroup extends Model
{
    public static function listMenuGroup()
    {
        $data = DB::table(config('constants.MENU_GROUP_TABLE'))->get();
        return $data;
    }

   
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Menu extends Model
{
    public static function listMenu()
    {
        $data = DB::table(config('constants.MENU_TABLE'))->get();
        return $data;
    }
   
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TypeCost extends Model
{
    public static function listTypeCost()
    {
        $data = DB::table('DM_NOIDUNG')->get();
        return $data;
    }
   
}

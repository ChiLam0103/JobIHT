<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Garage extends Model
{
    public static function listGarage()
    {
        $data = DB::table(config('constants.GARAGE_TABLE'))->where('CUST_TYPE', 4)->where('BRANCH_ID', 'IHTVN1')->get();
        return $data;
    }
   
}

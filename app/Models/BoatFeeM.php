<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BoatFeeM extends Model
{
    public static function listBoatMonthM()
    {
        $data = DB::table(config('constants.BOAT_FEE_M_TABLE'))
        ->where('BRANCH_ID','IHTVN1')
        ->where('FEE_TYPE','B')
        ->orderBy('BOAT_FEE_MONTH', 'desc')
        ->get();
        return $data;
    }
    public static function listFeeMonthM()
    {
        $data = DB::table(config('constants.BOAT_FEE_M_TABLE'))
        ->where('BRANCH_ID','IHTVN1')
        ->where('FEE_TYPE','C')
        ->orderBy('BOAT_FEE_MONTH', 'desc')
        ->get();
        return $data;
    }
}

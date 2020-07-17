<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BoatFeeD extends Model
{
    public static function desMonth($request)
    {
        $data = DB::table(config('constants.BOAT_FEE_D_TABLE'))
        ->where('BRANCH_ID','IHTVN1')
        ->where('BOAT_FEE_MONTH',$request->BOAT_FEE_MONTH)
        ->where('FEE_TYPE',$request->FEE_TYPE)
        ->get();
        return $data;
    }
  
}

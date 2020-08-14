<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BoatFeeD extends Model
{
    public static function desMonth($type,$value)
    {
        $data = DB::table(config('constants.BOAT_FEE_D_TABLE'))
        ->where('BRANCH_ID','IHTVN1')
        ->where('BOAT_FEE_MONTH',$value)
        ->where('FEE_TYPE',$type)
        ->get();
        return $data;
    }
    public static function edit($request)
    {
        dd($request);
        // $data = DB::table(config('constants.BOAT_FEE_D_TABLE'))
        // ->where('BRANCH_ID','IHTVN1')
        // ->where('BOAT_FEE_MONTH',$value)
        // ->where('FEE_TYPE',$type)
        // ->get();
        // return $data;
    }
}

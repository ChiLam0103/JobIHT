<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Carriers extends Model
{
    public static function listCarriers()
    {
        $data = DB::table(config('constants.CARRIERS_TABLE'))->where('CUST_TYPE', 2)->where('BRANCH_ID', 'IHTVN1')->get();
        return $data;
    }
   
}

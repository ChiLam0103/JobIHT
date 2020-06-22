<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Agent extends Model
{
    public static function listAgent()
    {
        $data = DB::table(config('constants.AGENT_TABLE'))->where('CUST_TYPE', 3)->where('BRANCH_ID', 'IHTVN1')->get();
        return $data;
    }
   
}

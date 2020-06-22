<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Personal extends Model
{
    public static function listStaffCustoms()
    {
        $data = DB::table(config('constants.PERSONAL_TABLE'))->where('BRANCH_ID', 'IHTVN1')->get();
        return $data;
    }
   
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Branch extends Model
{
    public static function listBranch()
    {
        $data = DB::table(config('constants.BRANCH_TABLE'))->get();
        return $data;
    }
   
}

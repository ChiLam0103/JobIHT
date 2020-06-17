<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Branch extends Model
{
    public static function listBranch()
    {
        $data = DB::table('CKICO_BRANCH')->get();
        return $data;
    }
   
}

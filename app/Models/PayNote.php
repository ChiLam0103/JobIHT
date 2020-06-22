<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PayNote extends Model
{
    public static function listPayNote()
    {
        $data = DB::table(config('constants.PAY_NOTE_TABLE'))->get();
        return $data;
    }
   
}

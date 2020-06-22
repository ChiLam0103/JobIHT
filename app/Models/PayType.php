<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PayType extends Model
{
    public static function listPayType()
    {
        $data = DB::table(config('constants.PAY_TYPE_TABLE'))->get();
        return $data;
    }
}

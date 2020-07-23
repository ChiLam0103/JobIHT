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
    public static function listPayType_JobNo($id)
    {
        $data = DB::table('PAY_TYPE as pt')
        ->rightJoin('JOB_ORDER_D as jod','pt.PAY_NO','jod.ORDER_TYPE')
        ->where('jod.JOB_NO',$id)
        ->select('pt.*')
        ->distinct()
        ->get();
        return $data;
    }
}

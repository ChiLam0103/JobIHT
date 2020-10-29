<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BoatFeeM extends Model
{
    public static function query()
    {
        $query = DB::table(config('constants.BOAT_FEE_M_TABLE'))
            ->where('BRANCH_ID', 'IHTVN1')
            ->orderBy('BOAT_FEE_MONTH', 'desc');
        return $query;
    }
    public static function listBoatMonthM()
    {
        $take = 5000;
        $query =  BoatFeeM::query();
        $data =  $query->take($take)->where('FEE_TYPE', 'B')->get();
        return $data;

    }
    public static function listFeeMonthM()
    {
        $take = 5000;
        $query =  BoatFeeM::query();
        $data =  $query->take($take)->where('FEE_TYPE', 'C')->get();
        return $data;
    }
    public static function listBoatMonthMPage($page)
    {
        $take = 10;
        $skip = ($page - 1) * $take;
        $query =  BoatFeeM::query();
        $count = $query->where('FEE_TYPE', 'B')->count();
        $data =  $query->skip($skip)->where('FEE_TYPE', 'B')
            ->take($take)
            ->get();
        return ['total_page' => $count, 'list' => $data];
    }
    public static function listFeeMonthMPage($page)
    {
        $take = 10;
        $skip = ($page - 1) * $take;
        $query =  BoatFeeM::query();
        $count = $query->where('FEE_TYPE', 'C')->count();
        $data =  $query->skip($skip)->where('FEE_TYPE', 'C')
            ->take($take)
            ->get();
        return ['total_page' => $count, 'list' => $data];
    }
}

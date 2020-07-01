<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TypeCost extends Model
{
    public static function list()
    {
        $data = DB::table(config('constants.TYPE_COST_TABLE'))->get();
        return $data;
    }
    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table(config('constants.TYPE_COST_TABLE'))->insert(
                [
                    'DESCRIPTION_CODE' => $request['DESCRIPTION_CODE'],
                    'DESCRIPTION_NAME_VN' => $request['DESCRIPTION_NAME_VN'],
                    'DESCRIPTION_NAME_CN' => $request['DESCRIPTION_NAME_CN'],
                ]
            );
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function edit($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table(config('constants.TYPE_COST_TABLE'))
            ->where('DESCRIPTION_CODE', $request['DESCRIPTION_CODE'])->update(
                [
                    'DESCRIPTION_NAME_VN' => $request['DESCRIPTION_NAME_VN'],
                    'DESCRIPTION_NAME_CN' => $request['DESCRIPTION_NAME_CN'],
                ]
            );
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function remove($request)
    {
        try {
            DB::table(config('constants.TYPE_COST_TABLE'))
                ->where('DESCRIPTION_CODE', $request['DESCRIPTION_CODE'])
                ->delete();
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }

}

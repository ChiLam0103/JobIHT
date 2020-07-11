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
    public static function des($no)
    {
        $data = DB::table(config('constants.TYPE_COST_TABLE'))
        ->where('DESCRIPTION_CODE',$no)
        ->first();
        return $data;
    }
    public static function generateNo()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $count = DB::table(config('constants.TYPE_COST_TABLE'))->count();
        $count = (int) $count + 1;
        do {
            $no = sprintf( "%03d", $count);
            $count++;
        } while (DB::table(config('constants.TYPE_COST_TABLE'))->where('DESCRIPTION_CODE', $no)->first());
       
        return $no;
    }
    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $DESCRIPTION_CODE=TypeCost::generateNo();
            DB::table(config('constants.TYPE_COST_TABLE'))->insert(
                [
                    'DESCRIPTION_CODE' => $DESCRIPTION_CODE,
                    'DESCRIPTION_NAME_VN' => $request['DESCRIPTION_NAME_VN'],
                    'DESCRIPTION_NAME_CN' => $request['DESCRIPTION_NAME_CN'],
                ]
            );
            $data=TypeCost::des($DESCRIPTION_CODE);
            return $data;
        } catch (\Exception $e) {
            return '201';
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
            $data=TypeCost::des($request['DESCRIPTION_CODE']);
            return $data;
        } catch (\Exception $e) {
            return '201';
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

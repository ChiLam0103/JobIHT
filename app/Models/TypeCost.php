<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TypeCost extends Model
{
    public static function query()
    {
        $query =  DB::table(config('constants.TYPE_COST_TABLE'))->orderByDesc('DESCRIPTION_CODE');
        return $query;
    }
    public static function list()
    {
        $query =  TypeCost::query();
        $data = $query->get();
        return $data;
    }
    public static function listPage($page)
    {
        $take = 10;
        $skip =  $page == 0 ?  $take : $page * $take;
        $query =  TypeCost::query();
        $count = $query->count();
        $data =  $query->skip($skip)
            ->take($take)
            ->get();
        return ['total_page' => $count, 'list' => $data];
    }
    public static function des($no)
    {
        $query =  TypeCost::query();
        $data = $query->where('DESCRIPTION_CODE', $no)->first();

        return $data;
    }
    public static function generateNo()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $query =  TypeCost::query();
        $count = $query->count();
        $count = (int) $count + 1;
        do {
            $no = sprintf("%03d", $count);
            $count++;
        } while ($query->where('DESCRIPTION_CODE', $no)->first());

        return $no;
    }
    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $DESCRIPTION_CODE = TypeCost::generateNo();
            $query =  TypeCost::query();
            $query->insert(
                [
                    'DESCRIPTION_CODE' => $DESCRIPTION_CODE,
                    'DESCRIPTION_NAME_VN' => $request['DESCRIPTION_NAME_VN'],
                    'DESCRIPTION_NAME_CN' => $request['DESCRIPTION_NAME_CN'],
                ]
            );
            $data = TypeCost::des($DESCRIPTION_CODE);
            return $data;
        } catch (\Exception $e) {
            return '201';
        }
    }
    public static function edit($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $query =  TypeCost::query();
            $query->where('DESCRIPTION_CODE', $request['DESCRIPTION_CODE'])->update(
                [
                    'DESCRIPTION_NAME_VN' => $request['DESCRIPTION_NAME_VN'],
                    'DESCRIPTION_NAME_CN' => $request['DESCRIPTION_NAME_CN'],
                ]
            );
            $data = TypeCost::des($request['DESCRIPTION_CODE']);
            return $data;
        } catch (\Exception $e) {
            return '201';
        }
    }
    public static function remove($request)
    {
        try {
            $query =  TypeCost::query();
            $query->where('DESCRIPTION_CODE', $request['DESCRIPTION_CODE'])
                ->delete();
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
}

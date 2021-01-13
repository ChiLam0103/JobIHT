<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Personal extends Model
{
    public static function query()
    {
        $query = DB::table('PERSONAL as p')
            ->join('CKICO_BRANCH as b', 'p.BRANCH_ID', '=', 'b.BRANCH_ID')
            ->where('p.BRANCH_ID', 'IHTVN1')
            ->orderByDesc('p.PNL_NO');
        return $query;
    }
    public static function list()
    {
        $take = 5000;
        $query =  Personal::query();
        $data =  $query
            ->take($take)
            ->select('p.*', 'b.BRANCH_NAME')
            ->get();
        return $data;
    }
    public static function listPage($page)
    {
        $take = 10;
        $skip =  $page == 0 ?  $take : $page * $take;
        $query =  Personal::query();
        $count = $query->count();
        $data =  $query->skip($skip)
            ->take($take)
            ->select('p.*', 'b.BRANCH_NAME')
            ->get();
        return ['total_page' => $count, 'list' => $data];
    }
    public static function des($id)
    {
        $query =  Personal::query();
        $data =  $query->where('p.PNL_NO', $id)->select('p.*', 'b.BRANCH_NAME')->first();
        return $data;
    }

    public static function generateNo()
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $query = Personal::query();
        $count =  $query->first();
        $no = (int)$count->PNL_NO;
        $no++;
        return $no;
    }
    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $PNL_NO = Personal::generateNo();
            DB::table(config('constants.PERSONAL_TABLE'))->insert(
                [
                    'PNL_NO' => $PNL_NO,
                    'PNL_NAME' => ($request['PNL_NAME'] == 'undefined' || $request['PNL_NAME'] == 'null' || $request['PNL_NAME'] == null) ? '' :  $request['PNL_NAME'],
                    'PNL_NAME_C' => ($request['PNL_NAME_C'] == 'undefined' || $request['PNL_NAME_C'] == 'null' || $request['PNL_NAME_C'] == null) ? '' :  $request['PNL_NAME_C'],
                    'PNL_ADDRESS' => ($request['PNL_ADDRESS'] == 'undefined' || $request['PNL_ADDRESS'] == 'null' || $request['PNL_ADDRESS'] == null) ? '' : $request['PNL_ADDRESS'],
                    'PNL_ID' => ($request['PNL_ID'] == 'undefined' || $request['PNL_ID'] == 'null' || $request['PNL_ID'] == null) ? '' : $request['PNL_ID'],
                    'PNL_TEL' => ($request['PNL_TEL'] == 'undefined' || $request['PNL_TEL'] == 'null' || $request['PNL_TEL'] == null) ? '' :  $request['PNL_TEL'],
                    'INPUT_USER' => ($request['INPUT_USER'] == 'undefined' || $request['INPUT_USER'] == 'null' || $request['INPUT_USER'] == null) ? '' :  $request['INPUT_USER'],
                    'INPUT_DT' => date("YmdHis"),
                    'BRANCH_ID' => ($request['BRANCH_ID'] == 'undefined' || $request['BRANCH_ID'] == 'null' || $request['BRANCH_ID'] == null) ? 'IHTVN1' :  $request['BRANCH_ID'],
                ]
            );
            $data = Personal::des($PNL_NO);
            return $data;
        } catch (\Exception $e) {
            return '400';
        }
    }
    public static function edit($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table(config('constants.PERSONAL_TABLE'))
                ->where('PNL_NO', $request['PNL_NO'])->update(
                    [
                        'PNL_NAME' => ($request['PNL_NAME'] == 'undefined' || $request['PNL_NAME'] == 'null' || $request['PNL_NAME'] == null) ? '' :  $request['PNL_NAME'],
                        'PNL_NAME_C' => ($request['PNL_NAME_C'] == 'undefined' || $request['PNL_NAME_C'] == 'null' || $request['PNL_NAME_C'] == null) ? '' :  $request['PNL_NAME_C'],
                        'PNL_ADDRESS' => ($request['PNL_ADDRESS'] == 'undefined' || $request['PNL_ADDRESS'] == 'null' || $request['PNL_ADDRESS'] == null) ? '' : $request['PNL_ADDRESS'],
                        'PNL_ID' => ($request['PNL_ID'] == 'undefined' || $request['PNL_ID'] == 'null' || $request['PNL_ID'] == null) ? '' : $request['PNL_ID'],
                        'PNL_TEL' => ($request['PNL_TEL'] == 'undefined' || $request['PNL_TEL'] == 'null' || $request['PNL_TEL'] == null) ? '' :  $request['PNL_TEL'],
                        'STOP_MK' => ($request['STOP_MK'] == 'undefined' || $request['STOP_MK'] == 'null' || $request['STOP_MK'] == null) ? '' :  $request['STOP_MK'],
                        'MODIFY_USER' => ($request['MODIFY_USER'] == 'undefined' || $request['MODIFY_USER'] == 'null' || $request['MODIFY_USER'] == null) ? '' :  $request['MODIFY_USER'],
                        'MODIFY_DT' => date("YmdHis"),
                    ]
                );
            $data = Personal::des($request['PNL_NO']);
            return $data;
        } catch (\Exception $e) {
            return '201';
        }
    }
    public static function remove($request)
    {
        try {
            $data = Personal::query()->where('PNL_NO', $request['PNL_NO'])->delete();;
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
}

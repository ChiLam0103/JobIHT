<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Personal extends Model
{
    public static function list()
    {
        $data = DB::table('PERSONAL as p')
        ->join('CKICO_BRANCH as b', 'p.BRANCH_ID', '=', 'b.BRANCH_ID')
        ->where('p.BRANCH_ID', 'IHTVN1')->select('p.*','b.BRANCH_NAME')->get();
        return $data;
    }
    public static function des($id)
    {
        $data = DB::table('PERSONAL as p')
        ->join('CKICO_BRANCH as b', 'p.BRANCH_ID', '=', 'b.BRANCH_ID')
        ->where('p.BRANCH_ID', 'IHTVN1') ->where('p.PNL_NO',$id)->select('p.*','b.BRANCH_NAME')->first();
        return $data;
    }
    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table(config('constants.PERSONAL_TABLE'))->insert(
                [
                    'PNL_NO' => $request['PNL_NO'],
                    'PNL_NAME' => $request['PNL_NAME'],
                    'PNL_NAME_C' => $request['PNL_NAME_C'],
                    'PNL_ADDRESS' => $request['PNL_ADDRESS'],
                    'PNL_ID' => $request['PNL_ID'],
                    'PNL_TEL' => $request['PNL_TEL'],
                    'INPUT_USER' => $request['INPUT_USER'],
                    'INPUT_DT' => date("YmdHis"),
                    'BRANCH_ID' => $request['BRANCH_ID'],
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
            DB::table(config('constants.PERSONAL_TABLE'))
            ->where('PNL_NO', $request['PNL_NO'])->update(
                [
                    'PNL_NAME' => $request['PNL_NAME'],
                    'PNL_NAME_C' => $request['PNL_NAME_C'],
                    'PNL_ADDRESS' => $request['PNL_ADDRESS'],
                    'PNL_ID' => $request['PNL_ID'],
                    'PNL_TEL' => $request['PNL_TEL'],
                    'MODIFY_USER' =>  $request['MODIFY_USER'],
                    'STOP_MK' => $request['STOP_MK'],
                    'MODIFY_DT' => date("YmdHis"),
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
            DB::table(config('constants.PERSONAL_TABLE'))
                ->where('PNL_NO', $request['PNL_NO'])
                ->delete();
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class JobM extends Model
{
    public static function list()
    {
        $data = DB::table('JOB_START as js')
        ->leftjoin('JOB_ORDER_M as jm','js.JOB_NO','=','jm.JOB_NO')
        ->select('jm.*')
        ->orderBy('jm.JOB_NO', 'desc')
        ->take(1000)
        ->get();
        return $data;
        return $data;
    }
    public static function listDes($id)
    {
        $data = DB::table('JOB_ORDER_M as jm')
        ->leftjoin('JOB_ORDER_D as jd','jm.JOB_NO','=','jd.JOB_NO')
        ->leftJoin('PAY_TYPE as pt','jd.ORDER_TYPE','=','pt.PAY_NO')
        ->where('jm.JOB_NO',$id)
        ->select('pt.PAY_NAME as ORDER_TYPE_NAME','jd.*')
        ->get();
        return $data;
    }
    public static function getJobOrderM($id)
    {
        $data = DB::table('JOB_ORDER_M')
        ->where('JOB_NO',$id)
        ->first();
        return $data;
    }
    public static function listJobOrderD($id)
    {
        $data = DB::table('JOB_ORDER_D as jo')
        ->where('JOB_NO',$id)
        ->join('PAY_TYPE as pt','pt.PAY_NO','=','jo.ORDER_TYPE')
        ->select('pt.PAY_NAME as ORDER_TYPE_NAME','jo.*')
        ->get();
        return $data;
    }
    public static function addJob($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table('JOB_ORDER_D')->insert(
                ['JOB_NO' => $request['JOB_NO'],
                    'ORDER_TYPE' =>$request['ORDER_TYPE'],
                    'SER_NO' => $request['SER_NO'],
                    'DESCRIPTION' => $request['DESCRIPTION'],
                    'REV_TYPE' => $request['REV_TYPE'],
                    'PORT_AMT' => $request['PORT_AMT'],
                    'NOTE' => $request['NOTE'],
                    'BRANCH_ID' => $request['BRANCH_ID'],
                    'INPUT_USER' => $request['INPUT_USER'],
                    'INPUT_DT' => date("Ymd"),
                ]
            );
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function editJob($request)
    {
        try {
            DB::table('JOB_ORDER_D')
            ->where('JOB_NO', $request['JOB_NO'])
            ->where('ORDER_TYPE', $request['ORDER_TYPE'])
            ->where('SER_NO', $request['SER_NO'])
            ->update(
                [
                    'DESCRIPTION' => $request['DESCRIPTION'],
                    'REV_TYPE' => $request['REV_TYPE'],
                    'PORT_AMT' => $request['PORT_AMT'],
                    'NOTE' => $request['NOTE'],
                    'MODIFY_USER' => $request['MODIFY_USER'],
                    'MODIFY_DT' => date("Ymd"),
                ]
            );
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function deleteJob($request)
    {
        try {
            DB::table('JOB_ORDER_D')
            ->where('JOB_NO', $request['JOB_NO'])
            ->where('ORDER_TYPE', $request['ORDER_TYPE'])
            ->where('SER_NO', $request['SER_NO'])
            ->delete();
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
}

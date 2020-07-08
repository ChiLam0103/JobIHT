<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Permission extends Model
{
    public static function list()
    {
        $data = DB::table(config('constants.PRO_MENU_TABLE'))->get();
        return $data;
    }
    public static function des($USER_NO)
    {
        $data = DB::table(config('constants.USER_RIGHT_TABLE'))
            ->where('USER_NO', $USER_NO)->get();
        return $data;
    }
    public static function edit($request)
    {
        try {
            $USER_NO = $request['USER_NO'];
            //delete data old
            DB::table(config('constants.USER_RIGHT_TABLE'))
                ->where('USER_NO', $USER_NO)
                ->delete();
            foreach ($request['data'] as $data) {
                //add data new
               $da= DB::table(config('constants.USER_RIGHT_TABLE'))
                    ->where('USER_NO', $USER_NO)
                    ->insert(
                        [
                            'USER_NO' => $USER_NO,
                            'PRO_NO' => $data['PRO_NO'],
                            'RUN_MK' => $data['RUN_MK'],
                            'INS_MK' => $data['INS_MK'],
                            'UPD_MK' => $data['UPD_MK'],
                            'DEL_MK' => $data['DEL_MK'],
                            'SEL_MK' => $data['SEL_MK'],
                            'PRN_MK' => $data['PRN_MK'],
                        ]
                    );
            }
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Bank extends Model
{
    public static function list()
    {
        $data = DB::table('BANK')
            ->where('STATUS', 'show')
            ->get();
        return $data;
    }
    public static function des($id)
    {
        $data = DB::table('BANK')->where('BANK_NO', $id)->first();
        return $data;
    }
    public static function add($request)
    {
        try {
            $bank = DB::table('BANK')->insert(
                [
                    'BANK_NO' => $request['BANK_NO'],
                    'BANK_NAME' => ($request['BANK_NAME'] == 'undefined' || $request['BANK_NAME'] == 'null' || $request['BANK_NAME'] == null) ? '' : $request['BANK_NAME'],
                    'ACCOUNT_NO' => ($request['ACCOUNT_NO'] == 'undefined' || $request['ACCOUNT_NO'] == 'null' || $request['ACCOUNT_NO'] == null) ? '' : $request['ACCOUNT_NO'],
                    'ACCOUNT_NAME' => ($request['ACCOUNT_NAME'] == 'undefined' || $request['ACCOUNT_NAME'] == 'null' || $request['ACCOUNT_NAME'] == null) ? '' : $request['ACCOUNT_NAME'],
                    'SWIFT_CODE' => ($request['SWIFT_CODE'] == 'undefined' || $request['SWIFT_CODE'] == 'null' || $request['SWIFT_CODE'] == null) ? '' : $request['SWIFT_CODE'],
                    'BANK_ADDRESS' => ($request['BANK_ADDRESS'] == 'undefined' || $request['BANK_ADDRESS'] == 'null' || $request['BANK_ADDRESS'] == null) ? '' : $request['BANK_ADDRESS'],
                    'ADDRESS' => ($request['ADDRESS'] == 'undefined' || $request['ADDRESS'] == 'null' || $request['ADDRESS'] == null) ? '' : $request['ADDRESS'],
                    'STATUS' => 'show',
                ]
            );
            $des = Bank::des($bank['BANK_NO']);
            return $des;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function edit($request)
    {
        try {
            DB::table('BANK')
                ->where('BANK_NO', $request['BANK_NO'])
                ->update(
                    [
                        'BANK_NAME' => ($request['BANK_NAME'] == 'undefined' || $request['BANK_NAME'] == 'null' || $request['BANK_NAME'] == null)  ? '' : $request['BANK_NAME'],
                        'ACCOUNT_NO' => ($request['ACCOUNT_NO'] == 'undefined' || $request['ACCOUNT_NO'] == 'null' || $request['ACCOUNT_NO'] == null) ? '' : $request['ACCOUNT_NO'],
                        'ACCOUNT_NAME' => ($request['ACCOUNT_NAME'] == 'undefined' || $request['ACCOUNT_NAME'] == 'null' || $request['ACCOUNT_NAME'] == null) ? '' : $request['ACCOUNT_NAME'],
                        'SWIFT_CODE' => ($request['SWIFT_CODE'] == 'undefined' || $request['SWIFT_CODE'] == 'null' || $request['SWIFT_CODE'] == null) ? '' : $request['SWIFT_CODE'],
                        'BANK_ADDRESS' => ($request['BANK_ADDRESS'] == 'undefined' || $request['BANK_ADDRESS'] == 'null' || $request['BANK_ADDRESS'] == null)  ? '' : $request['BANK_ADDRESS'],
                        'ADDRESS' => ($request['ADDRESS'] == 'undefined' || $request['ADDRESS'] == 'null' || $request['ADDRESS'] == null)  ? '' : $request['ADDRESS'],
                    ]
                );
            $des = Bank::des($request['BANK_NO']);
            return $des;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function remove($request)
    {
        try {
            DB::table('BANK')
                ->where('BANK_NO', $request['BANK_NO'])
                ->update(
                    [
                        'STATUS' => 'hide',
                    ]
                );
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
}

<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Users;
use App\Models\Permission;

class UserController extends Controller
{
    public function login(Request $request)
    {
        $data = Users::login($request);
        $permission = Permission::des($data->USER_NO);
        if ($data) {
            if ($data == '401') {
                return response()->json(
                    [
                        'success' => false,
                        'data' => $data,
                        'message' => 'Invalid User or Password1'
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            } else {
                return response()->json([
                    'success' => true,
                    'data' => $data,
                    'permission' => $permission,
                ], Response::HTTP_OK);
            }
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Invalid User or Password2'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    public function list()
    {
        $data = Users::list();
        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data
            ], Response::HTTP_OK);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'null'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    public function add(Request $request)
    {
        $data = Users::add($request);
        if ($data == '201') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error'
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            return response()->json([
                'success' => true,
                'data' => $data
            ], Response::HTTP_OK);
        }
    }
    public function des($id)
    {
        $data = Users::des($id);
        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data
            ], Response::HTTP_OK);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'null'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    public function edit(Request $request)
    {
        $data = Users::edit($request);
        if ($data == '201') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error'
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            return response()->json([
                'success' => true,
                'data' => $data
            ], Response::HTTP_OK);
        }
    }
    public function remove(Request $request)
    {
        $data = Users::remove($request);
        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data
            ], Response::HTTP_OK);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'null'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}

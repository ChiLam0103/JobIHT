<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Customer;

class CustomerController extends Controller
{
    public function list($type)
    {
        $data = Customer::list($type);
        if ($data) {
            return response()->json([
                'success' => true,
                'type_name' => $data['type_name'],
                'data' => $data['list']
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
    public function listPage($type, $page)
    {
        $data = Customer::listPage($type, $page);
        if ($data) {
            return response()->json([
                'success' => true,
                'total_page' => $data['total_page'],
                'type_name' => $data['type_name'],
                'data' => $data['list']
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
    public function search($group, $type, $value, $page)
    {
        $data = Customer::search($group, $type, $value, $page);
        if ($data) {
            return response()->json([
                'success' => true,
                'total_page' => $data['total_page'],
                'group_name' => $data['group_name'],
                'data' => $data['list']
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
    public function des($id, $type)
    {
        $data = Customer::des($id, $type);
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
    public function add(Request $req)
    {
        $data = Customer::add($req);
        if ($data == '400') {
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
            ], Response::HTTP_CREATED);
        }
    }
    public function edit(Request $req)
    {
        $data = Customer::edit($req);
        if ($data == '400') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'error'
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
    public function remove(Request $req)
    {
        $data = Customer::remove($req);
        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' =>  'Bạn đã xóa thành công'
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

<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Receipts;

class ReceiptsController extends Controller
{
    public function list()
    {
        $data = Receipts::list();
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
    public function listPage($page)
    {
        $data = Receipts::listPage($page);
        if ($data) {
            return response()->json([
                'success' => true,
                'total_page'=>$data['total_page'],
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
    public function des($id)
    {
        $data = Receipts::des($id);
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
        $data = Receipts::add($request);
        if ($data == '400') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Error'
                ],
                Response::HTTP_BAD_REQUEST
            );
        } elseif ($data == '409') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'exist PNL_NO'
                ],
                Response::HTTP_CONFLICT
            );
        } else {
            return response()->json([
                'success' => true,
                'data' => $data
            ], Response::HTTP_CREATED);
        }
    }
    public function edit(Request $request)
    {
        $data = Receipts::edit($request);
        if ($data == '201') {
            return response()->json(
                [
                    'success' => false,
                    'message' =>  'Error'
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
        $data = Receipts::remove($request);
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
    public function search($type, $value, $page)
    {
        $data = Receipts::search($type, $value, $page);
        if ($data) {
            return response()->json([
                'success' => true,
                'total_page' => $data['total_page'],
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
}

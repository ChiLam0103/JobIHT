<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Lender;
use App\Models\LenderD;

class LenderController extends Controller
{
    public function list()
    {
        $data = Lender::list();
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
        $data = Lender::listPage($page);
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
    public function listAdvance()
    {
        $data = Lender::listAdvance();
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
    public function listJobNotCreated()
    {
        $data = Lender::listJobNotCreated();
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

    public function search($type, $value, $page)
    {
        $data = Lender::search($type, $value, $page);
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
    public function des($id)
    {
        $data = Lender::des($id);
        $lenderD = LenderD::des($id);
        if ($data == '201') {

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng kiểm tra lại'
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            return response()->json([
                'success' => true,
                'data' => $data,
                'lenderD' => $lenderD
            ], Response::HTTP_OK);
        }
    }
    public function add(Request $request)
    {
        $data = Lender::add($request);
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
    public function edit(Request $request)
    {
        $data = Lender::edit($request);
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
    public function addD(Request $request)
    {
        $data = LenderD::add($request);
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
    public function editD(Request $request)
    {
        $data = LenderD::edit($request);
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
        $data = Lender::remove($request);
        LenderD::removeAll($request);
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
    public function removeD(Request $request)
    {
        $data = LenderD::remove($request);
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

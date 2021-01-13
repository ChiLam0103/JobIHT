<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\DebitNoteM;
use App\Models\DebitNoteD;
use App\Models\TypeCost;

class DebitNoteController extends Controller
{
    public function list()
    {
        $data = DebitNoteM::list();
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
    public function listTake($take)
    {
        $data = DebitNoteM::listTake($take);
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
        $data = DebitNoteM::listPage($page);
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
    public function search($type, $value, $page)
    {
        $data = DebitNoteM::search($type, $value, $page);
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
    public function listNotCreated()
    {
        $data = DebitNoteM::listNotCreated();
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
    public function listPending()
    {
        $data = DebitNoteM::listPending();
        $total = 0;
        foreach ($data as $item) {
            $total += $item->sum_AMT;
        }
        if ($data) {
            return response()->json([
                'success' => true,
                'total' => $total,
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
    public function listPaid()
    {
        $data = DebitNoteM::listPaid();
        $total = 0;
        foreach ($data as $item) {
            $total += $item->sum_AMT;
        }
        if ($data) {
            return response()->json([
                'success' => true,
                'total' => $total,
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
    public function listPendingPage($page)
    {
        $data = DebitNoteM::listPendingPage($page);

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
    public function listPaidPage($page)
    {
        $data = DebitNoteM::listPaidPage($page);

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
    //print
    public function listCustomer($customer)
    {
        $data = DebitNoteM::listCustomer($customer);
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
    public function postListCustomerJob(Request $request)
    {
        $data = DebitNoteM::postListCustomerJob($request);
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
    //print
    public function listJobHasD()
    {
        $data = DebitNoteM::listJobHasD();
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
    public function des($id)
    {
        $debit_note_m = DebitNoteM::des($id);
        $debit_note_d = DebitNoteD::des($id);
        $type_cost = TypeCost::list();
        foreach ($debit_note_d as $item) {
            foreach ($type_cost as $item2) {
                if ($item->DESCRIPTION == $item2->DESCRIPTION_CODE) {
                    $item->DESCRIPTION_NAME_VN = $item2->DESCRIPTION_NAME_VN;
                    break;
                } else {
                    $item->DESCRIPTION_NAME_VN = $item->DESCRIPTION;
                }
            }
        }
        if ($debit_note_m) {
            return response()->json([
                'success' => true,
                'debit_note_m' => $debit_note_m,
                'debit_note_d' => $debit_note_d,
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
    public function desJobNotCreated($id)
    {
        $data = DebitNoteM::desJobNotCreated($id);
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
        $data = DebitNoteM::add($request);
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
        $data = DebitNoteM::edit($request);
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
        $data = DebitNoteM::remove($request);
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
    public function addDebitD(Request $request)
    {
        $data = DebitNoteD::add($request);
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
    public function editDebitD(Request $request)
    {
        $data = DebitNoteD::edit($request);
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
    public function removeDebitD(Request $request)
    {
        $data = DebitNoteD::remove($request);
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
    public function change(Request $request)
    {
        $data = DebitNoteM::change($request);
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
                'data' => 'Bạn đã thay đổi xác nhận thanh toán thành công'
            ], Response::HTTP_OK);
        }
    }
    public function checkData(Request $request)
    {
        $type_name = "";
        switch ($request->TYPE) {
            case 1:
                $type_name = "Chưa thanh toán";
                break;
            case 2:
                $type_name = "Đã thanh toán";
                break;
            case 3:
                $type_name = "Tất cả";
                break;
            case 4:
                $type_name = "Lợi nhuận";
                break;
            default:
                break;
        }
        $data = DebitNoteM::checkData($request);
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
                'type_name' => $type_name,
                'data' => $data
            ], Response::HTTP_OK);
        }
    }
}

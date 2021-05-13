<!DOCTYPE html>
<html lang="">

<body>
    <table>
        <tr>
            <td colspan="6" style="text-align: center">
                <h1 style="color: yellow">{{ $title_vn }}</h1>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center"> TỪ NGÀY: {{ date('Y/m/d', strtotime($fromdate)) }} - ĐẾN
                NGÀY:{{ date('Y/m/d', strtotime($todate)) }} </td>
        </tr>
    </table>
    <table>
        @switch($type)
            @case('truck_fee')
            <tr>
                <th>STT</th>
                <th>Job No</th>
                <th>Mã KH</th>
                <th>Order From</th>
                <th>Order To</th>
                <th>Description</th>
                <th>Đơn vị</th>
                <th>SL</th>
                <th>Đơn Giá</th>
                <th>Thuế</th>
                <th>Tổng Tiền</th>
                <th>User Tạo</th>
            </tr>
            <span style="display: none">{{ $total_money = 0 }}</span>
            @foreach ($data as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->JOB_NO }}</td>
                    <td>{{ $item->CUST_NO }}</td>
                    <td>{{ $item->ORDER_FROM }}</td>
                    <td>{{ $item->ORDER_TO }}</td>
                    <td>{{ $item->DESCRIPTION }}</td>
                    <td>{{ $item->UNIT }}</td>
                    <td>{{ $item->QTY }}</td>
                    <td>{{ $item->PRICE }}</td>
                    <td>{{ $item->TAX_AMT }}</td>
                    <td>{{ $item->PRICE * $item->QTY + $item->TAX_AMT }}</td>
                    <td>{{ $item->INPUT_USER }}</td>
                </tr>
                <span style="display: none">{{ $total_money += $item->PRICE * $item->QTY + $item->TAX_AMT }}</span>
            @endforeach
            <tr>
                <th colspan="10" style="text-align: right">TỔNG SỐ TIỀN:</th>
                <th colspan="2">{{ $total_money }}</th>
            </tr>
            @break
            @case('have_not_debit_note')
            <tr>
                <th>STT</th>
                <th>Job No</th>
                <th>Mã KH</th>
                <th>Order From</th>
                <th>Order To</th>
                <th>Cont. Qty</th>
                <th>Customs No</th>
                <th>Customs Dt</th>
                <th>Bill No</th>
                <th>NW</th>
                <th>GW</th>
                <th>POL</th>
                <th>POD</th>
                <th>User Tạo</th>
            </tr>
            @foreach ($data as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->JOB_NO }}</td>
                    <td>{{ $item->CUST_NO }}</td>
                    <td>{{ $item->ORDER_FROM }}</td>
                    <td>{{ $item->ORDER_TO }}</td>
                    <td>{{ $item->CONTAINER_QTY }}</td>
                    <td>{{ $item->CUSTOMS_NO }}</td>
                    <td>{{ $item->CUSTOMS_DATE }}</td>
                    <td>{{ $item->BILL_NO }}</td>
                    <td>{{ $item->NW }}</td>
                    <td>{{ $item->GW }}</td>
                    <td>{{ $item->POL }}</td>
                    <td>{{ $item->POD }}</td>
                    <td>{{ $item->INPUT_USER }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="2" style="text-align: right">TỔNG CỘNG:</th>
                <th colspan="12" style="text-align: left">{{ count($data) }} PHIẾU</th>
            </tr>
            @break
            @case('unpaid_cont')
            <tr>
                <th>STT</th>
                <th>Job No</th>
                <th>Mã KH</th>
                <th>Order From</th>
                <th>Order To</th>
                <th>Description</th>
                <th>Tiền Cảng</th>
                <th>Tiền KCN</th>
                <th>Tiền Book Tàu</th>
                <th>Tổng Thành Tiền</th>
                <th>User Tạo</th>
            </tr>
            <span style="display: none">{{ $total_money = 0 }}</span>
            @foreach ($data as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->JOB_NO }}</td>
                    <td>{{ $item->CUST_NO }}</td>
                    <td>{{ $item->ORDER_FROM }}</td>
                    <td>{{ $item->ORDER_TO }}</td>
                    <td>{{ $item->DESCRIPTION }}</td>
                    <td>{{ $item->PORT_AMT }}</td>
                    <td>{{ $item->INDUSTRY_ZONE_AMT }}</td>
                    <td>{{ $item->PRICE * $item->QTY }}</td>
                    <td>{{ $item->PORT_AMT + $item->INDUSTRY_ZONE_AMT + ($item->PRICE * $item->QTY) }}</td>
                    <td>{{ $item->INPUT_USER }}</td>
                </tr>
                <span style="display: none">{{ $total_money += $item->PORT_AMT + $item->INDUSTRY_ZONE_AMT + ($item->PRICE * $item->QTY) }}</span>
            @endforeach
            <tr>
                <th colspan="9" style="text-align: right">TỔNG SỐ TIỀN:</th>
                <th colspan="2">{{ $total_money }}</th>
            </tr>
            @break
            @default

        @endswitch
    </table>

</body>

</html>

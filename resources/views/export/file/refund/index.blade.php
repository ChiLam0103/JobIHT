<!DOCTYPE html>
<html lang="">

<body>
        <table>
            <tr>
                <th colspan="5"><h3>BÁO CÁO REFUND {{ $type_name }}</h3></th>
            </tr>
            <tr>
                <th colspan="5"><h5>TỪ NGÀY: {{ date('d/m/Y', strtotime($fromdate)) }} - ĐẾN NGÀY:
                    {{ date('d/m/Y', strtotime($todate)) }}</h5></th>
            </tr>
        </table>
        <table>
            <tr>
                <th>MÃ {{ ($type_name == 'ĐẠI LÝ') || ($type_name == 'KHÁCH HÀNG') ? 'KH' : 'HT' }}</th>
                <th>TÊN {{ $type_name == 'ĐẠI LÝ' ? 'KHÁCH HÀNG' : $type_name }}</th>
                <th>JOB NO</th>
                <th>BILL NO</th>
                <th>STT</th>
                <th>MÔ TẢ</th>
                <th>ĐVT</th>
                <th>SL</th>
                <th>ĐƠN GIÁ</th>
                <th>SAU THUẾ</th>
                <th>TỔNG TIỀN</th>
                <th >THANH TOÁN</th>
            </tr>
            <span style="display: none">{{$sum_price=0}} {{ $sum_money_after = 0}}{{$sum_money=0}}</span>
            @foreach ($data as $item)
                <tr >
                    <td>{{ $type_name == 'ĐẠI LÝ' ? $item->CUST_NO2 : $item->CUST_NO }}</td>
                    <td>{{ $type_name == 'ĐẠI LÝ' ? $item->CUST_NAME2 : $item->CUST_NAME }}</td>
                    <td>{{ $item->JOB_NO }}</td>
                    <td>{{ $item->BILL_NO }}</td>
                    <td>{{ $item->SER_NO }}</td>
                    <td>{{ $item->DESCRIPTION }}</td>
                    <td>{{ (int) $item->UNIT }}</td>
                    <td>{{ number_format($item->QTY,0,",",".") }}</td>
                    <td>{{ number_format($item->PRICE,0,",",".") }}</td>
                    <td>{{number_format($item->PRICE + $item->TAX_AMT,0,",",".") }}</td>
                    <td>{{number_format(($item->PRICE + $item->TAX_AMT)*$item->QTY,0,",",".") }}</td>
                    <td>{{ $item->THANH_TOAN_MK == 'Y' ? 'Đã thanh toán' : 'Chưa thanh toán' }}</td>
                </tr>
                <span style="display: none">{{$sum_price+=$item->PRICE}} {{ $sum_money_after += ($item->PRICE + $item->TAX_AMT)}}{{ $sum_money += ($item->PRICE + $item->TAX_AMT)*$item->QTY}}</span>
            @endforeach
            <tr class="amount">
                <td colspan="8" style="text-align: right">TỔNG TIỀN:</td>
                <td style="text-align: left">{{ number_format($sum_price,0,",",".") }}</td>
                <td  style="text-align: left">{{ number_format($sum_money_after,0,",",".") }}</td>
                <td colspan="2" style="text-align: left">{{ number_format($sum_money,0,",",".") }}</td>
            </tr>
        </table>

</body>
</html>

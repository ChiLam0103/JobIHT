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
        <tr>
            <th>STT</th>
            <th>Job No</th>
            <th>Mã KH</th>
            <th>Tên KH</th>
            <th>Ngày Nhập Job</th>
            <th>Tổng Số Tiền</th>
        </tr>
        @foreach ($data as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->JOB_NO }}</td>
                <td>{{ $item->CUST_NO }}</td>
                <td>{{ $item->CUST_NAME }}</td>
                <td>{{ date('Y/m/d', strtotime($item->DEBIT_DATE)) }}</td>
                <td>{{ number_format($item->TOTAL_AMT, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="5" style="text-align: right">TỔNG SỐ TIỀN:</th>
            <th>{{ number_format($data->sum('TOTAL_AMT'), 0, ',', '.') }}</th>
        </tr>
    </table>

</body>

</html>

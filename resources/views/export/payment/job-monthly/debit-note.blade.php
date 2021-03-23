<!DOCTYPE html>
<html lang="">

<body>
    <table>
        <tr>
            <td colspan="11" style="text-align: center">
                <h1 style="color: yellow">{{ $title_vn }}</h1>
            </td>
        </tr>
        <tr>
            <td colspan="11" style="text-align: center"> TỪ NGÀY: {{ date('Y/m/d', strtotime($fromdate)) }} - ĐẾN
                NGÀY:{{ date('Y/m/d', strtotime($todate)) }} </td>
        </tr>
    </table>
    <span style="display: none">{{ $total_lender = 0 }}{{ $total_job_d = 0 }}</span>
    <table>
        <tr>
            <th>STT</th>
            <th>Job No</th>
            <th>Mã KH</th>
            <th>Tên Khách</th>
            <th>Ngày Nhập Job</th>
            <th>Tổng Số Tiền</th>
            <th>Tiền Thuế</th>
        </tr>
        @foreach ($data as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->JOB_NO }}</td>
                <td>{{ $item->CUST_NO }}</td>
                <td>{{ $item->CUST_NAME }}</td>
                <td>{{  date('Y/m/d', strtotime($item->DEBIT_DATE)) }}</td>
                <td>{{$item->SUM_PRICE }}</td>
                <td>{{$item->SUM_TAX_AMT }}</td>
            </tr>
        @endforeach

    </table>
</body>

</html>

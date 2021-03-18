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
            <th>Order From</th>
            <th>Order To</th>
            <th>Container Qty</th>
            <th>POL</th>
            <th>POD</th>
            <th>ETD/ETA</th>
            <th>Description</th>
            @if ($type == 'job_start')
                <th>Số tiền tạm ứng</th>
                <th>Số tiền job order</th>
            @endif
        </tr>
        @foreach ($data as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->JOB_NO }}</td>
                <td>{{ $item->CUST_NO }}</td>
                <td>{{ $item->CUST_NAME }}</td>
                <td>{{ $type == 'debit_note' ? $item->TRANS_FROM : $item->ORDER_FROM }}</td>
                <td>{{ $type == 'debit_note' ? $item->TRANS_TO : $item->ORDER_TO }}</td>
                <td>{{ $item->CONTAINER_QTY }}</td>
                <td>{{ $item->POD }}</td>
                <td>{{ $item->POL }}</td>
                <td> {{ $type == 'job_start' ? $item->ETA_ETD : $item->ETD_ETA }}</td>
                <td>{{ $item->NOTE }}</td>
                @if ($type == 'job_start')
                    <td>{{ $item->SUM_LENDER_AMT }}</td>
                    <td>{{ $item->SUM_PORT_AMT + $item->SUM_INDUSTRY_ZONE_AMT }}</td>
                    <span style="display: none">
                        {{ $total_job_d += $item->SUM_PORT_AMT + $item->SUM_INDUSTRY_ZONE_AMT }}
                        {{ $total_lender += $item->SUM_LENDER_AMT }}
                    </span>
                @endif
            </tr>
            <tr>
                <td colspan="11"></td>
                <td> {{ $total_lender }}</td>
                <td> {{ $total_job_d }}</td>
            </tr>
        @endforeach

    </table>
</body>

</html>

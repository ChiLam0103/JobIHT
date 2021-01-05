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
            </tr>
        @endforeach

    </table>
</body>

</html>

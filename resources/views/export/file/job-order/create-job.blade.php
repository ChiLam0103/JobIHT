<!DOCTYPE html>
<html lang="">

<body>
    <table>
        <tr>
            <th colspan="12" style="text-align: center"><h1>THỐNG KÊ TẠO JOB</h1></th>
        </tr>
        <tr>
            <th colspan="12" style="text-align: center">TỪ NGÀY: {{ date('d/m/Y', strtotime($fromdate)) }} - ĐẾN NGÀY:
                {{ date('d/m/Y', strtotime($todate)) }}</th>
        </tr>
    </table>
    <table>
        <thead>
            <tr>
                <th>JOB NO</th>
                <th>JOB DATE</th>
                <th>KHÁCH HÀNG</th>
                <th>NHÂN VIÊN</th>
                <th>ORDER FROM</th>
                <th>ORDER TO</th>
                <th>CONTAINER QTY</th>
                <th>CONTAINER NO</th>
                <th>POL</th>
                <th>ETA ETD</th>
                <th>POD</th>
                <th>USER TẠO JOB</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr class="text-align-left">
                    <td>{{ $item->JOB_NO }}</td>
                    <td>{{ date('d/m/Y', strtotime($item->JOB_DATE)) }}</td>
                    <td >{{ $item->CUST_NAME }}</td>
                    <td>{{ $item->PNL_NAME }}</td>
                    <td>{{ $item->ORDER_FROM }}</td>
                    <td>{{ $item->ORDER_TO }}</td>
                    <td>{{ $item->CONTAINER_QTY }}</td>
                    <td>{{ $item->CONTAINER_NO }}</td>
                    <td>{{ $item->POL }}</td>
                    <td>{{ $item->ETA_ETD }}</td>
                    <td>{{ $item->POD }}</td>
                    <td>{{ $item->USER_NAME }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>

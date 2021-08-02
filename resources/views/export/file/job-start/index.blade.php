<!DOCTYPE html>
<html lang="">

<body>
    <table>
        <tr>
            <th colspan="12" style="text-align: center">
                <h1>THỐNG KÊ JOB START (PHIẾU THEO DÕI)</h1>
            </th>
        </tr>
        <tr>
            <th colspan="12" style="text-align: center">TỪ NGÀY: {{ date('d/m/Y', strtotime($from_date)) }} - ĐẾN
                NGÀY:
                {{ date('d/m/Y', strtotime($to_date)) }}</th>
        </tr>
    </table>
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>JOB_NO</th>
                <th>CUST_NO</th>
                <th>ORDER_FROM</th>
                <th>ORDER_TO</th>
                <th>NW</th>
                <th>GW</th>
                <th>POL</th>
                <th>POD</th>
                <th>ETA_ETD</th>
                <th>BILL_NO</th>
                <th>CONTAINER_NO</th>
                <th>CONTAINER_QTY</th>
                <th>CUSTOMS_NO</th>
                <th>CUSTOMS_DATE</th>
                <th>NOTE</th>
                <th>INPUT_USER</th>
                <th>INPUT_DT</th>
                <th>MODIFY_USER</th>
                <th>MODIFY_DT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $item)
                <tr>
                    <td>0{{ $key + 1 }}</td>
                    <td>{{ $item->JOB_NO }}</td>
                    <td>{{ $item->CUST_NO }}</td>
                    <td>{{ $item->ORDER_FROM }}</td>
                    <td>{{ $item->ORDER_TO }}</td>
                    <td>{{ $item->NW }}</td>
                    <td>{{ $item->GW }}</td>
                    <td>{{ $item->POL }}</td>
                    <td>{{ $item->POD }}</td>
                    <td>{{ date('Y/m/d', strtotime($item->ETA_ETD)) }}</td>
                    <td>{{ $item->BILL_NO }}</td>
                    <td>{{ $item->CONTAINER_NO }}</td>
                    <td>{{ $item->CONTAINER_QTY }}</td>
                    <td>{{ $item->CUSTOMS_NO }}</td>
                    <td>{{ date('Y/m/d', strtotime($item->CUSTOMS_DATE)) }}</td>
                    <td>{{ $item->NOTE }}</td>
                    <td>{{ $item->INPUT_USER }}</td>
                    <td>{{ date('Y/m/d', strtotime($item->INPUT_DT)) }}</td>
                    <td>{{ $item->MODIFY_USER }}</td>
                    <td>{{ date('Y/m/d', strtotime($item->MODIFY_DT)) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>

<!DOCTYPE html>
<html lang="">

<body>
    <table>
        <thead>
            <tr>
                <th>Job No</th>
                <th>Cust Name</th>
                <th>Cust Tax</th>
                <th>Bill No</th>
                <th>Container No</th>
                <th>Container QTY</th>
                <th>Mã Lô</th>
                <th>Mã DK</th>
                <th>Số Tiền</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->JOB_NO }}</td>
                    <td>{{ $item->CUST_NAME }}</td>
                    <td>{{ $item->CUST_TAX }}</td>
                    <td>{{ $item->BILL_NO }}</td>
                    <td>{{ $item->CONTAINER_NO }}</td>
                    <td>{{ $item->CONTAINER_QTY }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>

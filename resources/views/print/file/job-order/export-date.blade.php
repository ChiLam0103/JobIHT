<!DOCTYPE html>
<html lang="">

<body>
    <table>
        <thead>
            <tr>
                <th>Số Job Order</th>
                <th>Ngày lập</th>
                <th>Mã khách hàng</th>
                <th>Tên khách hàng</th>
                <th>Order From</th>
                <th>Order To</th>
                <th>NW</th>
                <th>GW</th>
                <th>POL</th>
                <th>POD</th>
                <th>ETA_ETA</th>
                <th>PO_NO</th>
                <th>Container Qty</th>
                <th>Consignee</th>
                <th>Customs Date</th>
                <th>Order Type</th>
                <th>STT</th>
                <th>Mô Tả</th>
                <th>Port AMT</th>
                <th>Note</th>
                <th>Đơn Vị Tính</th>
                <th>Số Lượng</th>
                <th>Đơn Giá</th>
                <th>Tiền thuế</th>
                <th>Tổng Tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($job_m as $item)
                <tr>
                    <td>{{ $item->JOB_NO }}</td>
                    <td>{{ date('Y/m/d', strtotime($item->ORDER_DATE)) }}</td>
                    <td>{{ $item->CUST_NO }}</td>
                    <td style="text-align: left;">{{ $item->CUST_NAME }}</td>
                    <td>{{ $item->ORDER_FROM }}</td>
                    <td>{{ $item->ORDER_TO }}</td>
                    <td>{{ $item->NW }}</td>
                    <td>{{ $item->GW }}</td>
                    <td>{{ $item->POL }}</td>
                    <td>{{ $item->POD }}</td>
                    <td>{{ date('Y/m/d', strtotime($item->ETD_ETA)) }}</td>
                    <td>{{ $item->PO_NO }}</td>
                    <td>{{ $item->CONTAINER_QTY }}</td>
                    <td>{{ $item->CONSIGNEE }}</td>
                    <td>{{ date('Y/m/d', strtotime($item->CUSTOMS_DATE)) }}</td>
                    <td>{{ $item->SHIPPER }}</td>

                    @foreach (\App\Models\Statistic\StatisticFile::getJobOrder_D2($from_date, $to_date, $item->JOB_NO) as $item_d)
                <tr>
                    <td colspan="15" style="border: none"></td>
                    <td>{{ $item_d->PAY_NAME }}</td>
                    <td>{{ $item_d->SER_NO }}</td>
                    <td>{{ $item_d->DESCRIPTION }}</td>
                    <td>{{ number_format($item_d->PORT_AMT) }}</td>
                    <td>{{ $item_d->NOTE }}</td>
                    <td>{{ $item_d->UNIT }}</td>
                    <td>{{ number_format($item_d->QTY) }}</td>
                    <td>{{ number_format($item_d->PRICE) }}</td>
                    <td>{{ number_format($item_d->TAX_AMT) }}</td>
                    <td>{{ number_format(($item_d->PRICE + $item_d->PRICE * ($item_d->TAX_NOTE / 100)) * $item_d->QTY) }}
                    </td>
                </tr>
            @endforeach

            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>

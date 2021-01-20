<!DOCTYPE html>
<html lang="">

<body>
    <table>
        <th>Phiếu yêu cầu thanh toán (payment report)</th>
        <th> TỪ NGÀY: {{ $fromdate == null ? '' : date('d/m/Y', strtotime($fromdate)) }} - ĐẾN NGÀY:
            {{ $todate == null ? '' : date('d/m/Y', strtotime($todate)) }}
        </th>
    </table>
    <table>
        <thead>
            <tr>
                <th>Số Job</th>
                <th>Mã Khách Hàng</th>
                <th>Ngày Lập</th>
                <th>Debit Type</th>
                <th>Ser No</th>
                <th>Invoice No</th>
                <th>Description</th>
                <th>Unit</th>
                <th>Ngoại tệ</th>
                <th>Tỉ Giá Ngoại Tệ</th>
                <th>Đơn Giá</th>
                <th>Số Lượng</th>
                <th>Thuế</th>
                <th>VAT</th>
                <th>Tổng Giá Sau Thuế</th>
            </tr>
        </thead>
        @foreach ($debit as $item)
            <tr>
                <td>{{ $item->JOB_NO }}</td>
                <td>{{ $item->CUST_NO }}</td>
                <td>{{ $item->DEBIT_DATE_M }}</td>
                <td colspan="12"></td>
            </tr>
            @foreach (\App\Models\Statistic\StatisticPayment::postDebitNote_D('debit_date', $fromdate, $todate, $item->JOB_NO, $debittype) as $item_d)
                <tr>
                    <td colspan="3"></td>
                    <td>{{ $item_d->DEB_TYPE }} </td>
                    <td>{{ $item_d->SER_NO }} </td>
                    <td>{{ $item_d->INV_NO }} </td>
                    <td>{{ $item_d->DESCRIPTION }} </td>
                    <td>{{ $item_d->UNIT }} </td>
                    <td>{{ number_format($item_d->DOR_AMT, 0, ',', '.') }} </td>
                    <td>{{ number_format($item_d->DOR_RATE, 0, ',', '.') }} </td>
                    <td>{{ number_format($item_d->PRICE, 0, ',', '.') }} </td>
                    <td>{{ number_format($item_d->QUANTITY, 0, ',', '.') }} </td>
                    <td>{{ $item_d->TAX_NOTE }} </td>
                    <td>{{ number_format($item_d->TAX_AMT, 0, ',', '.') }} </td>
                    <td>{{ number_format($item_d->TOTAL_AMT, 0, ',', '.') }} </td>
                </tr>
            @endforeach
        @endforeach
    </table>

</body>

</html>

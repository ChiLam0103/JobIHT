<!DOCTYPE html>
<html lang="">

<body>
    <table>
        <thead>
            <tr>
                <th>Job No</th>
                <th>Consignee </th>
                <th>From</th>
                <th>To</th>
                <th>ETD/ETA</th>
                <th>Customs No</th>
                <th>Customs Date</th>
                <th>GW</th>
                <th>Ser No</th>
                <th>Red Invoice No</th>
                <th>Description</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Tax Amt</th>
                <th>Total Amt</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($debit as $item)
                <tr>
                    <td>{{ $item->JOB_NO }}</td>
                    <td>{{ $item->CONSIGNEE }}</td>
                    <td>{{ $item->TRANS_FROM }}</td>
                    <td>{{ $item->TRANS_TO }}</td>
                    <td>{{ $item->ETD_ETA }}</td>
                    <td>{{ $item->CUSTOMS_NO }}</td>
                    <td>{{ $item->CUSTOMS_DATE }}</td>
                    <td>{{ $item->GW }}</td>
                    <td colspan="9"></td>
                </tr>
                <span style="display: none;"> {{$total_tax=0}}{{$total_amt=0}}</span>
                    @foreach ($debit_d as $item_d)
                        @if ($item->JOB_NO == $item_d->JOB_NO)
                        <tr>
                    <td colspan="8"> </td>
                            <td>{{ $item_d->SER_NO }}</td>
                            <td>{{ $item_d->INV_NO }}</td>
                            <td>{{ $item_d->DESCRIPTION }}</td>
                            <td>{{ $item_d->UNIT }}</td>
                            <td>{{ $item_d->QUANTITY }}</td>
                            <td>{{number_format( $item_d->PRICE) }}</td>
                            <td>{{number_format( $item_d->TAX_AMT) }}</td>
                            <td>{{number_format( $item_d->TOTAL_AMT) }}</td>
                            <td>{{ $item_d->NOTE }}</td>
                            <span style="display: none;">{{ $total_tax += $item_d->TAX_AMT }} {{ $total_amt += $item_d->TOTAL_AMT }} </span>
                        </tr>
                            @endif
                    @endforeach
                <tr class="text-right; font-weight-bold">
                    <td colspan="14"></td>
                    <td>{{ number_format($total_tax) }}</td>
                    <td>
                        {{ number_format($total_amt) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>

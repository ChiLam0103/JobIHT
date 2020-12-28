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
            <span style="display: none;"> {{ $total_vat_tax = 0 }} {{ $total_sum_amt = 0 }}</span>
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
                    @foreach ($item->debit_d as $item_d)
                    <tr>
                    <td colspan="8"> </td>
                            <td>{{ $item_d['SER_NO']}}</td>
                            <td>{{ $item_d['INV_NO']}}</td>
                            <td>{{ $item_d['DESCRIPTION']}}</td>
                            <td>{{ $item_d['UNIT']}}</td>
                            <td>{{ $item_d['QUANTITY']}}</td>
                            <td>{{ $item_d['PRICE']}}</td>
                            <td>{{$item_d['TAX_AMT']}}</td>
                            <td>{{ $item_d['TOTAL_AMT']}}</td>
                            <td>{{ $item_d['NOTE']}}</td>
                            <span style="display: none;">{{ $total_tax += $item_d['TAX_AMT']}} {{ $total_amt += $item_d['TOTAL_AMT']}} </span>
                        </tr>
                    @endforeach
                <tr class="text-right; font-weight-bold">
                    <td colspan="14"></td>
                    <td>{{ $total_tax }}</td>
                    <td>
                        {{ $total_amt}}
                    </td>
                </tr>
                <span style="display: none;">
                    {{ $total_vat_tax += $total_tax }}
                    {{ $total_sum_amt += $total_amt }}
                </span>
            @endforeach
        </tbody>
    </table>
    <table style="width:100%">
        <tr class="text-right font-weight-bold">
            <th width="71%" colspan="14">TOTAL AMT</th>
            <th>{{ $total_vat_tax }}</th>
            <th>
                {{ $total_sum_amt }}
            </th>
            <th></th>
        </tr>
    </table>
</body>

</html>

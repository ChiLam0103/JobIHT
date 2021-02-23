<!DOCTYPE html>
<html lang="">

<body>
    <table >
        <thead>
            <tr>
                <th>Job No</th>
                <th>Consignee </th>
                <th>From</th>
                <th>To</th>
                <th>Note</th>
                <th>Bill No</th>
                <th>ETD/ETA</th>
                <th>Customs No</th>
                <th>Customs Date</th>
                <th>Container No</th>
                <th>GW</th>
                <th>QTY</th>
                <th>Invoice No</th>
                <th>Ser No</th>
                <th>Red Invoice No</th>
                <th>Description</th>
                <th>Unit</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Tax Amt</th>
                <th>Total Amt</th>

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
                    <td>{{ $item->NOTE}}</td>
                    <td>{{ $item->BILL_NO}}</td>
                    <td>{{ $item->ETD_ETA }}</td>
                    <td>{{ $item->CUSTOMS_NO }}</td>
                    <td>{{ $item->CUSTOMS_DATE }}</td>
                    <td>{{ $item->CONTAINER_NO }}</td>
                    <td>{{ $item->GW }}</td>
                    <td>{{ $item->QTY }}</td>
                    <td>{{ $item->INVOICE_NO }}</td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>

                </tr>
                <span style="display: none;"> {{$total_tax=0}}{{$total_amt=0}} </span>
                    @foreach (\App\Models\Statistic\StatisticPayment::postDebitNote_D('customer', null, null, $item->JOB_NO, null) as $item_d)
                    <tr>
                    <td > </td>
                    <td > </td>
                    <td > </td>
                    <td > </td>
                    <td > </td>
                    <td > </td>
                    <td > </td>
                    <td > </td>
                    <td > </td>
                    <td > </td>
                    <td > </td>
                    <td > </td>
                    <td > </td>
                            <td>{{ $item_d->SER_NO}}</td>
                            <td>{{ $item_d->INV_NO}}</td>
                            <td>{{ $item_d->DESCRIPTION}}</td>
                            <td>{{ $item_d->UNIT}}</td>
                            <td>{{ $item_d->QUANTITY}}</td>
                            <td>{{ $item_d->PRICE}}</td>
                            <td>{{$item_d->TAX_AMT}}</td>
                            <td>{{ $item_d->TOTAL_AMT }}</td>
                            <span style="display: none;">
                                {{ $total_tax += $item_d->TAX_AMT}}
                                {{ $total_amt +=  $item_d->TOTAL_AMT }} </span>
                    </tr>
                    @endforeach
                <tr class="text-right; font-weight-bold">
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td ></td>
                    <td>{{ $total_tax }}</td>
                    <td>
                        {{ $total_amt}}
                    </td>
                    <span style="display: none;">{{ $total_vat_tax += $total_tax }} {{ $total_sum_amt += $total_amt }}</span>
                </tr>
            @endforeach
        </tbody>
    </table>
    <table >
        <tr>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <td ></td>
            <th style="text-align: right">TOTAL AMT</th>
            <th>{{ $total_vat_tax }}</th>
            <th>  {{ $total_sum_amt }}  </th>
        </tr>
    </table>
</body>

</html>

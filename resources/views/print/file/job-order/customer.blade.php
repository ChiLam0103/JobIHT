<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #FAFAFA;
        font: 8px "Tohoma";
    }

    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }


    .page {
        /* width: 21cm; */
        overflow: hidden;
        /* min-height: 297mm; */
        padding: 0.5cm;
        margin-left: auto;
        margin-right: auto;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    @page {
        size: A4 landscape;
        margin: 0;
    }

    @media print {
        @page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
    }

    nav ul {
        list-style-type: none;
        padding: 0;
    }

    .table,
    .table th,
    .table td {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size: 7px;
    }

    table {
        width: 100%;
    }

    .display-none {
        display: none;
    }

</style>

<body onload="window.print();">
    <div id="page" class="page">
        <table class="table">
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
                <th>Shipper</th>
                <th>Order Type</th>
                <th>STT</th>
                <th>Mô Tả</th>
                {{-- <th>Rev Type</th> --}}
                <th>Port AMT</th>
                {{-- <th>Industry Zone Amt</th> --}}
                <th>Note</th>
                <th>Đơn Vị Tính</th>
                <th>Số Lượng</th>
                <th>Đơn Giá</th>
                <th>Tiền thuế</th>
                <th>Tổng Tiền</th>
            </tr>
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
                    @foreach ($job_d as $item_d)
                        @if ($item->JOB_NO == $item_d->JOB_NO)
                <tr>
                    <td colspan="16" style="border: none"></td>
                    <td>{{ $item_d->PAY_NAME }}</td>
                    <td>{{ $item_d->SER_NO }}</td>
                    <td>{{ $item_d->DESCRIPTION }}</td>
                    <td>{{ number_format($item_d->PORT_AMT, 0, ',', '.') }}</td>
                    <td>{{ $item_d->NOTE }}</td>
                    <td>{{ $item_d->UNIT }}</td>
                    <td>{{ number_format($item_d->QTY, 0, ',', '.') }}</td>
                    <td>{{ number_format($item_d->PRICE, 0, ',', '.') }}</td>
                    <td>{{ number_format($item_d->TAX_AMT, 0, ',', '.') }}</td>
                    <td>{{ number_format(($item_d->PRICE + $item_d->PRICE * ($item_d->TAX_NOTE / 100)) * $item_d->QTY, 0, ',', '.') }}
                    </td>
                </tr>

            @endif
            @endforeach

            </tr>
            @endforeach

        </table>
    </div>
</body>

<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #FAFAFA;
        font: 12pt "Tohoma";
    }

    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    .page {
        width: 21cm;
        overflow: hidden;
        min-height: 297mm;
        padding: 0.5cm;
        margin-left: auto;
        margin-right: auto;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    @page {
        size: A4;
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

    .title {
        text-align: center;
        position: relative;
        font-size: 24px;
        font-weight: bold;
    }

    h4 {
        text-align: center;
        position: relative;
        text-transform: uppercase
    }

    .amount td {
        font-weight: bold;
    }

    .tab {
        display: -webkit-flex;
        display: flex;
    }

    .tab-1 {
        display: -webkit-flex;
        display: flex;
        border-bottom: 1px solid #000;
        margin-top: -2em;
    }

    .tab-left-1 {
        -webkit-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }

    .tab-left-2 {
        -webkit-flex: 2;
        -ms-flex: 2;
        flex: 2;
    }

    .tab-right {
        -webkit-flex: 6;
        -ms-flex: 6;
        flex: 6;
    }

    .tab-right ul {
        font-weight: bold;
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
    }

    table {
        width: 100%;
    }

    .footer th {
        text-align: center;
    }

    .display-none {
        display: none;
    }

</style>

<body onload="window.print();">
    <div id="page" class="page">
        <div class="title">JOB ORDER</div>

        <div class="tab">
            <nav class="tab-left-2">
                <ul>
                    <li>Số Tạm Ứng</li>
                    <li>Date</li>
                    <li>J/O No</li>
                    <li>Shipper</li>
                    <li>Consignee</li>
                    <li>From</li>
                    <li>Container Qty</li>
                    <li>POL</li>
                </ul>
            </nav>
            <nav class="tab-right">
                <ul>
                    <li>:&nbsp; {{ $data->LENDER_NO }}</li>
                    <li>:&nbsp; {{ date('Y/m/d', strtotime($data->ORDER_DATE)) }}</li>
                    <li>:&nbsp; {{ $data->JOB_NO }}</li>
                    <li>:&nbsp; {{ $data->SHIPPER }}</li>
                    <li>:&nbsp; {{ $data->CONSIGNEE }}</li>
                    <li>:&nbsp; {{ $data->ORDER_FROM }}</li>
                    <li>:&nbsp; {{ $data->CONTAINER_QTY }}</li>
                    <li>:&nbsp; {{ $data->POL }}</li>
                </ul>
            </nav>
            <nav class="tab-left">
                <ul>
                    <li>Customs Decleration No</li>
                    <li>Customs Date</li>
                    <li>NW</li>
                    <li>GW</li>
                    <li>ETD/ETA</li>
                    <li>To</li>
                    <li>POD</li>
                    <li>P/O No</li>
                </ul>
            </nav>
            <nav class="tab-right">
                <ul>
                    <li>:&nbsp; {{ $data->CUSTOMS_NO }}</li>
                    <li>:&nbsp;{{ date('Y/m/d', strtotime($data->CUSTOMS_DATE)) }}</li>
                    <li>:&nbsp; {{ $data->NW }} Kgs</li>
                    <li>:&nbsp; {{ $data->GW }} Kgs</li>
                    <li>:&nbsp; {{ $data->ETD_ETA ? date('Y/m/d', strtotime($data->ETD_ETA)) : '' }}</li>
                    <li>:&nbsp; {{ $data->ORDER_TO }}</li>
                    <li>:&nbsp; {{ $data->POD }}</li>
                    <li>:&nbsp; {{ $data->PO_NO }}</li>
                </ul>
            </nav>
        </div>
        <div class="tab-1">
            <nav class="tab-left-1">
                <ul>
                    <li>Customer</li>
                </ul>
            </nav>
            <nav class="tab-right">
                <ul>
                    <li>:&nbsp; {{ $data->CUST_NAME }}</li>

                </ul>
            </nav>
        </div>
        @foreach ($pay_type as $pay)
            <h4>{{ $pay->PAY_NAME }}</h4>
            <table class="table">
                <tr>
                    <th style="width: 5%">NO</th>
                    <th style="width: 50%">Description</th>
                    <th style="width: 20%">Amount</th>
                    <th style="width: 25%">Note</th>
                </tr>
                @foreach ($order_d as $order)
                    @if ($pay->PAY_NO == $order->ORDER_TYPE)
                        <tr>
                            <td>{{ $order->SER_NO }}</td>
                            <td style="text-align: left;">{{ $order->DESCRIPTION }}</td>
                            <td>{{ number_format($order->PORT_AMT + $order->INDUSTRY_ZONE_AMT, 0, ',', '.') }}</td>
                            <td>{{ $order->NOTE }}</td>
                        </tr>
                        <span class="display-none">
                            {{ $total_port += (int) ($order->PORT_AMT + $order->INDUSTRY_ZONE_AMT) }}
                        </span>
                    @endif
                @endforeach
            </table>
        @endforeach
        <table class="table" style="margin-top: 1em">
            <tr>
                <th style="width: 5%"></th>
                <th style="width: 50%">TOAL AMOUNT</th>
                <th style="width: 20%">{{ number_format($total_port, 0, ',', '.') }}</th>
                <th style="width: 25%"></th>
            </tr>
            <tr>
                <td></td>
                <td>Profit</td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <br>
        <div class="footer">
            <table>
                <tr>
                    <th>Acounting</th>
                    <th>G.M</th>
                    <th>Im & Ex.M</th>
                    <th>Applicant</th>
                </tr>
            </table>
        </div>
    </div>
</body>

<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #FAFAFA;
        font: 10pt "Tohoma";
        margin: 0px;
    }

    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    .page {
        overflow: hidden;
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
            margin: 15mm 0mm 15mm 0mm;
        }
    }

    .title {
        text-align: center;
        position: relative;
        font-size: 18px;
        font-weight: bold;
        text-transform: uppercase;
        margin-top: -1em;
    }

    .title-2 {
        text-align: center;
        position: relative;
        font-size: 18px;
        font-weight: bold;
        margin-top: -1em;
        margin-bottom: -0.2em;
        text-transform: uppercase;
    }

    nav ul {
        list-style-type: none;
        padding: 0;
    }

    .title-sub {
        font-size: 13px;
        font-weight: bold;
    }

    .padding-left-15 {
        padding-left: 15px;
    }

    .text-align-right {
        text-align: right
    }

    .text-align-left {
        text-align: left
    }

    .col-10 {
        width: 100%;
        display: flex;

    }

    .border {
        border-bottom: 1px solid #ccc;
        padding-top: 0.5em;
    }

    .col-8 {
        width: 80%;
        display: flex;
    }

    .col-7 {
        width: 70%;
        display: flex;
    }

    .col-6 {
        width: 60%;
        display: flex;
    }

    .col-5 {
        width: 50%;
        display: flex;
    }

    .col-4 {
        width: 40%;
        display: flex;
    }

    .col-3 {
        width: 30%;
        display: flex;
    }

    .col-25 {
        width: 25%;
        display: flex;
    }

    .col-2 {
        width: 20%;
        display: flex;
    }

    .col-1 {
        width: 10%;
        display: flex;
    }

    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size: 12px;
    }


    #sum-money {
        font-size: 12px;
        font-weight: bold;
        margin-left: 50%;
    }

</style>

<body onload="window.print();">
    <div id="page" class="page ">
        <div class="border">
            <p class="title">{{ $title_vn }}</p>
            <div class="title-sub"> TỪ NGÀY: {{ date('Y/m/d', strtotime($fromdate)) }} - ĐẾN NGÀY:
                {{ date('Y/m/d', strtotime($todate)) }}
            </div>
            <br>
            <table style="width:100%">
                @switch($type)
                    @case('truck_fee')
                    <tr>
                        <th>STT</th>
                        <th>Job No</th>
                        <th>Mã KH</th>
                        <th>Order From</th>
                        <th>Order To</th>
                        <th>Description</th>
                        <th>Đơn vị</th>
                        <th>SL</th>
                        <th>Đơn Giá</th>
                        <th>Thuế</th>
                        <th>Tổng Tiền</th>
                        <th>User Tạo</th>
                    </tr>
                    <span style="display: none">{{ $total_money = 0 }}</span>
                    @foreach ($data as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->JOB_NO }}</td>
                            <td>{{ $item->CUST_NO }}</td>
                            <td>{{ $item->ORDER_FROM }}</td>
                            <td>{{ $item->ORDER_TO }}</td>
                            <td>{{ $item->DESCRIPTION }}</td>
                            <td>{{ $item->UNIT }}</td>
                            <td>{{ number_format($item->QTY, 0, ',', '.') }}</td>
                            <td>{{ number_format($item->PRICE, 0, ',', '.') }}</td>
                            <td>{{ number_format($item->TAX_AMT, 0, ',', '.') }}</td>
                            <td>{{ number_format($item->PRICE * $item->QTY + $item->TAX_AMT, 0, ',', '.') }}</td>
                            <td>{{ $item->INPUT_USER }}</td>
                        </tr>
                        <span style="display: none">{{ $total_money += $item->PRICE * $item->QTY + $item->TAX_AMT }}</span>
                    @endforeach
                    <tr>
                        <th colspan="10" style="text-align: right">TỔNG SỐ TIỀN:</th>
                        <th colspan="2">{{ number_format($total_money, 0, ',', '.') }}</th>
                    </tr>
                    @break
                    @case('have_not_debit_note')
                    <tr>
                        <th>STT</th>
                        <th>Job No</th>
                        <th>Mã KH</th>
                        <th>Order From</th>
                        <th>Order To</th>
                        <th>Cont. Qty</th>
                        <th>Customs No</th>
                        <th>Customs Dt</th>
                        <th>Bill No</th>
                        <th>NW</th>
                        <th>GW</th>
                        <th>POL</th>
                        <th>POD</th>
                        <th>User Tạo</th>
                    </tr>
                    @foreach ($data as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->JOB_NO }}</td>
                            <td>{{ $item->CUST_NO }}</td>
                            <td>{{ $item->ORDER_FROM }}</td>
                            <td>{{ $item->ORDER_TO }}</td>
                            <td>{{ $item->CONTAINER_QTY }}</td>
                            <td>{{ $item->CUSTOMS_NO }}</td>
                            <td>{{ $item->CUSTOMS_DATE }}</td>
                            <td>{{ $item->BILL_NO }}</td>
                            <td>{{ $item->NW }}</td>
                            <td>{{ $item->GW }}</td>
                            <td>{{ $item->POL }}</td>
                            <td>{{ $item->POD }}</td>
                            <td>{{ $item->INPUT_USER }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2" style="text-align: right">TỔNG CỘNG:</th>
                        <th colspan="12" style="text-align: left">{{ number_format(count($data)) }} PHIẾU</th>
                    </tr>
                    @break
                    @case('unpaid_cont')
                    <tr>
                        <th>STT</th>
                        <th>Job No</th>
                        <th>Mã KH</th>
                        <th>Order From</th>
                        <th>Order To</th>
                        <th>Description</th>
                        <th>Tiền Cảng</th>
                        <th>Tiền KCN</th>
                        <th>Tổng Thành Tiền</th>
                        <th>User Tạo</th>
                    </tr>
                    <span style="display: none">{{ $total_money = 0 }}</span>
                    @foreach ($data as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->JOB_NO }}</td>
                            <td>{{ $item->CUST_NO }}</td>
                            <td>{{ $item->ORDER_FROM }}</td>
                            <td>{{ $item->ORDER_TO }}</td>
                            <td>{{ $item->DESCRIPTION }}</td>
                            <td>{{ number_format($item->PORT_AMT, 0, ',', '.') }}</td>
                            <td>{{ number_format($item->INDUSTRY_ZONE_AMT, 0, ',', '.') }}</td>
                            <td>{{ number_format($item->PORT_AMT + $item->INDUSTRY_ZONE_AMT, 0, ',', '.') }}</td>
                            <td>{{ $item->INPUT_USER }}</td>
                        </tr>
                        <span style="display: none">{{ $total_money += $item->PORT_AMT + $item->INDUSTRY_ZONE_AMT }}</span>
                    @endforeach
                    <tr>
                        <th colspan="8" style="text-align: right">TỔNG SỐ TIỀN:</th>
                        <th colspan="2">{{ number_format($total_money, 0, ',', '.') }}</th>
                    </tr>
                    @break
                    @default

                @endswitch

            </table>

        </div>

    </div>

</body>

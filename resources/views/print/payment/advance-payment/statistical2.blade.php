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
        }
    }

    .title {
        text-align: center;
        position: relative;
        font-size: 24px;
        font-weight: bold;
    }

    .title-sub {
        text-align: center;
        position: relative;
        font-size: 13px;
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
        font-size: 13px;
    }

    #total {
        width: 50%;
    }

    #total th {
        font-weight: bold;
        text-align: left;

    }

    .table .amount td {
        border: none;
    }

    table {
        width: 100%;
    }

    .text-align-left td {
        text-align: left !important;
    }

    .display-none {
        display: none;
    }

</style>

<body onload="window.print();">
    <div id="page" class="page">
        <div class="title">{{ $title }}</div>
        <div class="title-sub"> TỪ NGÀY: {{ date('Y/m/d', strtotime($fromdate)) }} - ĐẾN NGÀY:
            {{ date('Y/m/d', strtotime($todate)) }} </div>
        <div class="title-sub"> NGÀY IN: {{ date('d.m.Y H:i') }}</div>
        <br>
        <table class="table">
            <tr>
                <th>SỐ TẠM ỨNG</th>
                <th>NGÀY TẠM ỨNG</th>
                <th style="width:20%">NHÂN VIÊN TẠM ỨNG</th>
                <th>SỐ JOB</th>
                <th>LOẠI TIỀN</th>
                <th>TIỀN TẠM ỨNG</th>
                <th>TIỀN TIỀN SỬ DỤNG</th>
                <th>TIỀN BÙ</th>
                <th>GHI CHÚ</th>
            </tr>
            @foreach ($data as $item)

                <tr class="text-align-left">
                    <td>{{ $item->LENDER_NO }}</td>
                    <td>{{ date('Y/m/d', strtotime($item->LENDER_DATE)) }}</td>
                    <td>{{ $item->PNL_NAME != null ? $item->PNL_NAME : $item->PNL_NAME2 }}</td>
                    <td>{{ $item->JOB_NO }}</td>
                    <td>{{ $item->DOR_NO }}</td>
                    <td>{{ number_format($item->TOTAL_AMT + $item->AMOUNT_2 + $item->AMOUNT_3 + $item->AMOUNT_4 + $item->AMOUNT_5) }}
                    </td>
                    <td>
                        @foreach ($job as $item2)
                            @if ($item->JOB_NO == $item2->JOB_NO)
                                <span class="display-none">{{ $moneyspent += $item2->PORT_AMT + $item2->INDUSTRY_ZONE_AMT }}</span>
                            @endif
                        @endforeach
                        {{ number_format($moneyspent) }}
                    </td>
                    <td>{{ number_format($item->TOTAL_AMT + $item->AMOUNT_2 + $item->AMOUNT_3 + $item->AMOUNT_4 + $item->AMOUNT_5) }}
                    </td>
                    <td>{{ $item->LEND_REASON }}</td>
                </tr>
                <span class="display-none">
                    {{ $total += (int) ($item->TOTAL_AMT + $item->AMOUNT_2 + $item->AMOUNT_3 + $item->AMOUNT_4 + $item->AMOUNT_5) }}
                </span>
            @endforeach

        </table>
        <table id="total">
            <tr>
                <th>TỔNG CỘNG: {{ count($data) }} PHIẾU</th>
                <th>TỔNG SỐ TIỀN:{{ number_format($total) }}</th>
            </tr>
        </table>

    </div>
</body>

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
    .table .amount td{
        border: none;
    }
    table {
        width: 100%;
    }
    .amount td {
        font-weight: bold;
    }
</style>

<body onload="window.print();">
    <div id="page" class="page">
        <div class="title">BÁO CÁO REEFUND {{ $type_name }}</div>
        <div class="title-sub"> TỪ NGÀY: {{ date('Y/m/d', strtotime($todate)) }} - ĐẾN NGÀY:
            {{ date('Y/m/d', strtotime($fromdate)) }} </div>
        <br>
        <table class="table">
            <tr>
                <th>MÃ {{ $type_name }}</th>
                <th>TÊN {{ $type_name }}</th>
                <th>JOB NO</th>
                <th>STT</th>
                <th>DESCRIPTION</th>
                <th>ĐVT</th>
                <th>SL</th>
                <th>ĐƠN GIÁ</th>
                <th>THÀNH TIỀN<br> TRƯỚC THUẾ</th>
                <th>TIỀN THUẾ</th>
                <th>THÀNH TIỀN<br> SAU THUẾ</th>
                <th>THANH TOÁN</th>
            </tr>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->CUST_NO }}</td>
                    <td>{{ $item->CUST_NAME }}</td>
                    <td>{{ $item->JOB_NO }}</td>
                    <td>{{ $item->SER_NO }}</td>
                    <td>{{ $item->DESCRIPTION }}</td>
                    <td>{{ (int) $item->UNIT }}</td>
                    <td>{{ number_format($item->QTY) }}</td>
                    <td>{{ number_format($item->PRICE) }}</td>
                    <td>{{ number_format($item->TAX_AMT * $item->TAX_NOTE) }}</td>
                    <td>{{ number_format($item->TAX_AMT) }}</td>
                    <td>{{ number_format($item->TAX_AMT * $item->TAX_NOTE + $item->TAX_AMT) }}</td>
                    <td>{{ $item->THANH_TOAN_MK == 'Y' ? 'Đã thanh toán' : 'Chưa thanh toán' }}</td>
                </tr>
            @endforeach
                <tr  class="amount">
                    <td colspan="5"></td>
                    <td colspan="3">TỔNG TIỀN:</td>
                    <td>1</td>
                    <td>2</td>
                    <td colspan="2">3</td>
                </tr>
        </table>
    </div>
</body>

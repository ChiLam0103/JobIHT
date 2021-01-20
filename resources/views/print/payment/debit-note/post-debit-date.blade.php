<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #FAFAFA;
        font: 10pt "Tohoma";
    }

    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    /* .page {
        width: 21cm;
        overflow: hidden;
        min-height: 297mm;
        padding: 0.5cm;
        margin-left: auto;
        margin-right: auto;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    } */
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

    .font-weight-bold {
        font-weight: bold;
    }

    .title {
        text-align: center;
        position: relative;
        font-size: 24px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .title-sub {
        margin-top: -1em;
        text-align: center;
        position: relative;
        font-size: 13px;
    }


    .title h1 {
        margin-top: 0em;
    }

    .text-center {
        text-align: center
    }

    .text-left {
        text-align: left
    }

    .text-right {
        text-align: right
    }

    table,
    table th,
    table td {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size: 13px;
    }

    table {
        width: 100%;
    }

    tr td {
        padding-top: 0.2em;
        font-size: 13px
    }

    .border {
        border: 1px solid;
    }

    .col-10 {
        width: 100%;
        display: flex;
    }

    .col-9 {
        width: 90%;
    }

    .col-8 {
        width: 80%;
    }

    .col-7 {
        width: 70%;
    }

    .col-6 {
        width: 60%;
    }

    .col-5 {
        width: 50%;
    }

    .col-4 {
        width: 40%;
    }

    .col-3 {
        width: 30%;
    }

    .col-25 {
        width: 25%;
    }

    .col-2 {
        width: 20%;
    }

    .col-1 {
        width: 10%;
    }

    #lnkPrint {
        margin-top: 1em;
        background: aquamarine;
    }

    #lnkPrint:active::after {
        display: none;
    }

</style>
<script>
    function myFunction() {
        var x = document.getElementById("lnkPrint");
        if (x.style.display === "none") {
            x.style.display = "block";
        } else {
            x.style.display = "none";
            window.print();
        }
    }

</script>

<body>
    <div id="page" class="page">
        <button type="button" id="lnkPrint" onclick="myFunction()">Click the button to print the current page</button>
        <p class="title">Phiếu yêu cầu thanh toán (payment report)</p>
        <div class="title-sub"> TỪ NGÀY: {{ $fromdate == null ? '' : date('d/m/Y', strtotime($fromdate)) }} - ĐẾN NGÀY:
            {{ $todate == null ? '' : date('d/m/Y', strtotime($todate)) }}
        </div>
        <table>
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
            @foreach ($debit as $item)
                <tr>
                    <td>{{ $item->JOB_NO }}</td>
                    <td>{{ $item->CUST_NO }}</td>
                    <td>{{ $item->DEBIT_DATE_M }}</td>
                    <td colspan="12"></td>
                </tr>
                @foreach (\App\Models\Statistic\StatisticPayment::postDebitNote_D('debit_date', $fromdate, $todate, $item->JOB_NO, $debittype) as $item_d)
                    <tr>
                        {{-- {{ dd($item_d) }} --}}
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
    </div>
</body>

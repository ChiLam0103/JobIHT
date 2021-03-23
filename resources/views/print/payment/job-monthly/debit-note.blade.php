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
        text-align: left;
        font-size: 11px;
    }


    #sum-money {
        font-size: 12px;
        font-weight: bold;
        margin-left: 50%;
    }
    .center th{
        text-align: center
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
                <tr  class="center">
                    <th>STT</th>
                    <th>Job No</th>
                    <th>Mã KH</th>
                    <th>Tên Khách Hàng</th>
                    <th>Ngày Nhập Job</th>
                    <th>Tổng Số Tiền</th>
                    <th>Tiền Thuế</th>
                </tr>
                @foreach ($data as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->JOB_NO }}</td>
                        <td>{{ $item->CUST_NO }}</td>
                        <td>{{ $item->CUST_NAME }}</td>
                        <td>{{  date('Y/m/d', strtotime($item->DEBIT_DATE)) }}</td>
                        <td>{{number_format( $item->SUM_PRICE, 2, ',', '.') }}</td>
                        <td>{{number_format($item->SUM_TAX_AMT, 2, ',', '.')  }}</td>

                    </tr>
                @endforeach

            </table>

        </div>

    </div>

</body>

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
        font-size: 11px;
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
            <p class="title">BÁO CÁO LỢI NHUẬN</p>
            <div class="title-sub"> TỪ NGÀY: {{ date('Y/m/d', strtotime($fromdate)) }} - ĐẾN NGÀY:
                {{ date('Y/m/d', strtotime($todate)) }}
            </div>
            <br>
            <table style="width:100%">
                <tr>
                    <th>STT</th>
                    <th>Job No</th>
                    <th>Mã KH</th>
                    <th>Tên Khách</th>
                    <th>Tiền Thanh Toán</th>
                    <th>Chi Phí</th>
                    <th>Lợi Nhuận</th>
                </tr>
                <span style="display: none">{{ $sum_chi_phi = 0 }}{{ $sum_loi_nhuan = 0 }}</span>
                @foreach ($thanh_toan as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->JOB_NO }}</td>
                        <td>{{ $item->CUST_NO }}</td>
                        <td>{{ $item->CUST_NAME }}</td>
                        <td> {{ number_format($item->TIEN_THANH_TOAN, 0, ',', '.') }} </td>
                        @foreach ($item->job_d as $item_2)
                            <td> {{ number_format($item_2->CHI_PHI + $item_2->SUM_PORT_AMT + $item_2->SUM_INDUSTRY_ZONE_AMT - $item_2->SUM_TAX_AMT, 0, ',', '.') }}
                            </td>
                            <td> {{ number_format($item->TIEN_THANH_TOAN - ($item_2->CHI_PHI + $item_2->SUM_PORT_AMT + $item_2->SUM_INDUSTRY_ZONE_AMT - $item_2->SUM_TAX_AMT), 0, ',', '.') }}
                            </td>
                            <span style="display: none">{{ $sum_chi_phi += $item_2->CHI_PHI + $item_2->SUM_PORT_AMT + $item_2->SUM_INDUSTRY_ZONE_AMT - $item_2->SUM_TAX_AMT }}
                            {{ $sum_loi_nhuan += $item->TIEN_THANH_TOAN - ($item_2->CHI_PHI + $item_2->SUM_PORT_AMT + $item_2->SUM_INDUSTRY_ZONE_AMT - $item_2->SUM_TAX_AMT) }}</span>
                        @endforeach
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="4">TOTAL AMT</th>
                    <th>{{number_format($thanh_toan->sum('TIEN_THANH_TOAN'), 0, ',', '.') }} </th>
                    <th>{{ number_format($sum_chi_phi, 0, ',', '.') }} </th>
                    <th>{{ number_format($sum_loi_nhuan, 0, ',', '.') }}</th>
                </tr>
            </table>

        </div>

    </div>

</body>

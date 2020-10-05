<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #FAFAFA;
        font: 12pt "Tohoma";
        zoom:75%;
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
        font-size: 13px;
        word-wrap: break-word;
    white-space:pre-wrap;
    }
    .table .job_d td {
        background-color: #ccc;
        font-size: 12px !important;
    }

    .table .amount td {
        border: none;
    }

    table {
        width: 100%;
    }

</style>

<body onload="window.print();">
    <div id="page" class="page">
        <div class="title">Thống kê NV nhập Job Order</div>
        <div class="title-sub"> TỪ NGÀY: {{ date('Y/m/d', strtotime($fromdate)) }} - ĐẾN NGÀY:
            {{ date('Y/m/d', strtotime($todate)) }} </div>
        <br>
        <table class="table">
            <tr>
                <th style="width: 10%">JOB NO</th>
                <th style="width: 5%">JOB DATE</th>
                <th style="width: 10%">KHÁCH HÀNG</th>
                <th style="width: 10%">NHÂN VIÊN</th>
                <th style="width: 10%">ORDER FROM</th>
                <th style="width: 10%">ORDER TO</th>
                <th style="width: 5%">CONTAINER QTY</th>
                <th style="width: 10%">CONTAINER NO</th>
                <th style="width: 10%">POL</th>
                <th style="width: 5%">ETA ETD</th>
                <th style="width: 10%">POD</th>
                <th style="width: 5%">USER TẠO JOB</th>
            </tr>
            @foreach ($data as $item)
                <tr>
                    <td>{{ $item->JOB_NO }}</td>
                    <td>{{ date('Y/m/d', strtotime($item->JOB_DATE)) }}</td>
                    <td>{{ $item->CUST_NAME }}</td>
                    <td>{{ $item->PNL_NAME }}</td>
                    <td>{{ $item->ORDER_FROM }}</td>
                    <td>{{ $item->ORDER_TO }}</td>
                    <td>{{ $item->CONTAINER_QTY }}</td>
                    <td>{{ $item->CONTAINER_NO }}</td>
                    <td>{{ $item->POL }}</td>
                    <td>{{ date('Y/m/d', strtotime($item->ETD_ETA)) }}</td>
                    <td colspan="2">{{ $item->POD }}</td>
                </tr>
                @foreach ($job_d as $item_d)
                    @if ($item->JOB_NO == $item_d->JOB_NO)
                        <tr class="job_d">
                            <td colspan="2" style="border: none"></td>
                            <td>{{ $item_d->PAY_NAME }}</td>
                            <td>{{ $item_d->SER_NO }}</td>
                            <td colspan="4">{{ $item_d->DESCRIPTION }}</td>
                            <td>{{ date('Y/m/d', strtotime($item_d->INPUT_DT)) }}</td>
                            <td colspan="3">{{ $item_d->USER_NAME }}</td>
                        </tr>

                    @endif
                @endforeach
            @endforeach
        </table>
    </div>
</body>

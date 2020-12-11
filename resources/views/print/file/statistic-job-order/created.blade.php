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

    .table .amount td {
        border: none;
    }

    table {
        width: 100%;
    }
    .text-align-left td{
        text-align: left!important;
    }
</style>

<body onload="window.print();">
    <div id="page" class="page">
        <div class="title">Thống kê tạo JOB</div>
        <div class="title-sub"> TỪ NGÀY: {{ date('d/m/Y', strtotime($fromdate)) }} - ĐẾN NGÀY:
            {{ date('d/m/Y', strtotime($todate)) }} </div>
        <br>
        <table class="table">
            <tr>
                <th>JOB NO</th>
                <th>JOB DATE</th>
                <th>KHÁCH HÀNG</th>
                <th>NHÂN VIÊN</th>
                <th>ORDER FROM</th>
                <th>ORDER TO</th>
                <th>CONTAINER QTY</th>
                <th>CONTAINER NO</th>
                <th>POL</th>
                <th>ETA ETD</th>
                <th>POD</th>
                <th>USER TẠO JOB</th>
            </tr>
            @foreach ($data as $item)
                <tr class="text-align-left">
                    <td>{{ $item->JOB_NO }}</td>
                    <td>{{ date('d/m/Y', strtotime($item->JOB_DATE)) }}</td>
                    <td >{{ $item->CUST_NAME }}</td>
                    <td>{{ $item->PNL_NAME }}</td>
                    <td>{{ $item->ORDER_FROM }}</td>
                    <td>{{ $item->ORDER_TO }}</td>
                    <td>{{ $item->CONTAINER_QTY }}</td>
                    <td>{{ $item->CONTAINER_NO }}</td>
                    <td>{{ $item->POL }}</td>
                    <td>{{ $item->ETA_ETD }}</td>
                    <td>{{ $item->POD }}</td>
                    <td>{{ $item->USER_NAME }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</body>

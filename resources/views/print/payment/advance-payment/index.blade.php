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

    .title-2 {
        text-align: center;
        position: relative;
        font-size: 18px;
        font-weight: bold;
        margin-top: -1em;
    }

    nav ul {
        list-style-type: none;
        padding: 0;
    }

    .title-sub {
        font-size: 15px;
        font-weight: bold;
    }

    .padding-left-15 {
        padding-left: 15px;
    }

    .col-10 {
        width: 100%;
        display: flex;

    }

    .border {
        border-bottom: 1px solid;
        padding-top: 1em;
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

    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
    }

    #sign td {
        height: 10em;
    }

    tr td {
        font-size: 14px
    }

</style>

@foreach ($data as $item)

    <body onload="window.print();">
        <div id="page" class="page">
            <p class="title">{{ $title_vn }}</p>
            <p class="title-2">{{ $title_cn }}</p>
            <div class="col-10 border">
                <div class="col-5">
                    <span>借支單號:&nbsp;<span class="title-sub">{{ $item->LENDER_NO }}</span> </span>
                </div>
                <div class="col-2"></div>
                <div class="col-3">
                    <span>列印日期:&nbsp;{{ date('d.m.Y H:i') }}</span>
                </div>
            </div>
            <div class="col-10 border">
                <div class="col-5">
                    <span>借支人(Người xin tạm ứng):&nbsp;<span class="title-sub"> {{ $item->PNL_NAME }}</span></span>
                </div>
                <div class="col-2"></div>
                <div class="col-3">
                    <span>借支日期:&nbsp;{{ date('Y/m/d', strtotime($item->LENDER_DATE)) }}</span>
                </div>
            </div>
            <div class="border">
                <div class="col-10 ">
                    <div class="col-3">
                        @if ($type == 1)
                            <span>Job Order No:&nbsp;<span class="title-sub">{{ $item->JOB_NO }}</span></span>
                        @endif
                    </div>
                    <div class="col-7">
                        <span>Khách Hàng:&nbsp;{{ $item->CUST_NO }} - {{ $item->CUST_NAME }}</span>
                    </div>
                </div>
                <div class="col-10">
                    <div class="col-3">
                        @if ($type == 1)
                            <span>Số Job Đăng Ký:&nbsp;<span class="title-sub">{{ $item->JOB_NO }}</span></span>
                        @endif
                    </div>
                    <div class="col-2">
                        <span>Order From:&nbsp;{{ $item->ORDER_FROM }}</span>
                    </div>
                    <div class="col-25">
                        <span>Order To:&nbsp;{{ $item->ORDER_FROM }}</span>
                    </div>
                    <div class="col-25">
                        <span>CONTAINER_QTY:&nbsp;{{ $item->CONTAINER_QTY }}</span>
                    </div>
                </div>
            </div>
            <div class="col-10 border">
                <span>金額(Số tiền):&nbsp;<span class="title-sub">
                        {{ number_format($item->TOTAL_AMT + $item->AMOUNT_2 + $item->AMOUNT_3 + $item->AMOUNT_4 + $item->AMOUNT_5) }}
                        {{ $item->DOR_NO }}</span></span>
            </div>
            <div class="col-10 border">
                <span>事由 (Lý do):&nbsp;<span class="title-sub"> {{ $item->LEND_REASON }}</span></span>
            </div>
            <table style="width:100%">
                <tr>
                    <th rowspan="3" style="width:3%">財務審核 </th>
                    <th style="width:15.6%">財務核准</th>
                    <th style="width:15.6%">出納</th>
                    <th style="width:15.6%">取款人</th>
                    <th rowspan="3" style="width:3%">申請核准</th>
                    <th style="width:15.6%">核准</th>
                    <th style="width:15.6%">單位主管</th>
                    <th>申請人</th>
                </tr>
                <tr>
                    <td>Tài vụ</td>
                    <td>Thủ Quỹ</td>
                    <td>Người Nhận Tiền</td>
                    <td>Duyệt</td>
                    <td>Chủ Quản Đơn Vị</td>
                    <td>Người Xin Tạm Ứng</td>
                </tr>
                <tr id="sign">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>

    </body>
@endforeach

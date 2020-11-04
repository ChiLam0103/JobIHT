<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #FAFAFA;
        font: 8pt "Tohoma";
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
        font-size: 18px;
        font-weight: bold;
    }

    .title-2 {
        text-align: center;
        position: relative;
        font-size: 14px;
        font-weight: bold;
        margin-top: -1em;
    }

    nav ul {
        list-style-type: none;
        padding: 0;
    }

    .title-sub {
        font-size: 12px;
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
        border-bottom: 1px solid #ccc;
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

    table {
        margin-top: 1em;
    }

    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
    }

    #sign td {
        height: 8em;
    }

    tr td {
        font-size: 14px
    }

</style>

<body onload="window.print();">
    <div id="page" class="page">
        <p class="title">{{ $title_vn }}</p>
        <p class="title-2">{{ $title_cn }}</p>
        <div class="col-10 border">
            <div class="col-25">
                <span>Job No:&nbsp;<span class="title-sub">{{ $advance->JOB_NO }}</span> </span>
            </div>
            <div class="col-25">
                <span>Type:&nbsp;<span class="title-sub">{{ $advance->LENDER_NAME }}</span> </span>
            </div>
            <div class="col-25">
                <span>Advance No:&nbsp;<span class="title-sub">{{ $advance->LENDER_NO }}</span> </span>
            </div>
            <div class="col-25">
                <span>Advance Date:&nbsp;<span
                        class="title-sub">{{ date('Y/m/d', strtotime($advance->LENDER_DATE)) }}</span> </span>
            </div>
        </div>
        <div class="col-10 border">
            <div class="col-25">
                <span>Advance Staff:&nbsp;<span class="title-sub">{{ $advance->PNAME }}</span> </span>
            </div>
            <div class="col-25">
                <span>Kinds Of Money:&nbsp;<span class="title-sub">{{ $advance->DOR_NO }}</span> </span>
            </div>
            <div class="col-25">
                <span>Customer No:&nbsp;<span class="title-sub">{{ $advance->CUST_NO }}</span> </span>
            </div>
            <div class="col-25">
                <span>Customer Name:&nbsp;<span class="title-sub">{{ $advance->CUST_NAME }}</span> </span>
            </div>
        </div>
        <div class="col-10 border">
            <div class="col-25">
                <span>Order From:&nbsp;<span class="title-sub">{{ $advance->ORDER_FROM }}</span> </span>
            </div>
            <div class="col-25">
                <span>Order To:&nbsp;<span class="title-sub">{{ $advance->ORDER_TO }}</span> </span>
            </div>
            <div class="col-25">
                <span>Container Qty:&nbsp;<span class="title-sub">{{ $advance->CONTAINER_QTY }}</span> </span>
            </div>
            <div class="col-25">
                <span>Reasons:&nbsp;<span class="title-sub">{{ $advance->LEND_REASON }}</span> </span>
            </div>
        </div>
        @foreach ($advance_d as $item_d)
            <div class="col-10 border">
                <div class="col-2">
                    <span>STT:&nbsp;<span class="title-sub">{{ $item_d->SER_NO }}</span> </span>
                </div>
                <div class="col-2">
                    <span>Money:&nbsp;<span class="title-sub">{{ number_format($item_d->LENDER_AMT) }}</span> </span>
                </div>
                <div class="col-2">
                    <span>Person:&nbsp;<span class="title-sub">{{ $item_d->INPUT_USER }}</span> </span>
                </div>
                <div class="col-2">
                    <span>Note:&nbsp;<span class="title-sub">{{ $item_d->NOTE }}</span> </span>
                </div>
                <div class="col-2">
                    <span>Date:&nbsp;<span class="title-sub">{{ date('Y/m/d', strtotime($advance->LENDER_DATE)) }}</span> </span>
                </div>
            </div>
        @endforeach
        <span>Sum money:&nbsp;<span class="title-sub">{{number_format($advance_d->sum('LENDER_AMT'))}}</span></span>
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

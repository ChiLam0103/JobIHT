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

</style>

@foreach ($data as $item)

    <body onload="window.print();">
        <div id="page" class="page">
            <p class="title">PHIẾU CHI TẠM ỨNG</p>
            <p class="title-2">現金借支取款單</p>
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
                        <span>Job Order No:&nbsp;<span class="title-sub">{{ $item->JOB_NO }}</span></span>
                    </div>
                    <div class="col-7">
                        <span>Khách Hàng:&nbsp;{{ $item->CUST_NO }} - {{ $item->CUST_NAME }}</span>
                    </div>
                </div>
                <div class="col-10">
                    <div class="col-3">
                        <span>Số Job Đăng Ký:&nbsp;<span class="title-sub">{{ $item->JOB_NO }}</span></span>
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
                <span>金額:(Số tiền):&nbsp;<span class="title-sub"> {{ number_format($item->TOTAL_AMT) }}
                        {{ $item->DOR_NO }}</span></span>
            </div>
            <div class="col-10 border">
                <span>Số tiền bằng chữ:&nbsp;<span class="title-sub">
                        {{ $item->DOR_NO }}</span></span>
            </div>
            {{ dd($item) }}

        </div>
    </body>
@endforeach


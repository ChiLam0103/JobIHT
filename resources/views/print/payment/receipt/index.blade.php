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

    .margirn-top {
        margin-top: -1em;
    }
    .margirn-bottom {
        margin-top: -1em;
    }
    .text-center {
        text-align: center;
    }

    .title {
        position: relative;
        font-size: 20px;
        font-weight: bold;
    }

    .title-2 {
        position: relative;
        font-size: 12px;
        font-weight: bold;
    }
    .title-sign {
        font-size: 12px;
        font-weight: bold;
    }

    .title-sign-2 {
        font-size: 10px;
        font-style: italic;
    }
    .position{
        border-bottom: 1px dashed;
    }
    .col-10 {
        width: 100%;
        display: flex;

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

<body onload="window.print();">
    <div id="page" class="page">

        <div class="col-10">
            <div class="col-7">
                <span class="title">{{ $company->COMP_NAME }}</span><br>
                <span class="title-2">Add: {{ $company->COMP_ADDRESS1 }}</span><br>
                <span class="title-2">Tel: {{ $company->COMP_TEL1 }}/ {{ $company->COMP_TEL2 }};
                    Fax:{{ $company->COMP_FAX1 }}/{{ $company->COMP_FAX2 }}</span>
            </div>
            <div class="col-3">
                <span class="title">SỐ PHIẾU: {{ $receipt->RECEIPT_NO }}</span>
            </div>
        </div>
        <h2 class="title text-center">{{ $title_vn }}</h2>
        <p class="text-center margirn-top">{{ date('d-m-Y', strtotime($receipt->RECEIPT_DATE)) }}</p>
        <p>Người Nộp Tiền:&nbsp;<span class="title-2">{{ $receipt->CUST_NAME }}</span></p>
        <p>Địa Chỉ:&nbsp;<span class="title-2">{{ $receipt->CUST_ADDRESS }}</span></p>
        <p>Lý Do Nộp:&nbsp;<span class="title-2">{{ $receipt->RECEIPT_REASON }}</span></p>
        <div class="col-10 margirn-top margirn-bottom">
            <div class="col-5">
                <p>Số tiền:&nbsp;<span
                        class="title-2">{{ number_format($receipt->TOTAL_AMT) }}&nbsp;{{ $receipt->DOR_NO }}</span></p>
            </div>
            <div class="col-5">
                <p>Phí Chuyển Khoản:&nbsp;<span
                        class="title-2">{{ $receipt->TRANSFER_FEES }}&nbsp;{{ $receipt->DOR_NO }}</span></p>
            </div>
        </div>
        <p>Viết Bằng Chữ:&nbsp;<span class="title-2">{{ $receipt->RECEIPT_REASON }}</span></p>
        <div class="col-10 margirn-top">
            <div class="col-5">
                <p class="position">Kèm Theo:</p>
            </div>
            <div class="col-5">
                <p>Chứng Từ Gốc</p>
            </div>
        </div>
        <div class="col-10">
            <div class="col-2">
                <span class="title-sign">Người Lập Phiếu</span><br>
                <span class="title-sign-2">(Ký, họ tên)</span><br>
            </div>
            <div class="col-2">
                <span class="title-sign">Người Nộp Tiền</span><br>
                <span class="title-sign-2">(Ký, họ tên)</span><br>
            </div>
            <div class="col-2">
                <span class="title-sign">Thủ Quỹ</span><br>
                <span class="title-sign-2">(Ký, họ tên)</span><br>
            </div>
            <div class="col-2">
                <span class="title-sign">Kế Toán Trưởng</span><br>
                <span class="title-sign-2">(Ký, họ tên)</span><br>
            </div>
            <div class="col-2">
                <span class="title-sign">Giám Đốc</span></span><br>
                <span class="title-sign-2">(Ký, họ tên, đóng dấu)</span><br>
            </div>
        </div>
    </div>

</body>

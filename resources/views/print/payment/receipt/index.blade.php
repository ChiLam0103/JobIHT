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
        margin-bottom: -1em;
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
        font-size: 14px;
        font-weight: bold;
    }

    .title-sign {
        font-size: 16px;
        font-weight: bold;
    }

    .title-sign-2 {
        font-size: 12px;
        font-style: italic;
    }

    .position {
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
                        class="title-2">{{ number_format($receipt->TOTAL_AMT, 0, ',', '.') }}&nbsp;{{ $receipt->DOR_NO }}</span>
                </p>
            </div>
            <div class="col-5">
                <p>Phí Chuyển Khoản:&nbsp;<span
                        class="title-2">{{ $receipt->TRANSFER_FEES }}&nbsp;{{ $receipt->DOR_NO }}</span></p>
            </div>
        </div>
        {{-- <p>Viết Bằng Chữ:&nbsp;<span class="title-2""></span></p> --}}
        <div class=" col-10 margirn-top">
                <div class="col-5">
                    <p>Kèm Theo:........................................................................</p>
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
<script>
    //2. Hàm đọc số thành chữ (Sử dụng hàm đọc số có ba chữ số)

    function DocTienBangChu(SoTien) {
        var lan = 0;
        var i = 0;
        var so = 0;
        var KetQua = "";
        var tmp = "";
        var ViTri = new Array();
        if (SoTien < 0) return "Số tiền âm !";
        if (SoTien == 0) return "Không đồng !";
        if (SoTien > 0) {
            so = SoTien;
        } else {
            so = -SoTien;
        }
        if (SoTien > 8999999999999999) {
            //SoTien = 0;
            return "Số quá lớn!";
        }
        ViTri[5] = Math.floor(so / 1000000000000000);
        if (isNaN(ViTri[5]))
            ViTri[5] = "0";
        so = so - parseFloat(ViTri[5].toString()) * 1000000000000000;
        ViTri[4] = Math.floor(so / 1000000000000);
        if (isNaN(ViTri[4]))
            ViTri[4] = "0";
        so = so - parseFloat(ViTri[4].toString()) * 1000000000000;
        ViTri[3] = Math.floor(so / 1000000000);
        if (isNaN(ViTri[3]))
            ViTri[3] = "0";
        so = so - parseFloat(ViTri[3].toString()) * 1000000000;
        ViTri[2] = parseInt(so / 1000000);
        if (isNaN(ViTri[2]))
            ViTri[2] = "0";
        ViTri[1] = parseInt((so % 1000000) / 1000);
        if (isNaN(ViTri[1]))
            ViTri[1] = "0";
        ViTri[0] = parseInt(so % 1000);
        if (isNaN(ViTri[0]))
            ViTri[0] = "0";
        if (ViTri[5] > 0) {
            lan = 5;
        } else if (ViTri[4] > 0) {
            lan = 4;
        } else if (ViTri[3] > 0) {
            lan = 3;
        } else if (ViTri[2] > 0) {
            lan = 2;
        } else if (ViTri[1] > 0) {
            lan = 1;
        } else {
            lan = 0;
        }
        for (i = lan; i >= 0; i--) {
            tmp = DocSo3ChuSo(ViTri[i]);
            KetQua += tmp;
            if (ViTri[i] > 0) KetQua += Tien[i];
            if ((i > 0) && (tmp.length > 0)) KetQua += ','; //&& (!string.IsNullOrEmpty(tmp))
        }
        if (KetQua.substring(KetQua.length - 1) == ',') {
            KetQua = KetQua.substring(0, KetQua.length - 1);
        }
        KetQua = KetQua.substring(1, 2).toUpperCase() + KetQua.substring(2);
        return KetQua; //.substring(0, 1);//.toUpperCase();// + KetQua.substring(1);
    }
    console.log(DocTienBangChu(123));

</script>

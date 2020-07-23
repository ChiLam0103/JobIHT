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

    .tab {
        display: -webkit-flex;
        display: flex;
        border-bottom: 1px solid #000;
    }

    .tab-left-1 {
        -webkit-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }

    .tab-left-2 {
        -webkit-flex: 2;
        -ms-flex: 2;
        flex: 2;
    }

    .tab-right {
        -webkit-flex: 6;
        -ms-flex: 6;
        flex: 6;
    }

    nav ul {
        list-style-type: none;
        padding: 0;
    }

    .font-bold {
        font-weight: bold;
    }

    .tab-form {
        border-bottom: 1px solid #000;
    }

    .padding-left-15 {
        padding-left: 15px;
    }

    .footer th {
        text-align: center
    }

</style>

<body onload="window.print();">
    <div id="page" class="page">
        <div class="title">I.H.T LOGISTICS CO.,LTD</div>
        <div class="title-sub">TẦNG 3, TÒA NHÀ RIVERA PARK SÀI GÒN, SỐ 7/28 THÀNH THÁI, P.14, Q.10, TP.HCM <br> MST:
            0303641267</div>
        <div class="title">PHIẾU THEO DÕI </div>
        <div class="title-sub"> JOB DATE: {{ date('Y/m/d', strtotime($data->JOB_DATE)) }}</div>

        <div class="tab">
            <nav class="tab-left-1">
                <ul>
                    <li>KHÁCH HÀNG</li>
                    <li>SỐ JOB</li>
                    <li>MÃ SỐ THUẾ</li>
                    <li>ĐỊA CHỈ</li>
                </ul>
            </nav>
            <nav class="tab-right">
                <ul>
                    <li>:&nbsp; {{ $data->CUST_NAME }}</li>
                    <li class="font-bold">:&nbsp; {{ $data->JOB_NO }}</li>
                    <li class="font-bold">:&nbsp; {{ $data->CUST_TAX }}</li>
                    <li>:&nbsp; {{ $data->CUST_ADDRESS }}</li>
                </ul>
            </nav>
        </div>
        <div class="tab">
            <nav class="tab-left-2">
                <ul>
                    <li>Order From</li>
                    <li>Order To</li>
                    <li>Container No</li>
                    <li>Container Qty</li>
                    <li>Note</li>
                </ul>
            </nav>
            <nav class="tab-right">
                <ul>
                    <li>:&nbsp; {{ $data->ORDER_FROM }}</li>
                    <li>:&nbsp; {{ $data->ORDER_TO }}</li>
                    <li>:&nbsp; {{ $data->CONTAINER_NO }}</li>
                    <li>:&nbsp; {{ $data->CONTAINER_QTY }}</li>
                    <li>:&nbsp; {{ $data->NOTE }}</li>

                </ul>
            </nav>
            <nav class="tab-left">
                <ul>
                    <li>POL</li>
                    <li>POD</li>
                    <li>ETA/ETD</li>
                </ul>
            </nav>
            <nav class="tab-right">
                <ul>
                    <li>:&nbsp; {{ $data->POL }}</li>
                    <li>:&nbsp; {{ $data->POD }}</li>
                    <li>:&nbsp; {{ $data->ETA_ETD }}</li>
                </ul>
            </nav>
        </div>
        <div class="tab-form">
            <p>I. PHẦN CHỨNG TỪ</p>
            <nav class="padding-left-15">
                <ul>
                    <li>1. Nhân viên chứng từ: <span class="font-bold">{{ $data->NV_CHUNGTU_1 }}</span></li>
                    <li>2. Chứng từ gồm:</li>
                    <nav class="padding-left-15">
                        <ul>
                            <li>
                                <p>a. Tờ khai:<i class="hr"></i></p>
                            </li>
                            <li>
                                <p>b. Packing list:</p>
                            </li>
                            <li>
                                <p>c.Invoice:</p>
                            </li>
                            <li>
                                <p>d: Hợp đồng</p>
                            </li>
                            <li>
                                <p>e: Định mức</p>
                            </li>
                            <li>
                                <p>f: Bill</p>
                            </li>
                            <li>
                                <p>g: Giấy phép</p>
                            </li>
                            <li>
                                <p>h: Chứng từ khác</p>
                            </li>
                        </ul>
                    </nav>
                </ul>
            </nav>
            <p>II. PHẦN PHẦN GIAO NHẬN</p>
            <nav class="padding-left-15">
                <ul>
                    <li>1. Nhân viên giao nhận: <span class="font-bold">{{ $data->NV_GIAONHAN_2 }}</span></li>
                </ul>
            </nav>
            <p>III. PHẦN KẾ TOÁN</p>
            <nav class="padding-left-15">
                <ul>
                    <li>
                        <p>1. Nhân viên tạm ứng: </p>
                    </li>
                    <li>
                        <p>2. Số tiền tạm ứng:</p>
                    </li>
                    <li>
                        <p>3. Số tiền còn lại:</p>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="footer">
            <table style="width:100%">
                <tr>
                    <th>NV CHỨNG TỪ</th>
                    <th>NV TẠM ỨNG</th>
                    <th>NV GIAO NHẬN</th>
                    <th>KẾ TOÁN</th>
                </tr>
            </table>
        </div>
    </div>
</body>

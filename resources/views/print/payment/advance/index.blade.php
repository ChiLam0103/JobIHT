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
        border: 1px solid;
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
        text-transform: uppercase;
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

    #debit_d {
        margin-top: 1em;
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
    }

    #form-sign td {
        height: 10em;
    }

    tr td,
    tr th {
        font-size: 12px
    }

    #form-sign {
        width: 100%;
    }

    #sum-money {
        font-size: 14px;
        font-weight: bold;
        margin-left: 50%;
    }

    #lnkPrint {
        margin-top: 1em;
        background: aquamarine;
    }

</style>

<body>
    <div id="page" class="page ">
        <div class="border">
            <p class="title">Phiếu {{ $title_vn }}</p>
            <p class="title-2">{{ $title_en }}</p>
            <div class="col-10 border">
                <div class="col-25">
                    <div class="col-3"> <span>Số Job:<br> Job No:</span></div>
                    <div class="title-sub col-7">{{ $advance->JOB_NO }}</div>
                </div>
                <div class="col-25">
                    <div class="col-2"> <span>Loại:<br> Type:</span></div>
                    <div class="title-sub col-8">{{ $advance->LENDER_NAME }}</div>
                </div>
                <div class="col-25">
                    <div class="col-5"> <span>Số phiếu:<br> Advance No:</span></div>
                    <div class="title-sub col-5">{{ $advance->LENDER_NO }}</div>
                </div>
                <div class="col-25">
                    <div class="col-5"> <span>Ngày tạo:<br> Advance Date:</span></div>
                    <div class="title-sub col-5">{{ date('Y/m/d', strtotime($advance->LENDER_DATE)) }}</div>
                </div>
            </div>
            <div class="col-10 border">
                <div class="col-3">
                    <div class="col-4"> <span>Nhân viên:<br> Advance Staff:</span></div>
                    <div class="title-sub col-6">{{ $advance->PNAME }}</div>
                </div>
                <div class="col-2">
                    <div class="col-5"> <span>Mã Khách:<br> Cust No:</span></div>
                    <div class="title-sub col-5">{{ $advance->CUST_NO }}</div>
                </div>
                <div class="col-5">
                    <div class="col-2"> <span>Tên Khách:<br> Cust Name:</span></div>
                    <div class="title-sub col-8">{{ $advance->CUST_NAME }}</div>
                </div>
            </div>
            <div class="col-10 border">
                <div class="col-25">
                    <div class="col-3"> <span>Loại tiền:<br> Currency:</span></div>
                    <div class="title-sub col-7">{{ $advance->DOR_NO }}</div>
                </div>
                <div class="col-25">
                    <div class="col-4"> <span>Từ:<br> Order From:</span></div>
                    <div class="title-sub col-6">{{ $advance->ORDER_FROM }}</div>
                </div>
                <div class="col-25">
                    <div class="col-3"> <span>Đến:<br> Order To:</span></div>
                    <div class="title-sub col-7">{{ $advance->ORDER_TO }}</div>
                </div>
                <div class="col-25">
                    <div class="col-5"> <span>Số lượng:<br> Container Qty:</span></div>
                    <div class="title-sub col-5">{{ $advance->CONTAINER_QTY }}</div>
                </div>
            </div>
            <div class="col-10 border">
                <div class="col-1"> <span>Lý do:<br> Reasons:</span></div>
                <div class="title-sub col-9">{{ $advance->LEND_REASON }}</div>
            </div>
            <table style="width:100%" id="debit_d">
                <tr>
                    <th>STT</th>
                    <th>Số tiền/Amount</th>
                    <th>Nhân viên/Person</th>
                    <th>Ngày/Date</th>
                    <th>Ghi chú/Note</th>
                </tr>
                @foreach ($advance_d as $item_d)
                    <tr>
                        <td>{{ $item_d->SER_NO }}</td>
                        <td>{{ number_format($item_d->LENDER_AMT, 0, ',', '.') }}</td>
                        <td>{{ $item_d->INPUT_USER }}</td>
                        <td>{{ date('Y/m/d', strtotime($advance->LENDER_DATE)) }}</td>
                        <td>{{ $item_d->NOTE }}</td>
                    </tr>
                @endforeach
                @if ($SUM_PORT_AMT > 0)
                    <tr class="title-sub">
                        <td>0{{ count($advance_d) + 1 }}</td>
                        <td>{{ number_format($SUM_PORT_AMT, 0, ',', '.') }}</td>
                        @if ($advance->LENDER_TYPE == 'C')
                            <td>{{ $advance->PNAME }}</td>
                        @else
                            <td>{{ $INPUT_USER_jobD }}</td>
                        @endif
                        <td>{{ date('Y/m/d', strtotime($INPUT_DT_jobD)) }}</td>
                        <td>TỔNG TIỀN JOB ORDER/TOTAL AMOUNT JOB ORDER</td>
                    </tr>
                @endif
                <tr>
                    <td id="sum-money" colspan="5">
                        {{ $title_sum_money }}:&nbsp;{{ number_format($SUM_LENDER_AMT - $SUM_PORT_AMT, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
            <table id="form-sign">
                <tr>
                    <th style="width:16.6%">Tài vụ/<br> Finance</th>
                    <th style="width:16.6%">Thủ Quỹ/ <br>Cashier</th>
                    <th style="width:16.6%">Người Nhận Tiền/<br>Receiver </th>
                    <th style="width:16.6%">Duyệt/<br> Approved by</th>
                    <th style="width:16.6%">Chủ Quản Đơn Vị/<br> Department Manager</th>
                    <th style="width:16.6%">Người Xin {{ $title_vn }}/ {{ $title_en }}</th>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
        <input type="button" id="lnkPrint" value="Click the button to print the current page!">
    </div>
</body>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#lnkPrint').click(function() {
            $('#lnkPrint').hide();
            window.print();
        });
    });

</script>

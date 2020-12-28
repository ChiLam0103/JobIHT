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
            /* margin-bottom: 1cm;
        margin-top: 1cm; */
        }
    }

    .font-weight-bold {
        font-weight: bold;
    }

    .title {
        font-weight: bold;
        text-align: center;
    }

    .title p {
        /* font-size: 13px; */
        margin-top: -1em;
    }

    .title #comp_name {
        margin-top: 0.1em;
        font-size: 20px;
    }

    .title h1 {
        margin-top: 0em;
    }

    .text-center {
        text-align: center
    }

    .text-left {
        text-align: left
    }

    .text-right {
        text-align: right
    }

    #recevie p {
        text-align: center;
        font-weight: bold;
        border-bottom: 1px solid;
        margin: 0;
    }

    #recevie td:first-child,
    #info-debit-2 td:first-child {
        font-weight: bold;
    }

    #info-debit-2 td:first-child {
        width: 25%;
    }

    #info-debit td {
        padding-top: .5em;
    }

    #info-debit td:first-child {
        width: 40%;
    }

    #info-debit td:nth-child(2) {
        font-size: 16px;
        font-weight: bold;
    }

    #debit_d,
    #debit_d th,
    #debit_d td {
        border: 1px solid black;
        border-collapse: collapse;
    }

    tr td {
        padding-top: 0.2em;
        font-size: 13px
    }

    .border {
        border: 1px solid;
    }

    .col-10 {
        width: 100%;
        display: flex;
    }

    .col-9 {
        width: 90%;
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

    .col-1 {
        width: 10%;
    }

    #lnkPrint {
        margin-top: 1em;
        background: aquamarine;
    }

</style>
@foreach ($customer as $c)

    <body onload="window.print();">
        <input type="button" id="lnkPrint" value="Click the button to print the current page!">

        <div id="page" class="page">
            <div class="title">
                <p id="comp_name">{{ $company->COMP_NAME }}</p>
                <p>Add: {{ $company->COMP_ADDRESS1 }}</p>
                <p>Tel: {{ $company->COMP_TEL1 }}/ {{ $company->COMP_TEL2 }};
                    Fax:{{ $company->COMP_FAX1 }}/{{ $company->COMP_FAX2 }}</p>
                <h1>DEBIT NOTE</h1>
            </div>
            <div class="col-10 ">
                <div class="col-6 border" id="recevie">
                    <p>RECEIVE</p>
                    <table style="width:100%">
                        <tr>
                            <td>To:</td>
                            <td>{{ $c->CUST_NAME }}</td>
                        </tr>
                        <tr>
                            <td>Attn:</td>
                            <td>{{ $c->CUST_BOSS }}</td>
                        </tr>
                        <tr>
                            <td>Add:</td>
                            <td>{{ $c->CUST_ADDRESS }}</td>
                        </tr>
                        <tr>
                            <td>Tel:</td>
                            <td>{{ $c->CUST_TEL1 }}</td>
                        </tr>
                        <tr>
                            <td>Fax:</td>
                            <td>{{ $c->CUST_FAX }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-4 border" id="info-debit">
                    <table style="width:100%">
                        {{-- <tr>
                            <td>Date:</td>
                            <td>{{ date('Y/m/d', strtotime($item->DEBIT_DATE)) }}</td>
                        </tr>
                        <tr>
                            <td>Debit Note No:</td>
                            <td>{{ $item->JOB_NO }}</td>
                        </tr> --}}
                        <tr>
                            <td>Please Contact With:</td>
                            <td>{{ $person->PNL_NAME }}</td>
                        </tr>
                        <tr>
                            <td>Accountting:</td>
                            <td>{{ $phone }}</td>
                        </tr>

                    </table>
                </div>
            </div>
            <span class="font-weight-bold">We would like to make the Debit Note as follows:</span>
            <span style="display: none;"> {{ $total_vat_tax = 0 }} {{ $total_sum_amt = 0 }}</span>
            @foreach ($debit as $item)
                @if ($c->CUST_NO == $item->CUST_NO)
                    <div class="col-10  border" id="info-debit-2">
                        <div class="col-5 ">
                            <table style="width:100%">
                                <tr>
                                    <td>From:</td>
                                    <td>{{ $item->TRANS_FROM }}</td>
                                </tr>
                                <tr>
                                    <td>Customs No:</td>
                                    <td>{{ $item->CUSTOMS_NO }}</td>
                                </tr>
                                <tr>
                                    <td>NW:</td>
                                    <td>{{ $item->NW }}</td>
                                </tr>
                                <tr>
                                    <td>Job Order:</td>
                                    <td>{{ $item->JOB_NO }}</td>
                                </tr>
                                <tr>
                                    <td>QTY:</td>
                                    <td>{{ $item->CONTAINER_QTY }}</td>
                                </tr>
                                <tr>
                                    <td>Po No:</td>
                                    <td>{{ $item->PO_NO }}</td>
                                </tr>
                                <tr>
                                    <td>Container No:</td>
                                    <td>{{ $item->CONTAINER_NO }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-5 ">
                            <table style="width:100%">
                                <tr>
                                    <td>To:</td>
                                    <td>{{ $item->TRANS_TO }}</td>
                                </tr>
                                <tr>
                                    <td>Custom date:</td>
                                    <td>{{ date('Y/m/d', strtotime($item->CUSTOMS_DATE)) }}</td>
                                </tr>
                                <tr>
                                    <td>GW:</td>
                                    <td>{{ $item->GW }}</td>
                                </tr>
                                <tr>
                                    <td>Note:</td>
                                    <td>{{ $item->NOTE }}</td>
                                </tr>
                                <tr>
                                    <td>Invoices No:</td>
                                    <td>{{ $item->INVOICE_NO }}</td>
                                </tr>
                                <tr>
                                    <td>Bill No:</td>
                                    <td>{{ $item->BILL_NO }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <table style="width:100%" id="debit_d">
                        <tr>
                            <th>STT</th>
                            <th>Descriptions</th>
                            <th>Invoice No</th>
                            <th>Unit</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>VAT Tax</th>
                            <th>Total Amt</th>
                        </tr>
                        <span style="display: none;">{{ $total_amt = 0 }} {{ $total_vat = 0 }}</span>
                        @foreach ($debit_d as $item_d)
                            @if ($item->JOB_NO == $item_d->JOB_NO)
                                <tr>
                                    <td class="text-center">{{ $item_d->SER_NO }}</td>
                                    <td>{{ $item_d->DESCRIPTION }}</td>
                                    <td>{{ $item_d->INV_NO }}</td>
                                    <td class="text-center">{{ $item_d->UNIT }}</td>
                                    <td class="text-center">{{ number_format($item_d->QUANTITY, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($item_d->PRICE, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($item_d->TAX_AMT, 0, ',', '.') }}</td>
                                    <td class="text-right">{{ number_format($item_d->TOTAL_AMT, 0, ',', '.') }}</td>
                                    <span style="display: none;">{{ $total_amt += $item_d->TOTAL_AMT }}
                                        {{ $total_vat += $item_d->TAX_AMT }}
                                    </span>
                                </tr>
                            @endif
                        @endforeach
                        <tr class="text-right font-weight-bold">
                            <td colspan="6">JOB AMT</td>
                            <td>{{ number_format($total_vat, 0, ',', '.') }}</td>
                            <td>
                                {{ number_format($total_amt, 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                @endif
                <span style="display: none;">
                    {{ $total_vat_tax += $total_vat }}
                    {{ $total_sum_amt += $total_amt }}
                </span>
            @endforeach

            <table style="width:100%" id="debit_d">
                <tr class="text-right font-weight-bold">
                    <td width="71%">TOTAL AMT</td>
                    <td>{{ number_format($total_vat_tax, 0, ',', '.') }}</td>
                    <td>
                        {{ number_format($total_sum_amt, 0, ',', '.') }}
                    </td>
                </tr>
            </table>
            <span>We are looking forwards to reveiving your payment in the soonest time.</span><br>
            <span>If you have further infomation, please do not hesitate to contact with us.</span><br>
            <span>Also you can settle the payment to:</span><br>
            <div class="font-weight-bold">
                <span>Banker name: {{ $bank->BANK_NAME }}</span><br>
                <span>Account no: {{ $bank->ACCOUNT_NO }}</span><br>
                <span>Account name: {{ $bank->ACCOUNT_NAME }}</span><br>
                @if (!$bank->SWIFT_CODE)
                    <span>Swift code: {{ $bank->SWIFT_CODE }}</span>
                    <span>Bank address: {{ $bank->BANK_ADDRESS }}</span>
                    <span>Adress: {{ $bank->ADDRESS }}</span>
                @endif

            </div>
            <table style="width:100%">
                <tr>
                    <th>SALE</th>
                    <th>ACCOUNTANT</th>
                    <th>APPROVAL</th>
                </tr>
            </table>

        </div>

    </body>
@endforeach
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#lnkPrint').click(function() {
            $('#lnkPrint').hide();
            window.print();
        });
    });

</script>

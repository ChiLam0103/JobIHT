<!DOCTYPE html>
<html lang="">

<body>
    <table>
        <thead>
            <tr colspan="2" style="text-align: center">
                <td>
                    <h1>{{ $company->COMP_NAME }}</h1>
                </td>
            </tr>
            <tr colspan="6" style="text-align: left">
                <th>Add: {{ $company->COMP_ADDRESS1 }}</th>
            </tr>
            <tr colspan="6" style="text-align: left">
                <th>Tel: {{ $company->COMP_TEL1 }}/{{ $company->COMP_TEL2 }}</th>
            </tr>
            <tr colspan="6" style="text-align: left">
                <th>Fax: {{ $company->COMP_FAX1 }}/{{ $company->COMP_FAX2 }}</th>
            </tr>
        </thead>
    </table>
    <table>
        <thead>
            <tr>
                <td colspan="2" style="text-align: center">
                    <h1 style="color: yellow">DEBIT NOTE</h1>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center">
                    RECEIVE
                </td>
            </tr>
        </thead>
    </table>
    <table>
        <tr>
            <td>To:</td>
            <td>{{ $debit->CUST_NAME }}</td>
        </tr>
        <tr>
            <td>Attn:</td>
            <td>{{ $debit->CUST_BOSS }}</td>
        </tr>
        <tr>
            <td>Add:</td>
            <td>{{ $debit->CUST_ADDRESS }}</td>
        </tr>
        <tr>
            <td>Tel:</td>
            <td>{{ $debit->CUST_TEL1 }}</td>
        </tr>
        <tr>
            <td>Fax:</td>
            <td>{{ $debit->CUST_FAX }}</td>
        </tr>
    </table>
    <table>
        <tr>
            <td>Date:</td>
            <td>{{ date('Y/m/d', strtotime($debit->DEBIT_DATE)) }}</td>
        </tr>
        <tr>
            <td>Debit Note No:</td>
            <td>{{ $debit->JOB_NO }}</td>
        </tr>
        <tr>
            <td>Please Contact With:</td>
            <td>{{ $person->PNL_NAME }}</td>
        </tr>
        <tr>
            <td>Accountting:</td>
            <td>{{ $phone }}</td>
        </tr>

    </table>
    <table style="width:100%">
        <tr>
            <td>From:</td>
            <td>{{ $debit->TRANS_FROM }}</td>
        </tr>
        <tr>
            <td>Customs No:</td>
            <td>{{ $debit->CUSTOMS_NO }}</td>
        </tr>
        <tr>
            <td>NW:</td>
            <td>{{ $debit->NW }}</td>
        </tr>
        <tr>
            <td>Job Order:</td>
            <td>{{ $debit->JOB_NO }}</td>
        </tr>
        <tr>
            <td>QTY:</td>
            <td>{{ $debit->CONTAINER_QTY }}</td>
        </tr>
        <tr>
            <td>Po No:</td>
            <td>{{ $debit->PO_NO }}</td>
        </tr>
        <tr>
            <td>Container No:</td>
            <td>{{ $debit->CONTAINER_NO }}</td>
        </tr>
    </table>
    <table style="width:100%">
        <tr>
            <td>To:</td>
            <td>{{ $debit->TRANS_TO }}</td>
        </tr>
        <tr>
            <td>Custom date:</td>
            <td>{{ date('Y/m/d', strtotime($debit->CUSTOMS_DATE)) }}</td>
        </tr>
        <tr>
            <td>GW:</td>
            <td>{{ $debit->GW }}</td>
        </tr>
        <tr>
            <td>Note:</td>
            <td>{{ $debit->NOTE }}</td>
        </tr>
        <tr>
            <td>Invoices No:</td>
            <td>{{ $debit->INVOICE_NO }}</td>
        </tr>
        <tr>
            <td>Bill No:</td>
            <td>{{ $debit->BILL_NO }}</td>
        </tr>
    </table>
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
        @foreach ($debit->debit_d as $item)
            <tr>
                <td class="text-center">{{ $item['SER_NO'] }}</td>
                <td>{{ $item['DESCRIPTION'] }}</td>
                <td>{{ $item['INV_NO'] }}</td>
                <td class="text-center">{{ $item['UNIT'] }}</td>
                <td class="text-center">{{ number_format($item['QUANTITY']) }}</td>
                <td class="text-right">{{ number_format($item['PRICE']) }}</td>
                <td class="text-right">{{ number_format($item['TAX_AMT']) }}</td>
                <td class="text-right">{{ number_format($item['TOTAL_AMT']) }}</td>
                <span style="display: none;">{{ $total_amt += $item['TOTAL_AMT'] }}
                    {{ $total_vat += $item['TAX_AMT'] }}
                </span>
            </tr>

        @endforeach
        <tr class="text-right font-weight-bold">
            <td colspan="6">TOTAL AMT</td>
            <td>{{ number_format($total_vat) }}</td>
            <td>{{ number_format($total_amt) }} {{ $debit->DOR_NO }}</td>
        </tr>
    </table>
    <table>
        <tr>
            <td>We are looking forwards to reveiving your payment in the soonest time.</td>
        </tr>
        <tr>
            <td>If you have further infomation, please do not hesitate to contact with us.</td>
        </tr>
        <tr>
            <td>Also you can settle the payment to:</td>
        </tr>
        <tr>
            <th>Banker name: {{ $bank->BANK_NAME }}</th>
        </tr>
        <tr>
            <th>Account no: {{ $bank->ACCOUNT_NO }}</th>
        </tr>
        <tr>
            <th>Account name: {{ $bank->ACCOUNT_NAME }}</th>
        </tr>
        @if (trim($bank->SWIFT_CODE) != '')
            <tr>
                <th>Swift code: {{ $bank->SWIFT_CODE }}</th>
            </tr>
            <tr>
                <th>Bank address: {{ $bank->BANK_ADDRESS }}</th>
            </tr>
            <tr>
                <th>Adress: {{ $bank->ADDRESS }}</th>
            </tr>
        @endif
    </table>
    <table style="width:100%">
        <tr>
            <th>SALE</th>
            <th>ACCOUNTANT</th>
            <th>APPROVAL</th>
        </tr>
    </table>
</body>

</html>

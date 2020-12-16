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
                <th>Fax: {{ $company->COMP_FAX2 }}/{{ $company->COMP_FAX2 }}</th>
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
        </thead>
    </table>

    <table>
        <thead>
            <tr>
                <th>{{ $company->COMP_NAME }}</th>
                <th>Add: {{ $company->COMP_ADDRESS1 }}</th>
                <th>Tel: {{ $company->COMP_TEL1 }}/ {{ $company->COMP_TEL2 }};
                    Fax:{{ $company->COMP_FAX1 }}/{{ $company->COMP_FAX2 }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($debit_d as $item)
                <tr>
                    <td>{{ $item->JOB_NO }}</td>
                    <td>{{ $item->JOB_NO }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

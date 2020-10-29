<!DOCTYPE html>
<html lang="">

<body>
    <table>
        <thead>

        </thead>
    </table>
    <table>
        <thead>
            <tr>
                <td colspan="13" style="text-align: center">
                    <h1 style="color: yellow">BẢNG KÊ CÔNG NỢ </h1>
                </td>
            </tr>
        </thead>
    </table>
    <table>
        <tr>
            <th>Từ Ngày: </th>
            <td>{{ $start }}</td>
        </tr>
        <tr>
            <th>Đến Ngày: </th>
            <td>{{ $end }}</td>
        </tr>
    </table>
    <table>
        <thead>
            <tr style="background-color: #0B90C4">
                <th>STT</th>
                <th>Cust name</th>
                <th>JobNo</th>

            </tr>
        </thead>
        <tbody>
            @foreach($listResult as $k => $value)
            <tr>
                <td>{{ $k + 1 }}</td>
                <td>{{ $value->CUST_NAME ?? '' }}</td>
                <td>{{ $value->JOB_NO ?? '' }}</td>

            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

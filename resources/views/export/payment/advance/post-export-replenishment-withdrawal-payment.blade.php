<!DOCTYPE html>
<html lang="">

<body>
    <table>
        <tr>
            <td colspan="11" style="text-align: center">
                <h1 style="color: yellow">THỐNG KÊ PHIẾU BÙ TRẢ</h1>
            </td>
        </tr>
    </table>
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên</th>
                <th>Số Phiếu</th>
                <th>Nội dung</th>
                <th>Số Job</th>
                <th>Loại phiếu</th>
                <th>Mã K.H</th>
                <th>Tạm Ứng</th>
                <th>Chi Trực Tiếp</th>
                <th>Tiền Job</th>
                <th>Bù/Trả</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lender as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->INPUT_USER }}</td>
                    <td>{{ $item->LENDER_NO }}</td>
                    <td>{{ $item->LEND_REASON }}</td>
                    <td>{{ $item->JOB_NO }}</td>
                    <td>{{ $item->LENDER_TYPE == 'U' ? 'PTU' : ($item->LENDER_TYPE == 'C' ? 'CTT' : 'CTU') }}</td>
                    <td>{{ $item->CUST_NO }}</td>
                    <td>{{ $item->SUM_LENDER_AMT }}</td>
                    <td>{{ $item->SUM_DIRECT }}</td>
                    <td>{{ $item->SUM_JOB_ORDER }}</td>
                    <td>{{ $item->SUM_REPLENISHMENT_WITHDRAWAL }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="7"></td>
                <td>{{ $lender->sum('SUM_LENDER_AMT') ? $lender->sum('SUM_LENDER_AMT') :0 }}</td>
                <td>{{ $lender->sum('SUM_DIRECT') ? $lender->sum('SUM_DIRECT') :0}}</td>
                <td>{{ $lender->sum('SUM_JOB_ORDER') ? $lender->sum('SUM_JOB_ORDER') :0}}</td>
                <td>{{ $lender->sum('SUM_REPLENISHMENT_WITHDRAWAL') ? $lender->sum('SUM_REPLENISHMENT_WITHDRAWAL') :0}}</td>
            </tr>
            <tr>
                <th colspan="10" style="text-align: right">Tổng Phiếu Trả và Bù/Chi</th>
                <th>{{ $lender->sum('SUM_REPLENISHMENT_WITHDRAWAL') }}</th>
            </tr>
        </tbody>
    </table>

</body>

</html>

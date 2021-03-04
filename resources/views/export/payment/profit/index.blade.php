<!DOCTYPE html>
<html lang="">

<body>
    <table>
        <tr>
            <td colspan="7" style="text-align: center">
                <h1 style="color: yellow">BÁO CÁO LỢI NHUẬN</h1>
            </td>
        </tr>
        <tr>
            <td colspan="7" style="text-align: center"> TỪ NGÀY: {{ date('Y/m/d', strtotime($fromdate)) }} - ĐẾN
                NGÀY:{{ date('Y/m/d', strtotime($todate)) }} </td>
        </tr>
    </table>
    <table>
        <tr>
            <th>STT</th>
            <th>Job No</th>
            <th>Mã KH</th>
            <th>Tên Khách</th>
            <th>Tiền Thanh Toán</th>
            <th>Chi Phí</th>
            <th>Lợi Nhuận</th>
        </tr>
        <span style="display: none">{{ $sum_chi_phi = 0 }}{{ $sum_loi_nhuan = 0 }}</span>
        @foreach ($thanh_toan as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->JOB_NO }}</td>
                <td>{{ $item->CUST_NO }}</td>
                <td>{{ $item->CUST_NAME }}</td>
                <td> {{ $item->TIEN_THANH_TOAN }} </td>
                @foreach ($item->job_d as $item_2)
                    <td> {{ $item_2->CHI_PHI - $item_2->SUM_DEPOSIT_FEE - $item_2->SUM_DEPOSIT_FIX_FEE }}
                    </td>
                    <td> {{ $item->TIEN_THANH_TOAN - ($item_2->CHI_PHI - $item_2->SUM_DEPOSIT_FEE - $item_2->SUM_DEPOSIT_FIX_FEE) }}
                    </td>
                    <span
                        style="display: none">{{ $sum_chi_phi += $item_2->CHI_PHI - $item_2->SUM_DEPOSIT_FEE - $item_2->SUM_DEPOSIT_FIX_FEE }}
                        {{ $sum_loi_nhuan += $item->TIEN_THANH_TOAN - ($item_2->CHI_PHI - $item_2->SUM_DEPOSIT_FEE - $item_2->SUM_DEPOSIT_FIX_FEE) }}</span>
                @endforeach
                </td>
            </tr>
        @endforeach
        <tr>
            <th colspan="4" style="text-align: right">TOTAL AMT</th>
            <th>{{ $thanh_toan->sum('TIEN_THANH_TOAN') }} </th>
            <th>{{ $sum_chi_phi }} </th>
            <th>{{ $sum_loi_nhuan }}</th>
        </tr>

    </table>
</body>

</html>

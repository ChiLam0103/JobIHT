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
        @foreach ($thanh_toan as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $item->JOB_NO }}</td>
                <td>{{ $item->CUST_NO }}</td>
                <td>{{ $item->CUST_NAME }}</td>
                <td> {{ number_format($item->TIEN_THANH_TOAN, 0, ',', '.') }} </td>
                @foreach ($chi_phi as $item_2)
                    @if ($item->JOB_NO == $item_2->JOB_NO)
                        <td> {{ number_format($item_2->CHI_PHI + $item_2->SUM_PORT_AMT + $item_2->SUM_INDUSTRY_ZONE_AMT - $item_2->SUM_TAX_AMT, 0, ',', '.') }}
                        </td>
                        <td> {{ number_format($item->TIEN_THANH_TOAN - ($item_2->CHI_PHI + $item_2->SUM_PORT_AMT + $item_2->SUM_INDUSTRY_ZONE_AMT - $item_2->SUM_TAX_AMT), 0, ',', '.') }}
                        </td>
                    @endif
                @endforeach
                </td>
            </tr>
        @endforeach
        <tr>
            <th colspan="4" style="text-align: right">TOTAL AMT</th>
            <th>{{ number_format($thanh_toan->sum('TIEN_THANH_TOAN'), 0, ',', '.') }} </th>
            <th>{{ number_format($chi_phi->sum('CHI_PHI') + $chi_phi->sum('SUM_PORT_AMT') + $chi_phi->sum('SUM_INDUSTRY_ZONE_AMT') - $chi_phi->sum('SUM_TAX_AMT'), 0, ',', '.') }}
            </th>
            <th>{{ number_format($thanh_toan->sum('TIEN_THANH_TOAN') - ($chi_phi->sum('CHI_PHI') + $chi_phi->sum('SUM_PORT_AMT') + $chi_phi->sum('SUM_INDUSTRY_ZONE_AMT') - $chi_phi->sum('SUM_TAX_AMT')), 0, ',', '.') }}
            </th>
        </tr>

    </table>
</body>

</html>

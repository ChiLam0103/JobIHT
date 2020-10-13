<?php
/**
 * Created by PhpStorm.
 * User: thai
 * Date: 11/7/2018
 * Time: 3:34 PM
 */

namespace App\Services;


use Maatwebsite\Excel\Facades\Excel;

/**
 * Class ExcelService
 * @package App\Services
 */
class ExcelService
{

    public function exportDebt($listResult)
    {
        // dd($listResult);
        $start='20200101';
        $end='20200202';
        return Excel::create($start . '-'  . $end . ') '. '(' . date('dmY') . ')', function($excel) use ($listResult, $start, $end) {
            $excel->sheet('Test jon start', function($sheet) use ($listResult, $start, $end) {
                $sheet->loadView('excel', [
                    'listResult' => $listResult,
                    'start' => $start,
                    'end' => $end,
                ]);
                $sheet->setOrientation('landscape');
            });
        })->download('xlsx');
    }

}

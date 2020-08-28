<?php

namespace App\Models;
header('Content-Type: text/html; charset=utf-8');
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PrintPayment extends Model
{
    //1. in phieu theo doi
    public static function lender_AdvancePayment($fromadvance, $toadvance)
    {
        try {
            $data = DB::table('LENDER as l')
                ->leftJoin('CUSTOMER as c', 'l.CUST_NO', 'c.CUST_NO')
                ->whereBetween('l.LENDER_NO', [$fromadvance, $toadvance])
                ->select('c.CUST_NAME', 'l.*')->get();
            $str="";
            foreach ($data as $item) {
                $str = PrintPayment::convert_number_to_words((int)$item->TOTAL_AMT);
                $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');

               $item->AMT_NAME=$str;
               dd($item);
            }
            dd($data);
            return $data;
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function convert_number_to_words($number)
    {

        $hyphen      = ' ';
        $conjunction = ' ';
        $separator   = ' ';
        $negative    = 'âm ';
        $decimal     = ' phẩy ';
        $one         = 'mốt';
        $ten         = 'lẻ';
        $dictionary  = array(
            0                   => 'Không',
            1                   => 'Một',
            2                   => 'Hai',
            3                   => 'Ba',
            4                   => 'Bốn',
            5                   => 'Năm',
            6                   => 'Sáu',
            7                   => 'Bảy',
            8                   => 'Tám',
            9                   => 'Chín',
            10                  => 'Mười',
            11                  => 'Mười một',
            12                  => 'Mười hai',
            13                  => 'Mười ba',
            14                  => 'Mười bốn',
            15                  => 'Mười lăm',
            16                  => 'Mười sáu',
            17                  => 'Mười bảy',
            18                  => 'Mười tám',
            19                  => 'Mười chín',
            20                  => 'Hai mươi',
            30                  => 'Ba mươi',
            40                  => 'Bốn mươi',
            50                  => 'Năm mươi',
            60                  => 'Sáu mươi',
            70                  => 'Bảy mươi',
            80                  => 'Tám mươi',
            90                  => 'Chín mươi',
            100                 => 'trăm',
            1000                => 'ngàn',
            1000000             => 'triệu',
            1000000000          => 'tỷ',
            1000000000000       => 'nghìn tỷ',
            1000000000000000    => 'ngàn triệu triệu',
            1000000000000000000 => 'tỷ tỷ'
        );

        if (!is_numeric($number)) {
            return false;
        }

        // if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // 	// overflow
        // 	trigger_error(
        // 	'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
        // 	E_USER_WARNING
        // 	);
        // 	return false;
        // }

        if ($number < 0) {
            return $negative . PrintPayment::convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= strtolower($hyphen . ($units == 1 ? $one : $dictionary[$units]));
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= strtolower($conjunction . ($remainder < 10 ? $ten . $hyphen : null) . PrintPayment::convert_number_to_words($remainder));
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number - ($numBaseUnits * $baseUnit);
                $string = PrintPayment::convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= strtolower($remainder < 100 ? $conjunction : $separator);
                    $string .= strtolower(PrintPayment::convert_number_to_words($remainder));
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }
}

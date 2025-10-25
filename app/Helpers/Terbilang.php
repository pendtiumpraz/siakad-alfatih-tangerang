<?php

namespace App\Helpers;

class Terbilang
{
    /**
     * Convert number to Indonesian words
     *
     * @param int|float $number
     * @return string
     */
    public static function convert($number)
    {
        $number = abs($number);
        $words = [
            '', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan',
            'sepuluh', 'sebelas'
        ];

        if ($number < 12) {
            return $words[$number];
        }

        if ($number < 20) {
            return self::convert($number - 10) . ' belas';
        }

        if ($number < 100) {
            return self::convert((int)($number / 10)) . ' puluh ' . self::convert($number % 10);
        }

        if ($number < 200) {
            return 'seratus ' . self::convert($number - 100);
        }

        if ($number < 1000) {
            return self::convert((int)($number / 100)) . ' ratus ' . self::convert($number % 100);
        }

        if ($number < 2000) {
            return 'seribu ' . self::convert($number - 1000);
        }

        if ($number < 1000000) {
            return self::convert((int)($number / 1000)) . ' ribu ' . self::convert($number % 1000);
        }

        if ($number < 1000000000) {
            return self::convert((int)($number / 1000000)) . ' juta ' . self::convert($number % 1000000);
        }

        if ($number < 1000000000000) {
            return self::convert((int)($number / 1000000000)) . ' milyar ' . self::convert($number % 1000000000);
        }

        if ($number < 1000000000000000) {
            return self::convert((int)($number / 1000000000000)) . ' triliun ' . self::convert($number % 1000000000000);
        }

        return 'angka terlalu besar';
    }
}

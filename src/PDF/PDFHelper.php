<?php

namespace Partymeister\Competitions\PDF;

/**
 * Class PDFHelper
 */
class PDFHelper
{
    /**
     * @param $value
     * @return float
     */
    public static function pica2mm($value)
    {
        return round($value * 0.351, 4);
    }

    /**
     * @param    $value
     * @param  int  $dpi
     * @return float|int
     */
    public static function pixel2mm($value, $dpi = 300)
    {
        return round($value * 2.54 / $dpi, 4) * 10; // Pixel * 2,54 / 300 dpi
    }

    /**
     * @param    $value
     * @param  int  $dpi
     * @return float
     */
    public static function mm2pixel($value, $dpi = 300)
    {
        return round($value / 10 * $dpi / 2.54, 4); // Size in cm * 400 dpi / 2,54
    }

    /**
     * @param $fontsize
     * @param $pica
     * @return float|int
     */
    public static function cellheightratio($fontsize, $pica)
    {
        return $pica / $fontsize;
    }

    /**
     * @param    $value
     * @param  int  $decimals
     * @param  string  $dec_point
     * @param  string  $thousands_sep
     * @return string
     */
    public static function format_currency($value, $decimals = 2, $dec_point = ',', $thousands_sep = '.')
    {
        return @number_format($value, $decimals, $dec_point, $thousands_sep);
    }

    /**
     * @param    $value
     * @param  int  $decimals
     * @param  string  $suffix
     * @param  string  $dec_point
     * @param  string  $thousands_sep
     * @return string
     */
    public static function format_tax($value, $decimals = 2, $suffix = '%', $dec_point = ',', $thousands_sep = '.')
    {
        return number_format($value, $decimals, $dec_point, $thousands_sep).$suffix;
    }

    /**
     * @param    $date
     * @param  string  $format
     * @return string
     */
    public static function format_date($date, $format = '%d.%m.%Y')
    {
        if (! is_numeric($date)) {
            if (count(explode('-', $date)) >= 3) {
                $date = strtotime($date);
            } elseif (count(explode('.', $date)) >= 3) {
                $day = substr($date, 0, 2);
                $month = substr($date, 3, 2);
                $year = substr($date, 6, 4);
                $hours = substr($date, 11);
                $date = strtotime($year.'-'.$month.'-'.$day.' '.$hours);
            }
        }

        return strftime($format, $date);
    }
}

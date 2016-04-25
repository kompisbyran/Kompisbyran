<?php

namespace AppBundle\Util;

/**
 * Class Util
 * @package AppBundle\Util
 */
class Util
{
    /**
     * @param $string
     * @return string
     */
    public static function googleMapLink($string)
    {
        $qs = str_replace(' ', '/', $string);

        return '<a href="https://www.google.se/maps/dir/'.$qs.'">'.$string.'</a>';
    }
}

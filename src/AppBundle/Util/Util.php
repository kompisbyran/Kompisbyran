<?php

namespace AppBundle\Util;

/**
 * Class Util
 * @package AppBundle\Util
 */
class Util
{
    /**
     * @param $personArea
     * @param string $matchArea
     * @return string
     */
    public static function googleMapLink($personArea, $matchArea = '')
    {
        $matchArea = strlem(trim($matchArea))? '/' . $matchArea: '';

        return '<a href="https://www.google.se/maps/dir/'.$personArea.$matchArea.'">'.$personArea.'</a>';
    }
}

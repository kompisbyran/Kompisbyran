<?php

namespace AppBundle\Enum;

final class OccupationTypes
{
    const EMPLOYED = 'employed';
    const STUDENT = 'student';
    const UNEMPLOYED = 'unemployed';

    private function __construct()
    {
    }

    /**
     * @return array
     */
    public static function listTypesWithTranslationKeys()
    {
        return [
            self::EMPLOYED => 'occupation.employed',
            self::STUDENT => 'occupation.student',
            self::UNEMPLOYED=> 'occupation.unemployed',
        ];
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public static function tranlsationKey($type)
    {
        return self::listTypesWithTranslationKeys()[$type];
    }
}

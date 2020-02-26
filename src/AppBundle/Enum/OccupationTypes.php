<?php

namespace AppBundle\Enum;

final class OccupationTypes
{
    const EMPLOYED = 'employed';
    const STUDENT = 'student';
    const UNEMPLOYED = 'unemployed';
    const OTHER = 'other';

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
            self::OTHER=> 'occupation.other',
        ];
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public static function translationKey($type)
    {
        return self::listTypesWithTranslationKeys()[$type];
    }
}

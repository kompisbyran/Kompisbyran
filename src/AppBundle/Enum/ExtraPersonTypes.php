<?php

namespace AppBundle\Enum;

final class ExtraPersonTypes
{
    const FRIEND = 'friend';
    const CHILD = 'child';
    const PARTNER = 'partner';
    const FAMILY = 'family';

    private function __construct()
    {
    }

    /**
     * @return array
     */
    public static function listTypesWithTranslationKeys()
    {
        return [
            self::FRIEND => 'extra_person.friend',
            self::CHILD => 'extra_person.child',
            self::PARTNER => 'extra_person.partner',
            self::FAMILY => 'extra_person.family',
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

<?php

namespace AppBundle\Enum;

final class MatchingProfileRequestTypes
{
    const SAME_AGE = 'same_age';
    const SAME_GENDER = 'same_gender';

    private function __construct()
    {
    }

    /**
     * @return array
     */
    public static function listTypesWithTranslationKeys()
    {
        return [
            self::SAME_AGE => 'matching_profile_request.same_age',
            self::SAME_GENDER => 'matching_profile_request.same_gender',
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

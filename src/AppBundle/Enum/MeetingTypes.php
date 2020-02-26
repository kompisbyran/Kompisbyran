<?php

namespace AppBundle\Enum;

final class MeetingTypes
{
    const UNKNOWN = 'unknown';
    const NOT_YET_MET = 'not_yet_met';
    const MET = 'met';
    const WILL_NOT_MEET = 'will_not_meet';

    private function __construct()
    {
    }

    /**
     * @return array
     */
    public static function listTypesWithTranslationKeys()
    {
        return [
            self::UNKNOWN => 'meeting_type.unknown',
            self::NOT_YET_MET => 'meeting_type.not_yet_met',
            self::MET => 'meeting_type.met',
            self::WILL_NOT_MEET => 'meeting_type.will_not_meet',
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

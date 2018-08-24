<?php

namespace AppBundle\Enum;

final class FriendTypes
{
    const FRIEND = 'friend';
    const MUSIC = 'music';
    const START = 'start';

    private function __construct()
    {
    }

    /**
     * @return array
     */
    public static function listTypesWithTranslationKeys()
    {
        return [
            self::FRIEND => 'global.fika_buddy',
            self::MUSIC => 'global.music_buddy',
            self::START => 'global.start_buddy',
        ];
    }

    /**
     * @return array
     */
    public static function listActiveTypesWithTranslationKeys()
    {
        return [
            self::FRIEND => 'global.fika_buddy',
            self::START => 'global.start_buddy',
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

<?php

namespace AppBundle\Enum;

final class RoleTypes
{
    const USER = 'ROLE_COMPLETE_USER';
    const MUNICIPALITY_ADMIN = 'ROLE_MUNICIPALITY';
    const ADMIN = 'ROLE_ADMIN';
    const SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    private function __construct()
    {
    }

    /**
     * @return array
     */
    public static function listTypesWithTranslationKeys()
    {
        return [
            self::USER => 'role.user',
            self::MUNICIPALITY_ADMIN => 'role.municipality_admin',
            self::ADMIN => 'role.admin',
            self::SUPER_ADMIN => 'role.super_admin',
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

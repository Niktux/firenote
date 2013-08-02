<?php

namespace Firenote\User;

class Roles
{
    public static function getAll()
    {
        return array_keys(self::getRolesCSSLabel());
    }
    
    public static function getRolesCSSLabel()
    {
        return array(
            'ROLE_ADMIN' => 'info',
            'ROLE_EDITOR' => 'success',
            'ROLE_USER' => 'danger',
            'ROLE_MODERATOR' => 'important',
            'ROLE_ANONYMOUS' => 'inverse',
        );
    }
}
<?php

namespace Firenote\User;

use Symfony\Component\Security\Core\User\UserInterface;

class UserInfo extends UserDelegate implements UserInfoInterface
{
    private
        $avatar,
        $lastLogin,
        $connectionCount;
    
    public function __construct(UserInterface $user, $avatar, $lastLogin = null, $connectionCount = null)
    {
        parent::__construct($user);
        
        $this->avatar = $avatar;
        $this->lastLogin = $lastLogin;
        $this->connectionCount = $connectionCount;
    }

    public function getAvatar()
    {
        return $this->avatar;
    }

    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    public function getConnectionCount()
    {
        return $this->connectionCount;
    }
}
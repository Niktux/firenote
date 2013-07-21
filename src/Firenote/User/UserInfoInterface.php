<?php

namespace Firenote\User;

use Symfony\Component\Security\Core\User\UserInterface;

interface UserInfoInterface extends UserInterface
{
    public function getAvatar();
    public function getLastLogin();
    public function getConnectionCount();
}
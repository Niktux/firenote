<?php

namespace Firenote\User;

interface UserProviderInterface extends \Symfony\Component\Security\Core\User\UserProviderInterface
{
    public function listAll();
}
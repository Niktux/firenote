<?php

namespace Firenote\User;

use Symfony\Component\Security\Core\User\UserInterface;

abstract class UserDelegate implements UserInterface
{
    private
        $user;
    
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }
    
    public function getRoles()
    {
        return $this->user->getRoles();
    }
    
    public function getPassword()
    {
        return $this->user->getPassword();
    }
    
    public function getSalt()
    {
        return $this->user->getSalt();
    }
    
    public function getUsername()
    {
        return $this->user->getUsername();
    }
    
    public function eraseCredentials()
    {
        return $this->user->eraseCredentials();
    }
}
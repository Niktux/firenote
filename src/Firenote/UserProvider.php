<?php

namespace Firenote;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\DBAL\Driver\Connection;

class UserProvider implements UserProviderInterface
{
    private
        $db;
    
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }
    
    public function loadUserByUsername($username)
    {
        $stmt = $this->db->executeQuery(
            'SELECT * FROM comptes WHERE login = ?',
            array(strtolower($username))
        );
    
        if (!$user = $stmt->fetch())
        {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }
    
        return new User($user['login'], $user['encpass'], explode(',', $user['roles']), true, true, true, true);
    }
    
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User)
        {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }
    
        return $this->loadUserByUsername($user->getUsername());
    }
    
    public function supportsClass($class)
    {
        return $class === 'Symfony\Component\Security\Core\User\User';
    }
}
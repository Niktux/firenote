<?php

namespace Firenote\User;

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
            'SELECT * FROM users WHERE username = ?',
            array(strtolower($username))
        );
    
        if (!$user = $stmt->fetch())
        {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }
        
        return new UserInfo(
            new User($user['username'], $user['password'], explode(',', $user['roles']), true, true, true, true),
            $user['avatar'], null, 0
        );
    }
    
    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }
    
    public function supportsClass($class)
    {
        return (
           $class === 'Symfony\Component\Security\Core\User\User'
        || $class === 'Firenote\User\UserInfo' );
    }
    
    public function listAll()
    {
        $stmt = $this->db->executeQuery('SELECT * FROM users');
    
        $users = array();
        while($user = $stmt->fetch())
        {
            $users[] = new User(
                $user['username'], $user['password'], explode(',', $user['roles']), true, true, true, true
            );
        }
        
        return $users;
    }
}
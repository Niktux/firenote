<?php

namespace Firenote\Controllers\Users;

use Symfony\Component\HttpFoundation\Response;

class Controller extends \Firenote\Controllers\AbstractController
{
    private
        $userProvider;
    
    public function __construct(\Firenote\ControllerAPI $api, \Firenote\User\UserProvider $userProvider)
    {
        parent::__construct($api, 'Users', '/admin/users/list');
        
        $this->userProvider = $userProvider;
    }
    
    public function usersAction()
    {
        return $this->renderResponse('List', 'pages/users/list.twig', array(
            'users' => $this->userProvider->listAll(),
        ));
    }
    
    public function createAction()
    {
        return $this->renderResponse('Create', 'pages/users/create.twig', array(
            // FIXME
            'roles' => array(
                'ROLE_ADMIN', 'ROLE_EDITOR', 'ROLE_USER',
            )
        ));
    }
    
    public function registerAction()
    {
        return $this->renderResponse('Register', 'pages/post.twig', array(
            'vars' => $_POST
        ));
    }
    
    public function profileAction($username)
    {
        return $this->renderResponse('View', 'pages/users/profile.twig', array(
            'profile' => $this->userProvider->loadUserByUsername($username),
        ));
    }
}
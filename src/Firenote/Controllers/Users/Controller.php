<?php

namespace Firenote\Controllers\Users;

use Symfony\Component\HttpFoundation\Response;

class Controller extends \Firenote\Controllers\AbstractController
{
    private
        $userProvider;
    
    public function __construct(\Firenote\User\UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }
    
    public function onInitialize()
    {
        $this->page->addBreadcrumb('Users', '/admin/users/list');
    }
    
    public function usersAction()
    {
        return $this->page
            ->setPageLabel('List')
            ->render('pages/users/list.twig', array(
                'users' => $this->userProvider->listAll(),
        ));
    }
    
    public function createAction()
    {
        return $this->page
            ->setPageLabel('Create')
            ->render('pages/users/create.twig', array(
                //FIXME
                'roles' => array('ROLE_ADMIN', 'ROLE_EDITOR', 'ROLE_USER')
        ));
    }
    
    public function registerAction()
    {
        return $this->page
            ->setPageLabel('Register')
            ->render('pages/post.twig', array(
                'vars' => $_POST
        ));
    }
    
    public function profileAction($username)
    {
        return $this->page
            ->setPageLabel('View')
            ->render('pages/users/profile.twig', array(
                'profile' => $this->userProvider->loadUserByUsername($username),
        ));
    }
}
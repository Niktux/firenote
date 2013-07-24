<?php

namespace Firenote\Controllers\Users;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Firenote\FileUploadHandler;

class Controller extends \Firenote\Controllers\AbstractController
{
    private
        $userProvider,
        $upload;
    
    public function __construct(\Firenote\User\UserProvider $userProvider, FileUploadHandler $upload)
    {
        $this->userProvider = $userProvider;
        $this->upload = $upload;
    }
    
    public function onInitialize()
    {
        $this->page->addBreadcrumb('Users', 'users_list');
    }
    
    public function usersAction()
    {
        return $this->page
            ->setTitle('List')
            ->render('pages/users/list.twig', array(
                'users' => $this->userProvider->listAll(),
        ));
    }
    
    public function createAction()
    {
        return $this->page
            ->setTitle('Create')
            ->render('pages/users/create.twig', array(
                //FIXME
                'roles' => array('ROLE_ADMIN', 'ROLE_EDITOR', 'ROLE_USER')
        ));
    }
    
    public function registerAction()
    {
        // FIXME filter security
        $user = $this->createUser($this->request);
        
        if($user instanceof UserInterface)
        {
            $this->session->getFlashBag()->add('success', 'User was successfully created');
        }
        else
        {
            $this->session->getFlashBag()->add('danger', 'ERROR : User was NOT created');
        }
        
        return $this->redirect('users_list');
    }
    
    // FIXME draft without any checks
    private function createUser(Request $request)
    {
        if($request->get('password') !== $request->get('password2'))
        {
            // FIXME double flash
            $this->session->getFlashBag()->add('danger', 'Password mismatch');
            
            return null;
        }
        
        $avatar = $this->upload->retrieve('avatar', array('png', 'jpg', 'jpeg', 'gif'));
        $avatar = substr($avatar, strpos($avatar, '/var/'));
        
        return $this->userProvider->register(
            $request->get('login'),
            $request->get('password'),
            array($request->get('roles')),
            $avatar
        );
    }
    
    public function profileAction($username)
    {
        return $this->page
            ->setTitle('View')
            ->render('pages/users/profile.twig', array(
                'profile' => $this->userProvider->loadUserByUsername($username),
        ));
    }
}
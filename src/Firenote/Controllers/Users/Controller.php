<?php

namespace Firenote\Controllers\Users;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Firenote\FileUploadHandler;
use Firenote\User\Roles;
use Firenote\Exceptions\User as Exceptions;

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
                'roleColors' => Roles::getRolesCSSLabel(),
        ));
    }
    
    public function createAction()
    {
        return $this->page
            ->setTitle('Create')
            ->render('pages/users/create.twig', array(
                'roles' => Roles::getAll(),
        ));
    }
    
    public function deleteConfirmationAction($username)
    {
        return $this->page
            ->setTitle('Delete')
            ->render('pages/users/delete.twig', array(
                'profile' => $this->userProvider->loadUserByUsername($username),
        ));
    }
    
    public function deleteAction($username)
    {
        $success = $this->userProvider->delete($username);
        
        $this->addOperationStatusFlash(
            $success,
            'User was successfully deleted',
            'ERROR : User was NOT deleted'
        );
        
        return $this->redirect('users_list');
    }
    
    public function registerAction()
    {
        try
        {
            $user = $this->createUser($this->request);
        }
        catch(\Firenote\Exception $e)
        {
            $this->addFlash('danger', $e->getMessage());
            
            return $this->redirect('users_create');
        }

        $this->addOperationStatusFlash(
            $user instanceof UserInterface,
            'User was successfully created',
            'ERROR : User was NOT created'
        );
        
        return $this->redirect('users_list');
    }
    
    // FIXME draft without any checks
    private function createUser(Request $request)
    {
        $this->checkRequiredFields($request);
        
        if($request->get('password') !== $request->get('password2'))
        {
            throw new Exceptions\UserCreation('Password mismatch');
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
    
    private function checkRequiredFields(Request $request)
    {
        $requiredFields = array(
            'login',
            'password',
            'password2',
            'roles',
        );
        
        foreach($requiredFields as $field)
        {
            if($request->request->has($field) === false)
            {
                throw new Exceptions\UserCreation($field . ' must be filled');
            }
            
            $value = $request->get($field);
            if(empty($value))
            {
                throw new Exceptions\UserCreation($field . ' must be filled');
            }
        }
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
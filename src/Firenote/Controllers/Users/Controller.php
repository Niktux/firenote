<?php

namespace Firenote\Controllers\Users;

use Symfony\Component\HttpFoundation\Response;

class Controller extends \Firenote\Controllers\AbstractController
{
    private
        $userProvider;
    
    public function __construct(\Firenote\ControllerAPI $api, \Firenote\UserProvider $userProvider)
    {
        parent::__construct($api);
        
        $this->userProvider = $userProvider;
    }
    
    public function usersAction()
    {
        return $this->renderResponse('pages/users/list.twig', array(
            'users' => $this->userProvider->listAll(),
        ));
    }
    
    public function createAction()
    {
        return $this->renderResponse('pages/users/create.twig', array(
            // FIXME
            'roles' => array(
                'ROLE_ADMIN', 'ROLE_EDITOR',
            )
        ));
    }
}
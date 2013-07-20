<?php

namespace Firenote\Controllers\Admin;

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
    
    public function indexAction()
    {
        return $this->renderResponse('layouts/admin.twig', array());
    }
    
    public function usersAction()
    {
        return $this->renderResponse('pages/users.twig', array(
            'users' => $this->userProvider->listAll(),
        ));
    }
}
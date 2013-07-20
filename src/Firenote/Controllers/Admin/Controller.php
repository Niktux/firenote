<?php

namespace Firenote\Controllers\Admin;

use Symfony\Component\HttpFoundation\Response;

class Controller extends \Firenote\Controllers\AbstractController
{
    public function indexAction()
    {
        return $this->renderResponse('layouts/admin.twig', array());
    }
    
    public function usersAction()
    {
        return $this->renderResponse('layouts/admin.twig', array(
            'users' => array(),
        ));
    }
}
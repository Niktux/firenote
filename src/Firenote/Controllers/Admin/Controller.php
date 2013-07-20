<?php

namespace Firenote\Controllers\Admin;

use Symfony\Component\HttpFoundation\Response;

class Controller extends \Firenote\Controllers\AbstractController
{
    public function indexAction()
    {
        return $this->renderResponse('layout.twig', array());
    }
    
    public function usersAction()
    {
        return $this->renderResponse('layout.twig', array(
            'users' => array(),
        ));
    }
}
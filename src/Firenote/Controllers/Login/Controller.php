<?php

namespace Firenote\Controllers\Login;

use Symfony\Component\HttpFoundation\Response;

class Controller extends \Firenote\Controllers\AbstractController
{
    public function loginAction()
    {
        return $this->renderResponse('Login', 'pages/login.twig', array());
    }
}
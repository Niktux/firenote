<?php

namespace Firenote\Controllers;

use Firenote\AdminLayout;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

abstract class AbstractController
{
    protected
        $db,
        $twig,
        $layout,
        $request;
    
    public function __construct(\Doctrine\DBAL\Driver\Connection $db, \Twig_Environment $twig, Request $request, AdminLayout $layout, UsernamePasswordToken $token)
    {
        $this->db = $db;
        $this->twig = $twig;
        $this->request = $request;
        $this->layout = $layout;
        
        if($token !== null)
        {
            $this->layout->setUser($token->getUser());
        }
    }
    
    protected function renderResponse($template, $variables)
    {
        $variables = array_merge($variables, $this->layout->getVariables());
        
        return new Response($this->twig->render(
            $template,
            $variables
        ));
    }
}
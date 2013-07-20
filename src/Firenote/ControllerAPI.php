<?php

namespace Firenote;

use Symfony\Component\HttpFoundation\Response;

class ControllerAPI
{
    private
        $db,
        $twig,
        $layout,
        $request;
    
    public function __construct(\Pimple $app)
    {
        $this->db = $app['db'];
        $this->twig = $app['twig'];
        $this->request = $app['request'];
        $this->layout = $app['layout'];
        
        $token = $app['security']->getToken();
        if($token !== null)
        {
            $this->layout->setUser($token->getUser());
        }
    }
    
    public function renderResponse($template, $variables = array())
    {
        $variables = array_merge($variables, $this->layout->getVariables());
    
        return new Response($this->twig->render(
            $template,
            $variables
        ));
    }

    public function getLayout()
    {
        return $this->layout;
    }

    public function getRequest()
    {
        return $this->request;
    }
}
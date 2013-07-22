<?php

namespace Firenote;

use Symfony\Component\HttpFoundation\Response;

class ControllerAPI
{
    private
        $layout,
        $request;
    
    public function __construct(\Pimple $app)
    {
        $this->request = $app['request'];
        $this->layout = $app['layout'];
        
        $token = $app['security']->getToken();
        if($token !== null)
        {
            $this->layout->setUser($token->getUser());
        }
    }

    public function getRequest()
    {
        return $this->request;
    }
}
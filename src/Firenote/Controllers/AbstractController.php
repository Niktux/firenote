<?php

namespace Firenote\Controllers;

abstract class AbstractController
{
    protected
        $api,
        $request,
        $layout;
    
    public function __construct(\Firenote\ControllerAPI $api)
    {
        $this->api = $api;
        
        $this->request = $this->api->getRequest();
        $this->layout = $this->api->getLayout();
    }
    
    protected function renderResponse($template, $variables = array())
    {
        return $this->api->renderResponse($template, $variables);
    }
}
<?php

namespace Firenote\Controllers;

abstract class AbstractController
{
    protected
        $api,
        $request,
        $layout;
    
    public function __construct(\Firenote\ControllerAPI $api, $pagegroupName = null, $pagegroupPath = null)
    {
        $this->api = $api;
        
        $this->request = $this->api->getRequest();
        $this->layout = $this->api->getLayout();
        
        if($pagegroupName !== null)
        {
            $this->layout->addBreadcrumb($pagegroupName, $pagegroupPath);
        }
    }
    
    protected function renderResponse($pageLabel, $template, $variables = array())
    {
        $this->layout->setPageLabel($pageLabel);
        
        return $this->api->renderResponse($template, $variables);
    }
}
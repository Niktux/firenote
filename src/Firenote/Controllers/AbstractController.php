<?php

namespace Firenote\Controllers;

use Firenote\Pages\AdminPage;
use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController implements \Firenote\Controller
{
    protected
        $page,
        $request;
    
    public function setPage(AdminPage $page)
    {
        $this->page = $page;
        
        return $this;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
        
        return $this;
    }
    
    public function onInitialize()
    {
    }
}
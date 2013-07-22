<?php

namespace Firenote\Controllers;

use Firenote\Pages\AdminPage;

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

    public function setRequest($request)
    {
        $this->request = $request;
        
        return $this;
    }
    
    public function onInitialize()
    {
    }
}
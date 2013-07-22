<?php

namespace Firenote\Controllers;

use Firenote\Pages\AdminPage;

abstract class AbstractController
{
    protected
        $api,
        $page,
        $request;
    
    public function __construct(\Firenote\ControllerAPI $api, AdminPage $page)
    {
        $this->api = $api;
        $this->page = $page;
        $this->request = $this->api->getRequest();
    }
}
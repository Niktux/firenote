<?php

namespace Firenote;

use Firenote\Pages\AdminPage;
use Symfony\Component\HttpFoundation\Request;

interface Controller
{
    public function setPage(AdminPage $page);
    public function setRequest(Request $request);
    
    public function onInitialize();
}
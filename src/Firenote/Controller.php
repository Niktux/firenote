<?php

namespace Firenote;

use Firenote\Pages\AdminPage;

interface Controller
{
    public function setPage(AdminPage $page);
    public function setRequest($request);
    
    public function onInitialize();
}
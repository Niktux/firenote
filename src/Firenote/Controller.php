<?php

namespace Firenote;

use Firenote\Pages\AdminPage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

interface Controller
{
    public function setPage(AdminPage $page);
    public function setRequest(Request $request);
    public function setUrlGenerator(UrlGeneratorInterface $urlGenerator);
    
    public function onInitialize();
}
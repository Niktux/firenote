<?php

namespace Firenote\Controllers;

use Firenote\Pages\AdminPage;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;

abstract class AbstractController implements \Firenote\Controller
{
    private
        $urlGenerator;
    
    protected
        $page,
        $request,
        $session;
    
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
    
    public function setUrlGenerator(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
        
        return $this;
    }

    public function setSession(Session $session)
    {
        $this->session = $session;
        
        return $this;
    }
    
    protected function redirect($route, $routeParameters = array())
    {
        return new RedirectResponse(
            $this->urlGenerator->generate($route, $parameters)
        );
    }
    
    public function onInitialize()
    {
    }
}
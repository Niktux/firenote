<?php

namespace Firenote\Controllers;

use Symfony\Component\HttpFoundation\Request;

abstract class AbstractController
{
    protected
        $db,
        $twig,
        $request;
    
    public function __construct(\Doctrine\DBAL\Driver\Connection $db, \Twig_Environment $twig, Request $request)
    {
        $this->db = $db;
        $this->twig = $twig;
        $this->request = $request;
    }
}
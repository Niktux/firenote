<?php

namespace Firenote\Pages;

use Firenote\AdminLayout;

class AdminPage extends HtmlPage
{
    private
        $breadcrumbs,
        $title,
        $layout;
    
    public function __construct(\Twig_Environment $twig, AdminLayout $layout)
    {
        parent::__construct($twig);
        
        $this->breadcrumbs = array();
        $this->addBreadcrumb('Admin', '/admin');
        
        $this->title = 'Blank page';
        $this->layout = $layout;
    }
    
    protected function getVariables()
    {
        return array_merge(parent::getVariables(), $this->layout->getVariables(), array(
            'breadcrumbs' => $this->breadcrumbs,
            'title' => $this->title,
        ));
        
        return $variables;
    }
    
    public function addBreadcrumb($title, $path)
    {
        $this->breadcrumbs[] = array(
            'title' => $title,
            'path' => $path,
        );
    
        return $this;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }
}
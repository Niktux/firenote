<?php

namespace Firenote\Pages;

use Firenote\AdminLayout;

class AdminPage extends HtmlPage
{
    private
        $breadcrumbs,
        $pageLabel,
        $layout;
    
    public function __construct(\Twig_Environment $twig, AdminLayout $layout)
    {
        parent::__construct($twig);
        
        $this->breadcrumbs = array();
        $this->addBreadcrumb('Admin', '/admin');
        
        $this->pageLabel = 'Blank page';
        $this->layout = $layout;
    }
    
    protected function getVariables()
    {
        return array_merge(parent::getVariables(), $this->layout->getVariables(), array(
            'breadcrumbs' => $this->breadcrumbs,
            'pageLabel' => $this->pageLabel,
        ));
        
        return $variables;
    }
    
    public function addBreadcrumb($label, $path)
    {
        $this->breadcrumbs[] = array(
            'label' => $label,
            'path' => $path,
        );
    
        return $this;
    }

    public function setPageLabel($pageLabel)
    {
        $this->pageLabel = $pageLabel;
    
        return $this;
    }
}
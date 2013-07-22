<?php

namespace Firenote\Pages;

class HtmlPage extends Firenote\Page
{
    private
        $stylesheets,
        $scripts;

    public function __construct(\Twig_Environment $twig)
    {
        parent::__construct($twig);
        
        $this->stylesheets = array();
        $this->scripts = array();
    }
    
    public function addCss($cssFile)
    {
        $this->stylesheets[] = $cssFile;
        
        return $this;
    }
    
    public function addJs($jsFile)
    {
        $this->scripts = $jsFile;
        
        return $this;
    }
    
    protected function getVariables()
    {
        return array_merge(parent::getVariables(), array(
            'css' => $this->stylesheets,
            'js' => $this->scripts,
        ));
    }
}
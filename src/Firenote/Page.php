<?php

namespace Firenote;

use Symfony\Component\HttpFoundation\Response;

class Page
{
    private
        $variables,
        $twig;
    
    public function __construct(\Twig_Environment $twig)
    {
        $this->variables = $variables;
    }
    
    protected function getVariables()
    {
        return $this->variables;
    }
    
    public function addVariable($name, $value)
    {
        $this->variables[$name] = $value;
        
        return $this;
    }
    
    public function render($view, array $variables)
    {
        $variables = array_merge($variables, $this->getVariables());
    
        return new Response($this->twig->render(
            $view,
            $variables
        ));
    }
}
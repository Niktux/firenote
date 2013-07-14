<?php

namespace Firenote\Layout\Menu;

class Root extends Item
{
    private
        $submenus;
    
    public function __construct($label, $icon = null)
    {
        parent::__construct($label, $icon);
        
        $this->submenus = array();
    }
    
    public function add(\Firenote\Layout\Menu $submenu)
    {
        $this->submenus[] = $submenu;
        
        return $this;
    }
    
    public function getSubmenus()
    {
        return $this->submenus;
    }
    
    public function hasSubmenus()
    {
        return count($this->submenus) > 0;
    }
}
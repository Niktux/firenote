<?php

namespace Firenote\Layout\Menu;

class Item implements \Firenote\Layout\Menu
{
    private
        $label,
        $icon;

    public function __construct($label, $icon = null)
    {
        $this->label = $label;
        $this->icon  = $icon !== null ? 'icon-' . $icon : null;
    }
    
    public function getLabel()
    {
        return $this->label;
    }

    public function getIcon()
    {
        return $this->icon;
    }
    
    public function getLink()
    {
        return null;
    }
    
    public function hasSubmenus()
    {
        return false;
    }
}
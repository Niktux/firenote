<?php

namespace Firenote;

class AdminLayout
{
    const
        MAX_SHORTCUTS = 4;
    
    private
        $shortcutStyles,
        $shortcuts,
        $menus;
    
    public function __construct()
    {
        $this->shortcutStyles = array('success', 'info', 'warning', 'danger');
        $this->shortcuts = array();
        $this->menus = array();
    }
    
    public function getVariables()
    {
        return array(
            'menus' => $this->menus,
            'shortcuts' => $this->shortcuts,
        );
    }
    
    public function addMenu(Layout\Menu $menu)
    {
        $this->menus[] = $menu;
        
        return $this;
    }
    
    public function addShortcut($link, $icon, $legend = '')
    {
        static $i = 0;
        
        $this->shortcuts[] = array(
            'style'  => $this->shortcutStyles[$i % self::MAX_SHORTCUTS],
            'icon'   => 'icon-' . $icon,
            'link'   => $link,
            'legend' => $legend,
        );
        
        $i++;
        
        return $this;
    }
    
    public function getShortcuts()
    {
        return iterator_to_array(new \LimitIterator(new \ArrayIterator($this->shortcuts), self::MAX_SHORTCUTS));
    }
}
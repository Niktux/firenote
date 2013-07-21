<?php

namespace Firenote;

class AdminLayout
{
    const
        MAX_SHORTCUTS = 4;
    
    private
        $user,
        $breadcrumbs,
        $shortcutStyles,
        $shortcuts,
        $menus,
        $appName,
        $pageLabel;
    
    public function __construct($appName = 'Firenote')
    {
        $this->shortcutStyles = array('success', 'info', 'warning', 'danger');
        $this->shortcuts = array();
        $this->menus = array();
        $this->user = null;
        $this->appName = $appName;
        $this->breadcrumbs = array();
        $this->addBreadcrumb('Admin', '/admin');
        $this->pageLabel = '';
    }
    
    public function getVariables()
    {
        return array(
            'appName' => $this->appName,
            'menus' => $this->menus,
            'shortcuts' => $this->shortcuts,
            'user' => $this->user,
            'breadcrumbs' => $this->breadcrumbs,
            'pageLabel' => $this->pageLabel,
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

    public function setUser($user)
    {
        $this->user = $user;
        
        return $this;
    }

    public function setAppName($appName)
    {
        $this->appName = $appName;
        
        return $this;
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
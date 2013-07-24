<?php

namespace Firenote;

use Symfony\Component\HttpFoundation\Session\Session;

class AdminLayout
{
    const
        MAX_SHORTCUTS = 4;
    
    private
        $session,
        $user,
        $shortcutStyles,
        $shortcuts,
        $menus,
        $containers,
        $applicationInfo;
    
    public function __construct(Session $session)
    {
        $this->session = $session;
        
        $this->shortcutStyles = array('success', 'info', 'warning', 'danger');
        $this->shortcuts = array();
        $this->menus = array();
        
        $this->containers = array();
        $this->user = null;
        $this->applicationInfo = array(
            'name' => 'Firenote',
            'icon' => 'fire',
            'color' => 'red',
        );
    }
    
    public function getVariables()
    {
        return array(
            'app' => $this->applicationInfo,
            'menus' => $this->menus,
            'shortcuts' => $this->shortcuts,
            'containers' => $this->containers,
            'user' => $this->user,
            'flashes' => $this->session->getFlashBag()->all(),
        );
    }
    
    public function addMenu(Layout\Menu $menu)
    {
        $this->menus[] = $menu;
        
        return $this;
    }
    
    public function addContainer(NavBarContainer $container)
    {
        $this->containers[] = $container;
        
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
    
    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
        
        return $this;
    }

    public function setAppName($appName)
    {
        $this->applicationInfo['name'] = $appName;
        
        return $this;
    }
    
    public function setAppIcon($icon)
    {
        $this->applicationInfo['icon'] = $icon;
        
        return $this;
    }

    public function setAppColor($color)
    {
        $this->applicationInfo['color'] = $color;
        
        return $this;
    }
}
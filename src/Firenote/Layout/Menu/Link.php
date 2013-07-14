<?php

namespace Firenote\Layout\Menu;

class Link extends Item
{
    private
        $link;
    
    public function __construct($label, $link, $icon = null)
    {
        parent::__construct($label, $icon);
        
        $this->link = $link;
    }

    public function getLink()
    {
        return $this->link;
    }

}
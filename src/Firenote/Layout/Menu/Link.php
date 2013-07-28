<?php

namespace Firenote\Layout\Menu;

class Link extends Item
{
    private
        $link,
        $linkParameters;
    
    public function __construct($label, $link, $icon = null)
    {
        parent::__construct($label, $icon);

        $this->linkParameters = array();
        if(is_array($link))
        {
            if(count($link) < 2)
            {
                throw new \Firenote\Exception('Menu link route is not valid');
            }
            
            $this->link = $link[0];
            $this->linkParameters = $link[1];
        }
        else
        {
            $this->link = $link;
        }
    }

    public function getLink()
    {
        return $this->link;
    }
    
    public function getLinkParameters()
    {
        return $this->linkParameters;
    }
}
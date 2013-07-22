<?php

namespace Firenote\NavBarContainers\Tasks;

class Task
{
    public
        $title,
        $type,
        $link,
        $percent,
        $active;
    
    public function __construct($title, $type, $link = null, $percent = 0, $active = false)
    {
        $this->title = $title;
        $this->type = $type;
        $this->percent = $percent;
        $this->active = $active;
        $this->link = $link;
    }
}
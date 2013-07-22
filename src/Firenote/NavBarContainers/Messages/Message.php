<?php

namespace Firenote\NavBarContainers\Messages;

class Message
{
    public
        $username,
        $text,
        $datetime,
        $avatar;
    
    public function __construct($username, $text, \DateTime $datetime = null, $avatar = null)
    {
        $this->username = $username;
        $this->text = $text;
        $this->avatar = $avatar;
        $this->datetime = $datetime;
        
        if($this->datetime === null)
        {
            $this->datetime = new \DateTime();
        }
    }
}
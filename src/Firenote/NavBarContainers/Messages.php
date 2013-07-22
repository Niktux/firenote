<?php

namespace Firenote\NavBarContainers;

class Messages implements \Firenote\NavBarContainer
{
    private
        $messages;
    
    public function __construct(array $messages = array())
    {
        $this->messages = $messages;
    }
    
    public function AddMessage(Messages\Message $message)
    {
        $this->messages[] = $message;
    
        return $this;
    }
    
    public function getView()
    {
        return 'containers/messages.twig';
    }
    
    public function getMessages()
    {
        return $this->messages;
    }
}
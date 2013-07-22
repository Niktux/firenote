<?php

namespace Firenote\NavBarContainers;

class Tasks implements \Firenote\NavBarContainer
{
    private
        $tasks;
    
    public function __construct(array $tasks = array())
    {
        $this->tasks = $tasks;
    }
    
    public function addTask(Tasks\Task $task)
    {
        $this->tasks[] = $task;
        
        return $this;
    }
    
    public function getView()
    {
        return 'containers/tasks.twig';
    }
    
    public function getTasks()
    {
        return $this->tasks;
    }
}
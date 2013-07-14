<?php

namespace Firenote;

interface Configuration
{
    const
        SEPARATOR = '/';
    
    /**
     *
     *
     * @param string $fqn
     * @param mixed $defaultValue
     *
     * @return mixed
     */
    public function read($fqn, $defaultValue = null);
    
    /**
     *
     *
     * @param string $fqn
     *
     * @return mixed
     *
     * @throws \Firenote\Configuration\NotFoundException
     */
    public function readRequired($fqn);
    
    /**
     *
     *
     * @param string $fqn
     *
     * @return boolean
     */
    public function exists($fqn);
    
    /**
     *
     *
     * @param string $fqn
     *
     * @return boolean
     */
    public function groupExists($fqn);
}
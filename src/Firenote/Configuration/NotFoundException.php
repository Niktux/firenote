<?php

namespace Firenote\Configuration;

class NotFoundException extends \Firenote\Exception
{
    public function __construct($fqn)
    {
        parent::__construct(sprintf(
            'Configuration variable %s is missing',
            $fqn
        ));
    }
}
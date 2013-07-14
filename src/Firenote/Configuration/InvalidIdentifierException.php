<?php

namespace Firenote\Configuration;

class InvalidIdentifierException extends \Firenote\Exception
{
    public function __construct($id)
    {
        parent::__construct(sprintf(
            '%s is not a valid configuration identifier',
            $id
        ));
    }
}
<?php

namespace Firenote\Images;

use Firenote\Configuration;
use Imagine\Image\ImagineInterface;

class ImageHandler
{
    private
        $configuration,
        $imagine;
    
    public function __construct(Configuration $configuration, ImagineInterface $imagine)
    {
        $this->configuration = $configuration;
        $this->imagine = $imagine;
    }
}
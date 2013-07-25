<?php

namespace Firenote\Twig;

use Firenote\Images\ImageHandler;

class Extension extends \Twig_Extension
{
    private
        $handler;
    
    public function __construct(ImageHandler $handler)
    {
        $this->handler = $handler;
    }
    
    public function getName()
    {
        return 'firenote';
    }
    
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('image', function($path, $format) {
                $path = $this->handler->applyFormat($path, $format);
                
                // FIXME is it the right place to do this ?
                return substr($path, strpos($path, '/var/'));
            }),
        );
    }
}
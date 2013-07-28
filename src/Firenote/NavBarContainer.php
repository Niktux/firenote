<?php

namespace Firenote;

interface NavBarContainer
{
    /**
     * @return the twig template for displaying the container
     */
    public function getView();
}
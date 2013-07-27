<?php

namespace Firenote\NavBarContainers;

class User implements \Firenote\NavBarContainer
{
    public function getView()
    {
        return 'containers/user.twig';
    }
}
<?php

namespace Firenote\Layout;

interface Menu
{
    public function getLabel();
    public function getIcon();
    public function getLink();
    public function hasSubmenus();
}
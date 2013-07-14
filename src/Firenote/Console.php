<?php

namespace Firenote;

use Symfony\Component\Console\Application;

class Console
{
    public static function run()
    {
        $app = new Application();
        $app->add(new Command\ApplicationInit());
        $app->run();
    }
}

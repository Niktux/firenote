<?php

namespace Firenote;

use Symfony\Component\Console\Application;

class Console
{
    public static function run($rootPath)
    {
        $app = new Application();
        $app->add(new Command\ApplicationInit($rootPath));
        $app->add(new Command\DatabaseCreate($rootPath));
        $app->run();
    }
}

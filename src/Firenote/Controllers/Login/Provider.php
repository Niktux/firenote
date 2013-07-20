<?php

namespace Firenote\Controllers\Login;

use Silex\Application;
use Silex\ControllerProviderInterface;

class Provider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app['login.controller'] = $app->share(function() use($app) {
            return new Controller($app['controllerApi']);
        });

        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/login', 'login.controller:loginAction');
        
        return $controllers;
    }
}

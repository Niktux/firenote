<?php

namespace Firenote\Controllers\Admin;

use Silex\Application;
use Silex\ControllerProviderInterface;

class Provider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app['admin.controller'] = $app->share(function() use($app) {
            return new Controller($app['db'], $app['twig'], $app['request'], $app['layout']);
        });

        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'admin.controller:indexAction');
        
        return $controllers;
    }
}

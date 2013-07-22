<?php

namespace Firenote\Controllers\Admin;

use Silex\Application;
use Silex\ControllerProviderInterface;

class Provider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app['admin.controller'] = $app->share(function() use($app) {
            return $app->initializeController(new Controller());
        });

        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/', 'admin.controller:indexAction');
        
        return $controllers;
    }
}

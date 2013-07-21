<?php

namespace Firenote\Controllers\Users;

use Silex\Application;
use Silex\ControllerProviderInterface;

class Provider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app['users.controller'] = $app->share(function() use($app) {
            return new Controller($app['controllerApi'], $app['user.provider']);
        });

        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/create', 'users.controller:createAction');
        $controllers->get('/list', 'users.controller:usersAction');
        $controllers->get('/{username}/profile', 'users.controller:profileAction')
                    ->assert('username', '\w+');
        
        return $controllers;
    }
}

<?php

namespace Firenote\Controllers\Users;

use Silex\Application;
use Silex\ControllerProviderInterface;

class Provider implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $app['users.controller'] = $app->share(function() use($app) {
            return $app->initializeController(new Controller($app['user.provider'], $app['file_upload']));
        });

        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/create', 'users.controller:createAction')->bind('users_create');
        $controllers->post('/register', 'users.controller:registerAction')->bind('users_register');
        $controllers->get('/list', 'users.controller:usersAction')->bind('users_list');
        $controllers->get('/{username}/profile', 'users.controller:profileAction')->bind('users_profile')
                    ->assert('username', '\w+');
        
        return $controllers;
    }
}

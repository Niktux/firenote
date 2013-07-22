<?php

return <<<CONTENT
<?php

namespace $this->namespace\Controllers\Home;

use Silex\Application;
use Silex\ControllerProviderInterface;

class Provider implements ControllerProviderInterface
{
    public function connect(Application \$app)
    {
        \$app['home.controller'] = \$app->share(function() use(\$app) {
            return \$app->initializeController(new Controller());
        });

        // creates a new controller based on the default route
        \$controllers = \$app['controllers_factory'];

        \$controllers->get('/', 'home.controller:indexAction');
        
        return \$controllers;
    }
}
CONTENT
;
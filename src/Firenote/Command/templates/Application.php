<?php

return <<<CONTENT
<?php
                
namespace $this->namespace;

use Firenote\\Layout\\Menu as Menu;
use Firenote\\NavBarContainers as Containers;
use Firenote\\NavBarContainers\\Messages\\Message;
use Firenote\\NavBarContainers\\Tasks\\Task;
                
class Application extends \\Firenote\\Application
{
    protected function initializeApplicationServices()
    {
    }
    
    protected function configureAdminLayout(\\Firenote\\AdminLayout \$layout)
    {
        \$layout
            ->setAppName('MyApp')
            ->setAppIcon('ok')
            ->setAppColor('blue')
        ;
    
        \$layout
            ->addMenu(new Menu\Link('Dashboard', 'admin_dashboard',    'dashboard'))
            ->addMenu((new Menu\Root('Users', 'user'))
                ->add(new Menu\Link('Create new user', 'users_create'))
                ->add(new Menu\Link('List users', 'users_list'))
            )
            ->addShortcut('users_list', 'user')
            ->addShortcut('users_list', 'comment')
            ->addShortcut('users_list', 'wrench')
            ->addShortcut('admin_logout', 'off')
            
            ->addContainer(new Containers\User())
        ;
    }
                    
    public function mountProviders()
    {
        parent::mountProviders();
        
        \$this->mount('/', new Controllers\Home\Provider());
    }
}
CONTENT
;
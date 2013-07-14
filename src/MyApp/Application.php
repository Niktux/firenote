<?php
                
namespace MyApp;
                
use Firenote\Layout\Menu as Menu;

class Application extends \Firenote\Application
{
    protected function initializeApplicationServices()
    {
    }
    
    protected function configureAdminLayout(\Firenote\AdminLayout $layout)
    {
        /*
        $layout
            ->addMenu(new Menu\Link('Dashboard', '/admin/dashboard',    'dashboard'))
            ->addMenu((new Menu\Root('Users', 'user'))
                ->add(new Menu\Link('Create new user', '/admin/users/create'))
                ->add(new Menu\Link('List users', '/admin/users/list'))
            )
            ->addShortcut('/admin/users/list', 'user')
            ->addShortcut('/admin/users/list', 'comment')
            ->addShortcut('/admin/users/list', 'wrench')
            ->addShortcut('/admin/users/list', 'th-list')
        ;
        //*/
    }
}
                
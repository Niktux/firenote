<?php

return <<<CONTENT
<?php
                
namespace $this->namespace;

use Firenote\\Layout\\Menu as Menu;
                
class Application extends \\Firenote\\Application
{
    protected function initializeApplicationServices()
    {
    }
    
    protected function configureAdminLayout(\\Firenote\\AdminLayout \$layout)
    {
        /*
        \$layout
            ->addMenu(new Menu\Link('Dashboard', 'admin_dashboard',    'dashboard'))
            ->addMenu((new Menu\Root('Users', 'user'))
                ->add(new Menu\Link('Create new user', 'users_create'))
                ->add(new Menu\Link('List users', 'users_list'))
            )
            ->addShortcut('users_list', 'user')
            ->addShortcut('users_list', 'comment')
            ->addShortcut('users_list', 'wrench')
            ->addShortcut('users_list', 'th-list')
            
            ->addContainer(new Containers\Messages(\$this->getMessages()))
            ->addContainer(new Containers\Tasks(\$this->getTasks()))
        ;
        //*/
    }
    
    private function getMessages()
    {
        // FIXME temp
        return array(
            new Message(
                'Tomtom', 'Ceci est un message privé', new \DateTime(), '/assets/firenote/avatars/avatar4.png'
            ),
            new Message(
                'Plus', 'Sale creeper !', new \DateTime('2013-06-08'), '/assets/firenote/avatars/avatar5.png'
        ));
    }
    
    private function getTasks()
    {
        return array(
            new Task('Finish user form', 'danger', 'users_create', '70', true),
            new Task('CRUD user', 'info', 'users_list', '40', false),
            new Task('Active dans menu', 'success', 'admin_home', '10', false),
            new Task('Translation', 'success', null, '20', true),
        );
    }
                    
    public function mountProviders()
    {
        parent::mountProviders();
        
        \$this->mount('/', new Controllers\Home\Provider());
    }
}
CONTENT
;
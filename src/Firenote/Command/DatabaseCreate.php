<?php

namespace Firenote\Command;

use Firenote\Command;
use Doctrine\DBAL\Schema\Table;

class DatabaseCreate extends Command
{
    protected function configure()
    {
        $this
            ->setName('db:create')
            ->setDescription('Create database')
        ;
    }
    
    protected function doExecute()
    {
        $this->step('Creating schema');
        
        $configuration = new \Firenote\Configuration\Yaml($this->rootPath . 'config');
        $app = new \Firenote\Application($configuration);
        $schema = $app['db']->getSchemaManager();
        
        if(! $schema instanceof \Doctrine\DBAL\Schema\AbstractSchemaManager)
        {
            throw new \Exception();
        }
        
        if (!$schema->tablesExist('users'))
        {
            $this->writeln('<info>Creating table users ...</info>');
            $users = new Table('users');
            $users->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => true));
            $users->setPrimaryKey(array('id'));
            $users->addColumn('username', 'string', array('length' => 32));
            $users->addUniqueIndex(array('username'));
            $users->addColumn('password', 'string', array('length' => 255));
            $users->addColumn('roles', 'string', array('length' => 255));
            $users->addColumn('avatar', 'string', array('length' => 512));
        
            $schema->createTable($users);
        
            $this->writeln('<info>Adding users ...</info>');
            $app['db']->insert('users', array(
                    'username' => 'fabien',
                    'password' => '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==',
                    'roles' => 'ROLE_USER',
                    'avatar' => '/assets/firenote/avatars/avatar2.png',
            ));
        
            $app['db']->insert('users', array(
                    'username' => 'admin',
                    'password' => '5FZ2Z8QIkA7UTZ4BYkoC+GsReLf569mSKDsfods6LYQ8t+a8EW9oaircfMpmaLbPBh4FOBiiFyLfuZmTSUwzZg==',
                    'roles' => 'ROLE_ADMIN',
                    'avatar' => '/assets/firenote/avatars/avatar2.png',
            ));

        }
        else
        {
            $this->writeln('Nothing to do !');
        }
    }
}
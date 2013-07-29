<?php

namespace Firenote\Command;

use Firenote\Command;
use Doctrine\DBAL\Schema\Table;
use Firenote\Configuration;

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
        
        $this->createDatabase($configuration);
        
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
    
    private function createDatabase(Configuration $configuration)
    {
        $db = \Doctrine\DBAL\DriverManager::getConnection(array(
            'driver'   => $configuration->read('db/server/driver', 'pdo_mysql'),
            'host'     => $configuration->read('db/server/host', 'localhost'),
            'port'     => $configuration->read('db/server/port', 3306),
            'user'     => $configuration->read('db/server/user'),
            'password' => $configuration->read('db/server/password'),
            'charset'  => 'utf8'
        ));
        
        $sm = $db->getSchemaManager();
        $databases = $sm->listDatabases();
        
        $database = $configuration->read('db/server/database');
        
        if(! in_array($database, $databases))
        {
            $this->writeln("Create database $database ...");
            $db->exec('CREATE DATABASE IF NOT EXISTS ' . $database);
            
            $databases = $sm->listDatabases();
            if(! in_array($database, $databases))
            {
                throw new \RuntimeException('Cannot create database ' . $database);
            }
        }
    }
}
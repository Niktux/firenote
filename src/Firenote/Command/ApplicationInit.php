<?php

namespace Firenote\Command;

use Firenote\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

class ApplicationInit extends Command
{
    private
        $workingDirectory,
        $appName,
        $namespace,
        $database;
    
    protected function configure()
    {
        $this
            ->setName('app:init')
            ->setDescription('Initialize application filetree')
            ->addArgument(
                'workingDir',
                InputArgument::OPTIONAL,
                'Application working directory',
                realpath(__DIR__ . '/../../..')
            )
        ;
    }
    
    protected function doExecute()
    {
        if(! $this->setEnvironment())
        {
            return;
        }
        
        $this->createDirectories();
        $this->createFiles();
        $this->installAssets();
    }
    
    private function setEnvironment()
    {
        $this->step('Set environment');
        
        $this->appName = $this->ask('Application name : ', 'MyApp');
        $this->setAppNameVariations();
        
        $this->setRootDirectory($this->input->getArgument('workingDir'));
        $this->writeln("<comment>Working into $this->workingDirectory</comment>");
        
        $reply = $this->confirm("Do you want to continue (files will be created into working directory) ?");
        
        return $reply;
    }
    
    private function setAppNameVariations()
    {
        $this->namespace = preg_replace('~\s+~', '', ucwords($this->appName));
        $this->writeln("<comment>Namespace will be $this->namespace</comment>");
        
        $this->database = preg_replace('~\s+~', '_', strtolower($this->appName));
        $this->writeln("<comment>Databse will be $this->database</comment>");
    }
    
    private function setRootDirectory($argument)
    {
        $workingDirectoryArgument = realpath($argument);
        if(empty($workingDirectoryArgument) || ! is_dir($workingDirectoryArgument))
        {
            throw new \InvalidArgumentException(sprintf(
                '%s is not a valid working directory',
                $argument
            ));
        }
        
        $this->workingDirectory = rtrim($workingDirectoryArgument, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }
    
    private function createDirectories()
    {
        $directories = array(
            'cache',
            'config',
            'logs',
            'src',
            'src' . DIRECTORY_SEPARATOR . $this->namespace,
            'views',
            'web',
            'web' . DIRECTORY_SEPARATOR . 'assets',
        );
        
        $this->step('Creating directories');
        foreach($directories as $directory)
        {
            $dirPath = $this->workingDirectory . $directory;
            if(! is_dir($dirPath))
            {
                $this->writeln("<info>Creating $dirPath</info>");
                mkdir($dirPath, 0755, true);
            }
            else
            {
                $this->writeln("Skipping $dirPath");
            }
        }
    }
    
    private function createFiles()
    {
        $files = array(
            '.gitignore' => <<<CONTENT
# Dependencies
vendor

# Secret files
# config/db.yml

# Runtime files
cache/*
logs/*

# Eclipse files
.buildpath
.project
.settings
CONTENT
,
                
         'config/db.yml' => <<<CONTENT
server:
  driver: pdo_mysql
  host: localhost
  port: 3306
  database: $this->database
  user:
  password:

CONTENT
,
                
        'web/index.php' => <<<CONTENT
<?php

require __DIR__ . '/../vendor/autoload.php';

\$app = new $this->namespace\\Application(new Firenote\\Configuration\\Yaml());

\$app->enableDebug()->enableProfiling();

\$app->mountProviders();
\$app->initializeAdminLayout();
                
\$app->run();

CONTENT
,

        'src/' . $this->namespace . '/Application.php' => <<<CONTENT
<?php
                
namespace $this->namespace;
                
class Application extends \\Firenote\\Application
{
    protected function initializeApplicationServices()
    {
    }
    
    protected function configureAdminLayout(\\Firenote\\AdminLayout \$layout)
    {
        /*
        \$layout
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
                
CONTENT
                ,
        );
                
        $this->step('Creating files');
        foreach($files as $file => $content)
        {
            $filePath = $this->workingDirectory . $file;
            if(! is_file($filePath))
            {
                $this->writeln("<info>Creating $filePath</info>");
                file_put_contents($filePath, $content);
            }
            else
            {
                $this->writeln("Skipping $filePath");
            }
        }
    }
    
    private function installAssets()
    {
        $this->step('Installing assets');
        // FIXME check
        $target = realpath(__DIR__ . '/../../../web/assets/firenote');
        $link = $this->workingDirectory . 'web/assets/firenote';
        if(! is_link($link) && ! file_exists($link))
        {
            $this->writeln("<info>Linking assets into $link</info>");
            symlink($target, $link);
        }
        else
        {
            $this->writeln("Skipping assets linking ($link already exists)");
        }
    }
    
}
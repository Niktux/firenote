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
        $database,
        $databaseUser,
        $databasePassword;
    
    protected function configure()
    {
        $this
            ->setName('app:init')
            ->setDescription('Initialize application filetree')
            ->addArgument(
                'workingDir',
                InputArgument::OPTIONAL,
                'Application working directory',
                $this->rootPath
            )
        ;
    }
    
    protected function doExecute()
    {
        if(! $this->setEnvironment())
        {
            return;
        }
        
        $this->collectDatabaseAccess();
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
        $this->writeln(sprintf(
            "<comment>Working into %s </comment>",
            rtrim($this->workingDirectory, DIRECTORY_SEPARATOR)
        ));
        
        $reply = $this->confirm("Do you want to continue (files will be created into working directory) ?");
        
        return $reply;
    }
    
    private function collectDatabaseAccess()
    {
        $this->step('Collect database access');

        $defaultDatabase = preg_replace('~\s+~', '_', strtolower($this->appName));
    
        $this->database = $this->ask('Database : ', $defaultDatabase);
        $this->databaseUser = $this->ask('User : ', 'root');
        $this->databasePassword = $this->askPassword('Password : ', '');
    }
    
    private function setAppNameVariations()
    {
        $this->namespace = preg_replace('~\s+~', '', ucwords($this->appName));
        $this->writeln("<comment>Namespace will be $this->namespace</comment>");
        
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
            'config',
            'src',
            'src' . DIRECTORY_SEPARATOR . $this->namespace,
            implode(DIRECTORY_SEPARATOR, array('src', $this->namespace, 'Controllers', 'Home')),
            'views',
            'var',
            'var' . DIRECTORY_SEPARATOR . 'cache',
            'var' . DIRECTORY_SEPARATOR . 'logs',
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
            '.gitignore' => 'gitignore',
            'config/db.yml' => 'dbConfig',
            'config/images.yml' => 'imagesConfig',
            'web/index.php' => 'index',
            'src/' . $this->namespace . '/Application.php' => 'Application',
            'src/' . $this->namespace . '/Controllers/Home/Provider.php' => 'HomeProvider',
            'src/' . $this->namespace . '/Controllers/Home/Controller.php' => 'HomeController',
            'views/home.twig' => 'home.twig',
        );
                
        $this->step('Creating files');
        foreach($files as $file => $templateFile)
        {
            $filePath = $this->workingDirectory . $file;
            if(! is_file($filePath))
            {
                $content = null;

                if($templateFile !== null)
                {
                    $templateFile = __DIR__ . '/templates/' . $templateFile . '.php';
                    $content = include($templateFile);
                }
                
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
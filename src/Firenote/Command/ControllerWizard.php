<?php

namespace Firenote\Command;

use Firenote\Command;

class ControllerWizard extends Command
{
    const
        SELF_DIR = 'Firenote',
        SRC_DIR = 'src/';
    
    private
        $twig;
    
    public function __construct($rootPath)
    {
        parent::__construct($rootPath);
        
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/wizard/controller/');
        $this->twig = new \Twig_Environment($loader, array(
            'debug' => false,
            'cache' => false,
        ));
    }
    
    protected function configure()
    {
        $this
            ->setName('wizard:controller')
            ->setDescription('Generates controller skeleton')
        ;
    }
    
    protected function doExecute()
    {
        $rootNamespace = $this->getRootNamespace();
        $namespace = $this->getNamespace();
            
        $this->generate($rootNamespace, $namespace);
        
        $this->postMortem($rootNamespace, $namespace);
    }
    
    private function getRootNamespace()
    {
        $found = $this->findExistingApplicationNamespace();
        
        if(count($found) === 1)
        {
            return array_shift($found);
        }
        
        sort($found);
        
        $choice = $this->askAmongSelection(
            'Please select your application namespace',
            $found,
            0,
            true
        );
        
        return $found[$choice];
        
    }
    
    private function findExistingApplicationNamespace()
    {
        $found = array();
        
        $it = new \DirectoryIterator($this->rootPath . self::SRC_DIR);
        foreach($it as $item)
        {
            if($item->isDir() && ! $item->isDot())
            {
                if($item->getBasename() !== self::SELF_DIR)
                {
                    $found[] = $item->getBasename();
                }
            }
        }
        
        if(empty($found))
        {
            throw new \Exception('Cannot find application namespace');
        }

        return $found;
    }
    
    private function getNamespace()
    {
        // TODO validate format
        return $this->ask('Please enter the controller name');
    }
        
    private function generate($rootNamespace, $namespace)
    {
        $relativePath = self::SRC_DIR . $rootNamespace . '/Controllers/' . $namespace . '/';
        $rootDir = $this->rootPath . $relativePath;
        $this->ensureDirectoryExists($rootDir);
        
        $this->writeln("<info>Working in $relativePath</info>");
        
        $files = array(
            'Controller.php' => 'Controller.twig',
            'Provider.php' => 'Provider.twig',
        );
        
        foreach($files as $phpFile => $template)
        {
            $content = $this->twig->render($template, array(
                'rootNamespace' => $rootNamespace,
                'namespace' => $namespace,
            ));
            
            $this->writeln('Generating ' . $phpFile);
            file_put_contents($rootDir . $phpFile, $content);
        }
    }
    
    private function ensureDirectoryExists($directory)
    {
        if(!is_dir($directory))
        {
            if(!mkdir($directory, 0755, true))
            {
                throw new Exceptions\Filesystem("Cannot create directory $directory");
            }
        }
    }

    private function postMortem($rootNamespace, $namespace)
    {
        $message = "Do not forget to mount the new provider :";
        $this->writeln("<comment>$message</comment>");
        
        $format = 'fg=white;options=bold';
        $urlNamespace = urlencode($namespace);
        $codeExample = <<<PHP
    \$this->mount('/admin/$urlNamespace', new Controllers\\$namespace\\Provider());
PHP;
        $this->writeln("\n<$format>$codeExample</$format>\n");
    }
}
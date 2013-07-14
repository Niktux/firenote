<?php

namespace Firenote\Configuration;

class Yaml extends AbstractConfiguration
{
    private
        $cache,
        $directory;
        
    public function __construct($configurationFilesDirectory = null)
    {
        parent::__construct();
        
        $this->cache = array();
        
        if($configurationFilesDirectory === null)
        {
            $configurationFilesDirectory = __DIR__ . '/../../../config/';
        }
        
        $this->directory = rtrim($configurationFilesDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }
    
    public function exists($fqn)
    {
        list($filename, $group, $variable) = $this->parseVariableDsn($fqn);

        $config = $this->getYaml($filename);
        
        return isset($config[$group][$variable]);
    }

    public function groupExists($fqn)
    {
        list($filename, $group) = $this->parseGroupDsn($fqn);

        $config = $this->getYaml($filename);
        
        return isset($config[$group]);
    }
    
    protected function getValue($fqn)
    {
        list($filename, $group, $variable) = $this->parseVariableDsn($fqn);

        $config = $this->getYaml($filename);
        
        return $config[$group][$variable];
    }

    protected function getGroup($fqn)
    {
        list($filename, $group) = $this->parseGroupDsn($fqn);

        $config = $this->getYaml($filename);
        
        return $config[$group];
    }

    private function getYaml($alias)
    {
        if(! isset($this->cache[$alias]))
        {
            $filename = $this->computeFilename($alias);
            $this->cache[$alias] = \Symfony\Component\Yaml\Yaml::parse($filename);
        }
        
        return $this->cache[$alias];
    }

    private function computeFilename($alias)
    {
        return $this->directory . $alias . '.yml';
    }
}

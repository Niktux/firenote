<?php

namespace Firenote\Configuration;

abstract class AbstractConfiguration implements \Firenote\Configuration
{
    abstract public function exists($fqn);
    abstract public function groupExists($fqn);
    
    abstract protected function getValue($fqn);
    abstract protected function getGroup($fqn);

    public function __construct()
    {
        // Empty constructor to avoid inheritance issues
    }
    
    public function read($fqn, $defaultValue = null)
    {
        $value = $defaultValue;
        
        if($this->exists($fqn))
        {
            $value = $this->getValue($fqn);
        }
    
        return $value;
    }
    
    public function readRequired($fqn)
    {
        if(!$this->exists($fqn))
        {
            throw new NotFoundException($fqn);
        }
    
        $value = $this->getValue($fqn);
    
        return $value;
    }
    
    public function readGroup($fqn)
    {
        if($this->groupExists($fqn))
        {
            return $this->getGroup($fqn);
        }
    
        throw new NotFoundException($fqn);
    }
    
    /**
     * Parse the idenfication name of variable or group
     *
     * @example myConfigFilenameWithoutExtension/myRootConfig/myGroup/myVariable
     * @see Configuration::parseDsn
     */
    protected function parseVariableDsn($fqn)
    {
        return $this->parseDsn($fqn, 3);
    }
    
    /**
     * Parse the idenfication name of group
     *
     * @example myConfigFilenameWithoutExtension/myRootConfig/myGroup
     * @see Configuration::parseDsn
     */
    protected function parseGroupDsn($fqn)
    {
        return $this->parseDsn($fqn, 2);
    }
    
    /**
     * Parse the idenfication name of variable or group
     *
     * @example myConfigFilenameWithoutExtension/myRootConfig/myGroup/myVariable
     *
     * @param string $fqn
     * @param int $maxToken
     *
     * @throws \Firenote\Configuration\InvalidIdentifierException
     *
     * @return multitype:
     */
    protected function parseDsn($fqn, $maxToken)
    {
        $tokens = explode(self::SEPARATOR, $fqn, $maxToken);
        
        if( count($tokens) < $maxToken )
        {
            throw new InvalidIdentifierException($fqn);
        }
        
        return $tokens;
    }
    
    /**
     *
     *
     * @param
     *
     * @return string
     */
    public static function join(/* ... */)
    {
        $args = func_get_args();
    
        return implode(self::SEPARATOR, $args);
    }
}
<?php

namespace Firenote;

use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;

abstract class Command extends \Symfony\Component\Console\Command\Command
{
    const
        LINE_LENGTH = 70;
    
    private static $logo = <<<ASCII
---------------------------------------------------------------------
                   ___ _                      _
                 / __(_)_ __ ___ _ __   ___ | |_ ___
                / _\ | | '__/ _ \ '_ \ / _ \| __/ _ \
               / /   | | | |  __/ | | | (_) | ||  __/
               \/    |_|_|  \___|_| |_|\___/ \__\___|

---------------------------------------------------------------------

ASCII;
    
    protected
        $rootPath,
        $input,
        $output,
        $dialog;
    
    public function __construct($rootPath)
    {
        $this->rootPath = $rootPath;
        
        parent::__construct('Firenote command');
    }
    
    protected function writeln($messages, $type = OutputInterface::OUTPUT_NORMAL)
    {
        $this->output->writeln($messages, $type);
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->dialog = $this->getHelperSet()->get('dialog');
        
        $style = new OutputFormatterStyle('white', 'blue', array('bold'));
        $output->getFormatter()->setStyle('step', $style);
        
        $style = new OutputFormatterStyle('red', 'black', array('bold'));
        $output->getFormatter()->setStyle('logo', $style);
        
        $this->writeln(sprintf(
            "<logo>%s%s</logo>\n",
            self::$logo,
            $this->center($this->getDescription())
        ));
        
        $this->doExecute();
        $this->writeln('Execution finished');
    }
    
    protected function step($step)
    {
        static $i = 1;
    
        $this->writeln(sprintf(
            "\n<step>%s</step>\n",
            $this->fill("$i - $step")
        ));
    
        $i++;
    }
    
    protected function center($string)
    {
        return str_pad(trim($string), self::LINE_LENGTH, ' ', STR_PAD_BOTH);
    }
    
    protected function fill($string)
    {
        return str_pad($string, self::LINE_LENGTH);
    }
    
    protected function ask($question, $defaultValue = null, array $autocomplete = null)
    {
        $reply = $this->dialog->ask(
            $this->output,
            "\n<question>$question</question> ",
            $defaultValue,
            $autocomplete
        );
        
        $this->writeln('');
        
        return $reply;
    }

    protected function askPassword($question, $defaultValue)
    {
        $reply = $this->dialog->askHiddenResponse(
            $this->output,
            "\n<question>$question</question> ",
            $defaultValue
        );
        
        $this->writeln('');
    
        return $reply;
    }
    
    protected function askAmongSelection($question, array $values, $defaultValue = 0, $displayDefaultValue = false)
    {
        $defaultMessage = '';
        if($displayDefaultValue === true)
        {
            $defaultMessage = " (default : $values[0])";
        }
        
        $reply = $this->dialog->select(
            $this->output,
            "\n<question>$question$defaultMessage</question> ",
            $values,
            $defaultValue
        );
        
        $this->writeln('');
        
        return $reply;
    }
    
    protected function confirm($question, $defaultValue = false)
    {
        $reply = $this->dialog->askConfirmation($this->output, "\n<question>$question</question> ", $defaultValue);
        $this->writeln('');
        
        return $reply;
    }
     
    abstract protected function doExecute();
}
<?php

namespace Firenote\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Command\Command;

class ApplicationInit extends Command
{
    protected function configure()
    {
        $this
            ->setName('app:init')
            ->setDescription('Initialize application filetree');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('ok');
    }
}
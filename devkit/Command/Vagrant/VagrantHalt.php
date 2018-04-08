<?php

namespace Noiselabs\Devkit\Command\Vagrant;

use Noiselabs\Devkit\Command\DevkitCliCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VagrantHalt extends DevkitCliCommand
{
    protected function configure()
    {
        $this->setName('vagrant:halt');
        $this->setDescription('Stops the Vagrant machine');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}

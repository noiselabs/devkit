<?php

namespace Noiselabs\Devkit\Command\Vagrant;

use Noiselabs\Devkit\Command\DevkitCliCommand;

class VagrantUp extends DevkitCliCommand
{
    protected function configure()
    {
        $this->setName('vagrant:up');
        $this->setDescription('Starts and provisions the Vagrant environment');
    }
}

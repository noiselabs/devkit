<?php

namespace Noiselabs\Devkit\Command\Docker;

use Noiselabs\Devkit\Command\DevkitCliCommand;
use Noiselabs\Devkit\Infra\Command\DockerCompose;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DockerStart extends DevkitCliCommand
{
    protected function configure()
    {
        $this->setName('docker:start');
        $this->setDescription('Starts a service');
        $this->addArgument('service', InputArgument::REQUIRED);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->executeRemoteCommand(DockerCompose::start($input->getArgument('service')), $input, $output);
    }
}

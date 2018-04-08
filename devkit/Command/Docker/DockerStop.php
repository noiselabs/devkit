<?php

namespace Noiselabs\Devkit\Command\Docker;

use Noiselabs\Devkit\Command\DevkitCliCommand;
use Noiselabs\Devkit\Infra\Command\DockerCompose;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DockerStop extends DevkitCliCommand
{
    protected function configure()
    {
        $this->setName('docker:stop');
        $this->setDescription('Stops a service');
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
        return $this->executeRemoteCommand(DockerCompose::stop($input->getArgument('service')), $input, $output);
    }
}

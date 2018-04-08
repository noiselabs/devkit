<?php

namespace Noiselabs\Devkit\Command\Docker;

use Noiselabs\Devkit\Command\DevkitCliCommand;
use Noiselabs\Devkit\Infra\Command\DockerCompose;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DockerShell extends DevkitCliCommand
{
    protected function configure()
    {
        $this->setName('docker:shell');
        $this->setDescription('Get a Bash shell in a running Docker container');
        $this->addArgument('container', InputArgument::REQUIRED, 'Container/service name');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $service = $input->getArgument('container');

        return $this->executeRemoteCommand(DockerCompose::exec($service, 'bash'), $input, $output);
    }
}

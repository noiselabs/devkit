<?php

namespace Noiselabs\Devkit\Command\Docker;

use Noiselabs\Devkit\Command\DevkitCliCommand;
use Noiselabs\Devkit\Infra\Command\DockerCompose;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DockerLogs extends DevkitCliCommand
{
    protected function configure()
    {
        $this->setName('docker:logs');
        $this->setDescription('Follow Docker logs');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->executeRemoteCommand(
            DockerCompose::logs(null, null, ['--follow', '--timestamps']),
            $input,
            $output
        );
    }
}

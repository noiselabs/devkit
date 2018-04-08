<?php

namespace Noiselabs\DevkitApp\Command;

use Noiselabs\Devkit\Command\DevkitCliCommand;
use Noiselabs\Devkit\Environment;
use Noiselabs\Devkit\Infra\Command\DockerCompose;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnvDown extends DevkitCliCommand
{
    protected function configure()
    {
        $this->setName('env:down');
        $this->setDescription('Shutdowns the development environment (stops and removes containers)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = $this->getConsoleIo($input, $output);

        $io->writeln(Environment::OUTPUT_TITLE_PREFIX . 'Shutting down the development environment...');
        $this->executeRemoteCommand(DockerCompose::down(), $input, $output);
        $io->writeln(Environment::OUTPUT_TITLE_PREFIX . Environment::OUTPUT_DONE);
    }
}

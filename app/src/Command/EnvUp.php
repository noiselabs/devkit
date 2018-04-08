<?php

namespace Noiselabs\DevkitApp\Command;

use Noiselabs\Devkit\Command\DevkitCliCommand;
use Noiselabs\Devkit\Environment;
use Noiselabs\Devkit\Infra\Command\DockerCompose;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnvUp extends DevkitCliCommand
{
    protected function configure()
    {
        $this->setName('env:up');
        $this->setDescription('Boots the development environment (starts Docker containers and installs dependencies)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = $this->getConsoleIo($input, $output);

        $io->writeln(Environment::OUTPUT_TITLE_PREFIX . 'Booting the development environment...');

        $this->executeRemoteCommand(DockerCompose::up(null, ['-d']), $input, $output);

        $io->writeln(Environment::OUTPUT_TITLE_PREFIX . Environment::OUTPUT_DONE);
    }
}

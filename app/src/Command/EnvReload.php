<?php

namespace Noiselabs\DevkitApp\Command;

use Noiselabs\Devkit\Command\DevkitCliCommand;
use Noiselabs\Devkit\Environment;
use Noiselabs\Devkit\Infra\Command\DockerCompose;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EnvReload extends DevkitCliCommand
{
    protected function configure()
    {
        $this->setName('env:reload');
        $this->setDescription('Reloads the environment (stop, build and up)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $io = $this->getConsoleIo($input, $output);

        $io->writeln(Environment::OUTPUT_TITLE_PREFIX . 'Reloading the development environment...');
        $this->executeRemoteCommand(DockerCompose::stop(), $input, $output);
        $this->executeRemoteCommand(DockerCompose::build(), $input, $output);
        $this->executeRemoteCommand(DockerCompose::up(), $input, $output);
        $io->writeln(Environment::OUTPUT_TITLE_PREFIX . Environment::OUTPUT_DONE);
    }
}

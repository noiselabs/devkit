<?php

namespace Noiselabs\Devkit\Command;

use Noiselabs\Devkit\DevkitApplication;
use Noiselabs\Devkit\Config\AppSettings;
use Noiselabs\Devkit\Environment;
use Noiselabs\Devkit\Infra\Command\RemoteCommand;
use Noiselabs\Devkit\Infra\Executor\DockerRemoteExecutor;
use Noiselabs\Devkit\Infra\Executor\LocalExecutor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

abstract class DevkitCliCommand extends Command
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    protected function env(): Environment
    {
        /** @var DevkitApplication $app */
        $app = $this->getApplication();

        return $app->getContainer()['env'];
    }

    protected function getSettings(): AppSettings
    {
        /** @var DevkitApplication $app */
        $app = $this->getApplication();

        return $app->getSettings();
    }

    protected function getConsoleIo(InputInterface $input, OutputInterface $output): SymfonyStyle
    {
        return new SymfonyStyle($input, $output);
    }

    protected function executeRemoteCommand(RemoteCommand $command, InputInterface $input, OutputInterface $output): int
    {
        /** @var DevkitApplication $app */
        $app = $this->getApplication();

        /** @var DockerRemoteExecutor $remoteExecutor */
        $remoteExecutor = $app->getContainer()['remote_executor'];

        $useTty = $input->hasOption('no-tty') ? !$input->getOption('no-tty') : !$output->isQuiet();

        return $remoteExecutor->execute($command, $input, $output, $useTty);
    }

    protected function executeLocalCommand(
        string $commandline,
        string $workingDirectory,
        InputInterface $input,
        OutputInterface $output,
        bool $thisIsFine = false
    ): int {
        /** @var DevkitApplication $app */
        $app = $this->getApplication();

        /** @var LocalExecutor $localExecutor */
        $localExecutor = $app->getContainer()['local_executor'];

        return $localExecutor->executeLocalCommand($commandline, $workingDirectory, $input, $output, $thisIsFine);
    }

    /**
     * @return Filesystem
     */
    protected function getFilesystem(): Filesystem
    {
        if (null === $this->filesystem) {
            $this->filesystem = new Filesystem();
        }

        return $this->filesystem;
    }
}

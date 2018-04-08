<?php

namespace Noiselabs\DevkitApp\Command;

use Noiselabs\Devkit\DevkitApplication;
use Noiselabs\Devkit\Command\DevkitCliCommand;
use Noiselabs\Devkit\Environment;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class EnvSetup extends DevkitCliCommand
{
    /**
     * Working directory set during command execution
     * @var string|null
     */
    private $rootDir;

    protected function configure(): void
    {
        $this->setName('env:setup');
        $this->setDescription('Set up the development environment');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $this->rootDir = $this->createWorkingDir($input, $output);
            $this->saveConfig($this->rootDir);
        } catch (RuntimeException $e) {
            $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));

            return -1;
        }

        return 0;
    }

    private function createWorkingDir(InputInterface $input, OutputInterface $output): string
    {
        $this->rootDir = $this->loadRootDirFromConfig() ?: Environment::getDefaultWorkingDir();
        $questionPhrase = sprintf(
            '<question>Path to your development directory (%s):</question> ',
            $this->rootDir
        );
        $question = new Question($questionPhrase, $this->rootDir);
        $this->rootDir = $this->getHelper('question')->ask($input, $output, $question);

        if (is_file($this->rootDir)) {
            throw new RuntimeException(sprintf('<error>%s exists and is not a directory</error>', $this->rootDir));
        }

        if (!is_dir($this->rootDir)) {
            try {
                $command = sprintf('mkdir -p %s', $this->rootDir);
                (new Process($command))->mustRun();
            } catch (ProcessFailedException $e) {
                throw new RuntimeException(sprintf('<error>Cannot create working directory %s</error>', $this->rootDir));
            }
        }

        return rtrim($this->rootDir, '/') . '/';
    }

    private function saveConfig(string $rootDir): void
    {
        /** @var DevkitApplication $app */
        $app = $this->getApplication();
        $settings = $app->getSettings();

        $rootDir = rtrim($rootDir, '/');

        if ($settings->getOption('root_dir') != $rootDir) {
            $settings->merge(['root_dir' => $rootDir]);
            $settings->save();
        }
    }

    private function loadRootDirFromConfig(): string
    {
        /** @var DevkitApplication $app */
        $app = $this->getApplication();
        $settings = $app->getSettings();

        return $settings->getOption('root_dir');
    }
}

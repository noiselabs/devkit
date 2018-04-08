<?php

namespace Noiselabs\Devkit\Infra\Executor;

use Exception;
use Noiselabs\Devkit\Environment;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class LocalExecutor
{
    /**
     * @var Filesystem
     */
    private $fs;

    public function __construct()
    {
        $this->fs = new Filesystem();
    }

    /**
     * @param string $commandline
     * @param string $workingDirectory
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param bool $thisIsFine
     *
     * @return int
     */
    public function executeLocalCommand($commandline, $workingDirectory, InputInterface $input, OutputInterface $output, $thisIsFine = false)
    {
        $process = new Process($commandline);
        $process->setTty(true);
        if ($workingDirectory) {
            $process->setWorkingDirectory($workingDirectory);
        }
        $process->setTimeout(Environment::DEFAULT_PROCESS_TIMEOUT);
        $process->setIdleTimeout(Environment::DEFAULT_PROCESS_TIMEOUT);

        try {
            $process->mustRun(function ($type, $buffer) use ($output) {
                if ($output->isVerbose()) {
                    $output->write(Environment::OUTPUT_VERBOSE_PREFIX . $buffer);
                }
            });
        } catch (Exception $e) {
            if (true !== $thisIsFine) {
                $output->writeln(sprintf(
                    ' %s%s<error>%s</error>',
                    Environment::OUTPUT_FAILED,
                    PHP_EOL,
                    $e->getMessage()
                ));
            }

            return -1;
        }

        return $process->getExitCode();
    }
}

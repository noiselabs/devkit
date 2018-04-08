<?php

namespace Noiselabs\Devkit\Infra\Executor;

use Exception;
use Noiselabs\Devkit\Environment;
use Noiselabs\Devkit\Infra\Command\DockerRemoteCommand;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class DockerRemoteExecutor
{
    /**
     * @var string
     */
    private $dockerComposeDir;

    /**
     * DockerRemoteExecutor constructor.
     *
     * @param string $dockerComposeDir
     */
    public function __construct($dockerComposeDir)
    {
        if (!is_file(sprintf('%s/docker-compose.yml', $dockerComposeDir))) {
            throw new RuntimeException(sprintf('Unable to locate file "docker-compose.yml" at "%s"', $dockerComposeDir));
        }

        $this->dockerComposeDir = $dockerComposeDir;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param bool            $tty
     *
     * @return int
     */
    public function execute(
        DockerRemoteCommand $command,
        InputInterface $input,
        OutputInterface $output,
                            $tty = true
    ) {
        $verbose = $output->isVerbose() || true === $input->getOption('verbose');
        $quiet = $output->isQuiet();

        $process = $this->createProcess((string) $command, $tty);
        try {
            $process->run(function ($type, $buffer) use ($quiet) {
                if (!$quiet) {
                    echo $buffer;
                }
            });
        } catch (Exception $e) {
            $output->writeln(sprintf(
                ' %s%s<error>%s</error>',
                Environment::OUTPUT_FAILED,
                PHP_EOL,
                $e->getMessage()
            ));

            return -1;
        }

        return $process->getExitCode();
    }

    /**
     * @param string $commandline
     * @param bool   $tty Enables or disables the TTY mode.
     *
     * @return Process
     */
    private function createProcess($commandline, $tty = true)
    {
        $process = new Process($commandline);
        $process->setWorkingDirectory($this->dockerComposeDir);
        $process->setTty($tty);
        $process->setTimeout(Environment::DEFAULT_PROCESS_TIMEOUT);
        $process->setIdleTimeout(Environment::DEFAULT_PROCESS_TIMEOUT);

        return $process;
    }
}

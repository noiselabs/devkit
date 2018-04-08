<?php

namespace Noiselabs\Devkit\Infra\Executor;

use Exception;
use Noiselabs\Devkit\Environment;
use Noiselabs\Devkit\Infra\Command\DockerRemoteCommand;
use RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class DockerRemoteExecutor implements RemoteExecutor
{
    /**
     * @var string
     */
    private $dockerComposeDir;

    public function __construct(string $dockerComposeDir)
    {
        if (!is_file(sprintf('%s/docker-compose.yml', $dockerComposeDir))) {
            throw new RuntimeException(sprintf('Unable to locate file "docker-compose.yml" at "%s"', $dockerComposeDir));
        }

        $this->dockerComposeDir = $dockerComposeDir;
    }

    public function execute(DockerRemoteCommand $command, InputInterface $input, OutputInterface $output, $tty = true): int
    {
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

    private function createProcess($commandline, $tty = true): Process
    {
        $process = new Process($commandline);
        $process->setWorkingDirectory($this->dockerComposeDir);
        $process->setTty($tty);
        $process->setTimeout(Environment::DEFAULT_PROCESS_TIMEOUT);
        $process->setIdleTimeout(Environment::DEFAULT_PROCESS_TIMEOUT);

        return $process;
    }
}

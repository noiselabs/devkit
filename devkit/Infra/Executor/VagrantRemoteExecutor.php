<?php

namespace Noiselabs\Devkit\Infra\Executor;

use Exception;
use Noiselabs\Devkit\DevkitApplication;
use Noiselabs\Devkit\Environment;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class VagrantRemoteExecutor implements RemoteExecutor
{
    /**
     * @var string
     */
    private $vagrantfileDir;

    public function __construct(string $vagrantfileDir)
    {
        if (!is_file(sprintf('%s/Vagrantfile', $vagrantfileDir))) {
            throw new RuntimeException(sprintf('Unable to locate file "Vagrantfile" at "%s"', $vagrantfileDir));
        }

        $this->vagrantfileDir = $vagrantfileDir;
    }

    public function execute(string $box, string $commandline, InputInterface $input, OutputInterface $output): int
    {
        $verbose = $output->isVerbose() || true === $input->getOption('verbose');
        $command = escapeshellarg(implode(' && ', (array) $commandline));
        $command = sprintf("vagrant ssh %s --command %s", $box, $command);

        if (true === $verbose) {
            $output->writeln('');
        }

        $process = new Process($command);
        $process->setWorkingDirectory($this->vagrantfileDir);
        $process->setTimeout(Environment::DEFAULT_PROCESS_TIMEOUT);
        $process->setTty(false);
        try {
            $process->run(function ($type, $buffer) use ($verbose) {
                if ($verbose) {
                    echo $buffer;
                }
            });
        } catch (Exception $e) {
            $output->writeln(sprintf(' %s%s<error>%s</error>', Environment::OUTPUT_FAILED, PHP_EOL, $e->getMessage()));

            return -1;
        }

        return $process->getExitCode();
    }
}

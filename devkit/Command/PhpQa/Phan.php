<?php

namespace Noiselabs\Devkit\Command\PhpQa;

use Noiselabs\Devkit\Command\DevkitCliCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Phan extends DevkitCliCommand
{
    protected function configure()
    {
        $this
            ->setName('phpqa:phan')
            ->setDescription('A static analyzer for PHP')
            ->setHelp(
                <<<'HEREDOC'
Phan is a static analyzer for PHP that prefers to minimize false-positives. Phan attempts to prove incorrectness rather than correctness.

Phan looks for common issues and will verify type compatibility on various operations when type information is available or can be deduced. Phan does not have a strong understanding of flow control and does not attempt to track values.
HEREDOC
            )
            ->addArgument('project', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectPath = $this->env()->getProjectDir($this->env()->fromAlias($input->getArgument('project')));

        $this->executeLocalCommand(sprintf(
            'docker run -it --rm -v %s:/project -w /project jakzal/phpqa phan',
            $projectPath
        ), sys_get_temp_dir(), $input, $output, true);
    }
}

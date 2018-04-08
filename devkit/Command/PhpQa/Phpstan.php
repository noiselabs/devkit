<?php

namespace Noiselabs\Devkit\Command\PhpQa;

use Noiselabs\Devkit\Command\DevkitCliCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Phpstan extends DevkitCliCommand
{
    protected function configure()
    {
        $this
            ->setName('phpqa:phpstan')
            ->setDescription('PHPStan - PHP Static Analysis Tool')
            ->setHelp('PHPStan focuses on finding errors in your code without actually running it. It catches whole classes of bugs even before you write tests for the code.')
            ->addArgument('project', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $projectPath = $this->env()->getProjectDir($this->env()->fromAlias($input->getArgument('project')));

        $this->executeLocalCommand(sprintf(
            'docker run -it --rm -v %s:/project -w /project jakzal/phpqa phpstan analyse module',
            $projectPath
        ), sys_get_temp_dir(), $input, $output, true);
    }
}

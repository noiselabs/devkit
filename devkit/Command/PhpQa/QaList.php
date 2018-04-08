<?php

namespace Noiselabs\Devkit\Command\PhpQa;

use Noiselabs\Devkit\Command\DevkitCliCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class QaList extends DevkitCliCommand
{
    protected function configure()
    {
        $this
            ->setName('phpqa:list')
            ->setDescription('List all available PHP QA tools');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->executeLocalCommand('docker run -it --rm jakzal/phpqa', sys_get_temp_dir(), $input, $output);
    }
}

<?php

namespace Noiselabs\Devkit;

use Noiselabs\Devkit\Config\AppSettings;
use Symfony\Component\Filesystem\Filesystem;

class Environment
{
    const DEFAULT_PROCESS_TIMEOUT = 1800;
    const OUTPUT_TITLE_PREFIX = '<info>>></info> ';
    const OUTPUT_VERBOSE_PREFIX = '>> ';
    const OUTPUT_DONE = 'Done シ';
    const OUTPUT_FAILED = '(╯°□°）╯︵ ┻━┻';
    const OUTPUT_GREEN_CHECK_MARK = "<info>\xE2\x9C\x94</info>";

    /**
     * @var AppSettings
     */
    private $appSettings;

    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * Environment constructor.
     *
     * @param AppSettings $appSettings
     */
    public function __construct(AppSettings $appSettings)
    {
        $this->appSettings = $appSettings;
        $this->fs = new Filesystem();
    }

    public static function getDefaultWorkingDir(): string
    {
        return getenv('HOME') . '/projects';
    }

    public function getProjectsDir(): string
    {
        return $this->appSettings->getOption('root_dir');
    }

    public function getDockerDir(): string
    {
        return $this->getProjectsDir();
    }

    public function getVagrantDir(): string
    {
        return $this->getProjectsDir();
    }
}

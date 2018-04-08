<?php

namespace Noiselabs\DevkitApp;

use Noiselabs\Devkit\DevkitApplication;
use Noiselabs\DevkitApp\Command as Command;

class Application extends DevkitApplication
{
    const APP_NAME = 'DevkitApp';
    const APP_VERSION = '0.1.0';

    public function __construct(?string $name = self::APP_NAME, ?string $version = self::APP_VERSION)
    {
        parent::__construct($name, $version);

        $this->setService('remote_executor', $this->getService('docker_remote_executor'));

        $this->addCommands([
            new Command\EnvDown,
            new Command\EnvReload,
            new Command\EnvSetup,
            new Command\EnvUp,
        ]);
    }
}
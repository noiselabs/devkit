<?php

namespace Noiselabs\Devkit;

use Noiselabs\Devkit\Command as Command;
use Noiselabs\Devkit\Config\AppSettings;
use Noiselabs\Devkit\Infra\Executor\DockerRemoteExecutor;
use Noiselabs\Devkit\Infra\Executor\LocalExecutor;
use Pimple\Container;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;
use Symfony\Component\Console\Exception\RuntimeException;

abstract class DevkitApplication extends SymfonyConsoleApplication
{
    const APP_NAME = 'Devkit';
    const APP_VERSION = '0.1.0';
    const BUILD_VERSION = '@package_version@';
    const BUILD_RELEASE_DATE = '@release_date@';

    /**
     * @var Container
     */
    private $container;

    public function __construct(?string $name = null, ?string $version = null)
    {
        $name = $name ?: static::APP_NAME;
        $version = $version ?: static::APP_VERSION;

        $version = substr(static::BUILD_VERSION, 1, 15) !== 'package_version'
            ? static::BUILD_VERSION : $version;
        parent::__construct($name, $version);

        $this->container = new Container();

        $this->buildContainer();
        $this->registerCommands();
    }

    public function getContainer(): Container
    {
        return $this->container;
    }

    public function getSettings(): AppSettings
    {
        return $this->container['app_settings'];
    }

    public function setService(string $name, $service): void
    {
        $this->container[$name] = $service;
    }

    public function getService(string $name)
    {
        if (!isset($this->container[$name])) {
            throw new RuntimeException(sprintf('Service "%s" is not available.', $name));
        }

        return $this->container[$name];
    }

    private function registerCommands(): void
    {
        $this->addCommands([
            new Command\Docker\DockerLogs,
            new Command\Docker\DockerShell,
            new Command\Docker\DockerStart,
            new Command\Docker\DockerStop,
            new Command\PhpQa\Phan,
            new Command\PhpQa\Phpstan,
            new Command\Phpqa\QaList,
            new Command\Vagrant\VagrantHalt,
            new Command\Vagrant\VagrantUp,
        ]);
    }

    private function buildContainer(): void
    {
        $this->setService('app_settings', function () {
            return AppSettings::fromConfig();
        });

        $this->setService('env', function (Container $c) {
            return new Environment($c['app_settings']);
        });

        $this->setService('local_executor', function () {
            return new LocalExecutor();
        });

        $this->setService('docker_remote_executor', function (Container $c) {
            /** @var Environment $env */
            $env = $c['env'];

            return new DockerRemoteExecutor($env->getDockerDir());
        });

        $this->setService('vagrant_remote_executor', function (Container $c) {
            /** @var Environment $env */
            $env = $c['env'];

            return new DockerRemoteExecutor($env->getDockerDir());
        });
    }
}

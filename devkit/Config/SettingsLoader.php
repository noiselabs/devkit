<?php

namespace Noiselabs\Devkit\Config;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

/**
 * Loads application settings from config files.
 */
class SettingsLoader
{
    const CONFIG_FILE = 'devkit.yml';

    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * @var string
     */
    private $configDir;

    /**
     * SettingsLoader constructor.
     *
     * @param string $configDir
     */
    public function __construct($configDir)
    {
        $this->configDir = $configDir;
        $this->fs = new Filesystem();
    }

    /**
     * @return array
     */
    public function load(): array
    {
        if (!$this->fs->exists($this->configDir)) {
            $this->fs->mkdir($this->configDir, 0700);
        }

        $configPath = $this->getConfigPath();
        $configuration = new DevkitCliConfiguration();
        $processor = new Processor();

        if (!$this->fs->exists($configPath)) {
            $config = $processor->processConfiguration($configuration, []);
            $content = [DevkitCliConfiguration::ROOT_KEY => $config];
            $this->fs->dumpFile($configPath, Yaml::dump($content));

            return $config;
        }

        $content = Yaml::parse(file_get_contents($configPath));
        $config = $content[DevkitCliConfiguration::ROOT_KEY];

        return $config;
    }

    public function save(array $settings): void
    {
        $configPath = $this->getConfigPath();
        $content = [DevkitCliConfiguration::ROOT_KEY => $settings];
        $this->fs->dumpFile($configPath, Yaml::dump($content));
    }

    private function getConfigPath(): string
    {
        return sprintf('%s/%s', $this->configDir, self::CONFIG_FILE);
    }
}

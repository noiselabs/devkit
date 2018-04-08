<?php

namespace Noiselabs\Devkit\Config;

use Noiselabs\Devkit\Environment;
use OutOfBoundsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class AppSettings
{
    /**
     * @var array
     */
    private $options;

    /**
     * @var OptionsResolver
     */
    private $resolver;

    /**
     * AppSettings constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->resolver = new OptionsResolver();
        $this->resolver->setDefaults([
            'root_dir' => Environment::getDefaultWorkingDir(),
        ]);

        $this->merge($options);
    }

    /**
     * @return AppSettings
     */
    public static function fromConfig()
    {
        $configDir = sprintf('%s/.noiselabs', getenv('HOME'));
        $settingsLoader = new SettingsLoader($configDir);

        return new self($settingsLoader->load());
    }

    /**
     * Persist current settings.
     */
    public function save()
    {
        $configDir = sprintf('%s/.noiselabs', getenv('HOME'));
        $settingsLoader = new SettingsLoader($configDir);
        $settingsLoader->save($this->options);
    }

    /**
     * @param $key
     *
     * @return string
     */
    public function getOption($key)
    {
        if (!isset($this->options[$key])) {
            throw new OutOfBoundsException(sprintf('Option key "%s" does not exist.', $key));
        }

        return $this->options[$key];
    }

    /**
     * @param array $options
     */
    public function merge(array $options)
    {
        $this->options = $this->resolver->resolve($options);
    }
}

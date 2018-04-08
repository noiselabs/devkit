<?php

namespace Noiselabs\Devkit\Infra\Command;

final class Docker
{
    /**
     * run: Run a one-off command.
     */
    public static function run(string $image, string $command, $options = []): DockerRemoteCommand
    {
        return self::buildDockerCommand('run --rm', $image, $command, $options);
    }

    private static function buildDockerCommand(
        string $dockerCommand,
        ?string $image = null,
        ?string $args = null,
        array $options = []
    ): DockerRemoteCommand {
        $command = 'docker ' . $dockerCommand;

        if (!empty($options)) {
            $command .= sprintf(' %s', implode(' ', $options));
        }

        if (null !== $image) {
            $command .= sprintf(' %s', $image);
        }

        if (null !== $args) {
            $command .= sprintf(' %s', $args);
        }

        return new DockerRemoteCommand($command);
    }
}

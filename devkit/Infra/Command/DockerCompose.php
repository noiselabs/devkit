<?php

namespace Noiselabs\Devkit\Infra\Command;

/**
 * Define and run multi-container applications with Docker.
 */
final class DockerCompose
{
    /**
     * build: Build or rebuild services.
     */
    public static function build(?string $service = null, ?string $command = null, array $options = []): DockerRemoteCommand
    {
        return self::buildDockerCommand('build', $service, $command, $options);
    }

    /**
     * down: Stop and remove containers, networks, images, and volumes.
     */
    public static function down(?string $service = null, ?string $command = null, array $options = []): DockerRemoteCommand
    {
        return self::buildDockerCommand('down', $service, $command, $options);
    }

    /**
     * exec: Execute a command in a running container.
     */
    public static function exec(string $service, string $command, array $options = []): DockerRemoteCommand
    {
        return self::buildDockerCommand('exec', $service, $command, $options);
    }

    /**
     * logs: View output from containers.
     */
    public static function logs(?string $service = null, ?string $command = null, array $options = []): DockerRemoteCommand
    {
        return self::buildDockerCommand('logs', $service, $command, $options);
    }

    /**
     * run: Run a one-off command.
     */
    public static function run(string $service, string $command, array $options = []): DockerRemoteCommand
    {
        return self::buildDockerCommand('run --rm', $service, $command, $options);
    }

    /**
     * start: Start services.
     */
    public static function start(string $service, array $options = []): DockerRemoteCommand
    {
        return self::buildDockerCommand('start', $service, null, $options);
    }

    /**
     * stop: Stop services.
     */
    public static function stop(string $service = null, array $options = []): DockerRemoteCommand
    {
        return self::buildDockerCommand('stop', $service, null, $options);
    }

    /**
     * up: Create and start containers.
     */
    public static function up(string $service = null, array $options = []): DockerRemoteCommand
    {
        return self::buildDockerCommand('up', $service, null, $options);
    }

    private static function buildDockerCommand($dockerComposeCommand, $service = null, $args = null, $options = []): DockerRemoteCommand
    {
        $command = 'docker-compose ' . $dockerComposeCommand;

        if (!empty($options)) {
            $command .= sprintf(' %s', implode(' ', $options));
        }

        if (null !== $service) {
            $command .= sprintf(' %s', $service);
        }

        if (null !== $args) {
            $command .= sprintf(' %s', $args);
        }

        return new DockerRemoteCommand($command);
    }
}

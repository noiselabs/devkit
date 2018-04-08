<?php

namespace Noiselabs\Devkit\Infra\Command;

class DockerRemoteCommand implements RemoteCommand
{
    /**
     * @var string
     */
    private $command;

    public function __construct(string $command)
    {
        $this->command = $command;
    }

    public function __toString(): string
    {
        return $this->command;
    }
}

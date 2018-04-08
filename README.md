# Devkit

Yet another **dev**eloper tool**kit**.

## Installation

```
$ composer install
```

## Usage

```
$ app/bin/devkit
DevkitApp 0.1.0

Usage:
  command [options] [arguments]

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  help           Displays help for a command
  list           Lists commands
 docker
  docker:logs    Follow Docker logs
  docker:shell   Get a Bash shell in a running Docker container
  docker:start   Starts a service
  docker:stop    Stops a service
 env
  env:down       Shutdowns the development environment (stops and removes containers)
  env:reload     Reloads the environment (stop, build and up)
  env:setup      Set up the development environment
  env:up         Boots the development environment (starts Docker containers and installs dependencies)
 phpqa
  phpqa:list     List all available PHP QA tools
  phpqa:phan     A static analyzer for PHP
  phpqa:phpstan  PHPStan - PHP Static Analysis Tool
 vagrant
  vagrant:halt   Stops the Vagrant machine
  vagrant:up     Starts and provisions the Vagrant environment                                                            

```

Add commands as needed in `app/src/Application::construct()`.

## Copyright

Copyright (c) 2018 [Vítor Brandão](https://noiselabs.io). Licensed under the [MIT License](LICENSE).
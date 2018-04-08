<?php

namespace Noiselabs\Devkit\Infra;

use SplFileInfo;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Filesystem\Filesystem;

class File extends SplFileInfo
{
    /**
     * @param string $path
     * @param bool $shouldCreate Create file if it doesn't exist?
     * @param bool $isDir
     *
     * @return File
     */
    public static function fromCommandArgument($path, $shouldCreate = false, $isDir = false)
    {
        if (empty($path)) {
            throw new RuntimeException("Path cannot be empty");
        }

        if ('~' === $path[0]) {
            if (!function_exists('posix_getuid')) {
                throw new RuntimeException("Function 'posix_getuid()', required to perform tilde expansion, is not available on this system.");
            }
            $info = posix_getpwuid(posix_getuid());
            $path = str_replace('~', $info['dir'], $path);
        } elseif ('/' !== $path[0]) {
            $path = getcwd() . '/' . $path;
        }

        $fs = new Filesystem();
        if (!$fs->exists($path)) {
            if (true !== $shouldCreate) {
                throw new RuntimeException(sprintf("File '%s' does not exist", $path));
            }
            if (true === $isDir) {
                $fs->mkdir($path, 0775);
            } else {
                $fs->dumpFile($path, '');
                $fs->chmod($path, 0664);
            }
        }
        $path = realpath($path);

        return new self($path);
    }

    /**
     * @param int $precision
     *
     * @return string
     */
    public function getFormattedSize($precision = 2)
    {
        $base = log($this->getSize(), 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];

        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[(int) floor($base)];
    }
}

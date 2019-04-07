<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Plugin;

use Jhofm\FlysystemIterator\FilesystemIterator;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\PluginInterface;

/**
 * Class FlysystemIteratorPlugin
 * @package Jhofm\FlysystemIterator\Plugin
 */
class IteratorPlugin implements PluginInterface
{
    /** @var Filesystem $filesystem */
    private $filesystem;

    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethod()
    {
        return 'createIterator';
    }

    /**
     * @param string $dir
     * @param array $options
     * @return FilesystemIterator
     */
    public function handle(string $dir = '/', array $options = [])
    {
        return new FilesystemIterator($this->filesystem, $dir, $options);
    }

    /**
     * Set the Filesystem object.
     *
     * @param FilesystemInterface $filesystem
     */
    public function setFilesystem(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }
}

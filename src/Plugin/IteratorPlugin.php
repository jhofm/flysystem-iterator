<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Plugin;

use Jhofm\FlysystemIterator\FilesystemFilterIterator;
use Jhofm\FlysystemIterator\FilesystemIterator;
use Jhofm\FlysystemIterator\Options\Options;
use Jhofm\FlysystemIterator\RecursiveFilesystemIteratorIterator;
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
     * @param array $options
     * @param string [optional] $dir
     * @return FilesystemIterator
     */
    public function handle(array $options = [], $dir = '/')
    {
        $iterator = new FilesystemIterator($this->filesystem, $dir, $options);
        $options = Options::fromArray($options);
        if ($options->{Options::OPTION_IS_RECURSIVE}) {
            $iterator = new RecursiveFilesystemIteratorIterator($iterator, $options);
        }
        if ($options->{Options::OPTION_FILTER} !== null) {
            $iterator = new FilesystemFilterIterator($iterator, $options->{Options::OPTION_FILTER});
        }
        return $iterator;
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

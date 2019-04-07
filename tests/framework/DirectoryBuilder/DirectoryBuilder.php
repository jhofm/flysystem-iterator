<?php

namespace Jhofm\FlysystemIterator\Test\Framework\DirectoryBuilder;

use Jhofm\FlysystemIterator\Test\Functional\DirectoryBuilder\DirectoryBuilderException;
use League\Flysystem\FileExistsException;
use League\Flysystem\Filesystem;

/**
 * Class DirectoryBuilder
 * @package Jhofm\FlysystemIterator\Test\Framework\DirectoryBuilder
 */
class DirectoryBuilder
{
    /** @var string root directory under which the tree is generated */
    private $basePath;

    /** @var Filesystem */
    private $fs;

    /**
     * DirectoryBuilder constructor.
     * @param string $basePath
     * @param Filesystem $fs
     */
    public function __construct(
        string $basePath,
        Filesystem $fs
    ) {
        $this->basePath = $basePath;
        $this->fs = $fs;
    }

    /**
     * @param array $paths
     * @return Filesystem
     * @throws DirectoryBuilderException
     */
    public function buildDirectory(array $paths)
    {
        try {
            foreach ($paths as $path) {
                $this->createPath($path);
            }
        } catch (FileExistsException $e) {
            throw new DirectoryBuilderException($e, 1, $e);
        }
        return $this->fs;
    }

    /**
     * @return Filesystem
     */
    public function getFs()
    {
        return $this->fs;
    }

    public function clearBasePath()
    {
        $this->fs->deleteDir($this->basePath);
    }

    /**
     * @return string
     */
    public function getBasePath() : string
    {
        return $this->basePath;
    }

    /**
     * @param string $path
     * @return string
     * @throws FileExistsException
     */
    private function createPath(string $path) : string
    {
        if ($path{0} !== '/') {
            $path = '/' . $path;
        }
        $absPath = $this->basePath . $path;

        // path that do not end in slash are considered files and created with dummy content
        if ($path{strlen($path)-1} !== '/') {
            $this->fs->write($absPath, '.');
        } else {
            $this->fs->createDir($absPath);
        }

        return $absPath;
    }
}
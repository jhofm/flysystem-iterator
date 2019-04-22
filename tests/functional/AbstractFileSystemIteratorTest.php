<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Functional;

use Jhofm\FlysystemIterator\Test\Framework\DirectoryBuilder\DirectoryBuilder;
use Jhofm\FlysystemIterator\Test\Framework\TestException;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class AbstractFileSystemIteratorTest
 * @package Jhofm\FlysystemIterator\Test\Functional
 */
abstract class AbstractFileSystemIteratorTest extends TestCase
{
    /** @var DirectoryBuilder $dirBuilder */
    private $dirBuilder;

    /** @var Filesystem $fs */
    protected $fs;

    /** @var string $root */
    protected $root = '/test-fs-iterator';

    /** @var array $setupPaths default path to setup in the filesystem fixture */
    protected $setupPaths = [
        '/a/',
        '/a/a',
        '/a/b/',
        '/a/b/c/',
        '/a/b/c/d',
        '/a/b/a',
        '/a/c',
        '/a/d/',
        '/a/d/a',
        '/b',
        '/c',
        '/d/'
    ];

    /** @var array $expectedPaths paths the iterator is expected to contain by default */
    protected $expectedPaths = [
        'test-fs-iterator/',
        'test-fs-iterator/a/',
        'test-fs-iterator/a/a',
        'test-fs-iterator/a/b/',
        'test-fs-iterator/a/b/a',
        'test-fs-iterator/a/b/c/',
        'test-fs-iterator/a/b/c/d',
        'test-fs-iterator/a/c',
        'test-fs-iterator/a/d/',
        'test-fs-iterator/a/d/a',
        'test-fs-iterator/b',
        'test-fs-iterator/c',
        'test-fs-iterator/d/'
    ];

    /**
     * Test setup
     * @throws TestException
     */
    protected function setUp() : void
    {
        $this->fs = new Filesystem(new MemoryAdapter());
        $this->dirBuilder = new DirectoryBuilder($this->root, $this->fs);
        $this->dirBuilder->buildDirectory($this->setupPaths);
    }

    /**
     * Test teardown
     */
    protected function tearDown(): void
    {
        $this->dirBuilder->clearBasePath();
    }
}

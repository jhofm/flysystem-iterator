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

    /** @var array $setupPaths */ 
    protected $setupPaths = [
        '/a/',
        '/a/a',
        '/a/b/',
        'a/b/a',
        '/a/c'
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

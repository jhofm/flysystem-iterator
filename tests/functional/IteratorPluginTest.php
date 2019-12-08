<?php

declare(strict_types=1);

namespace functional;

use Jhofm\FlysystemIterator\FilesystemFilterIterator;
use Jhofm\FlysystemIterator\Plugin\IteratorPlugin;
use Jhofm\FlysystemIterator\RecursiveFilesystemIteratorIterator;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;
use PHPUnit\Framework\TestCase;

/**
 * Class IteratorPluginTest
 * @package Jhofm\FlysystemIterator\Test\Functional
 * @group functional
 * @small
 */
class IteratorPluginTest extends TestCase
{
    /** @var Filesystem */
    protected $fs;

    public function setUp()
    {
        $this->fs = new Filesystem(new MemoryAdapter());
        $this->fs->addPlugin(new IteratorPlugin());
    }

    /**
     * Test create recursive fs iterator iterator via filesystem plugin
     * @test
     * @small
     */
    public function testCreateIteratorViaPlugin()
    {
        $iter = $this->fs->createIterator();
        $this->assertInstanceOf(RecursiveFilesystemIteratorIterator::class, $iter);
    }

    /**
     * Test create recursive fs iterator iterator via filesystem plugin
     * @test
     * @small
     */
    public function testCreateFilterIteratorViaPlugin()
    {
        $iter = $this->fs->createIterator(['filter' => function(array $item) { return true;}]);
        $this->assertInstanceOf(FilesystemFilterIterator::class, $iter);
    }

    /**
     * @test
     */
    public function testIterateSubDirectory()
    {
        $this->fs->createDir('foo');
        $this->fs->write('/foo/bar', 'baz');
        $iter = $this->fs->createIterator([], 'foo');
        $this->assertTrue($iter->valid());
        $this->assertCount(1, $iter);
    }
}

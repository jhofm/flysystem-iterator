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
 * @package functional
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
}

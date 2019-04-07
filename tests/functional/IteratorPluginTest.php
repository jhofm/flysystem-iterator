<?php

declare(strict_types=1);

namespace functional;

use Jhofm\FlysystemIterator\FilesystemIterator;
use Jhofm\FlysystemIterator\Plugin\IteratorPlugin;
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
     * Test iterator instantiation via filesystm plugin
     * @test
     * @small
     */
    public function testCreateIteratorViaPlugin()
    {
        $iter = $this->fs->createIterator();
        $this->assertInstanceOf(FilesystemIterator::class, $iter);
    }
}

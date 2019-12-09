<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Functional;

use Jhofm\FlysystemIterator\Options\Options;
use Jhofm\FlysystemIterator\Plugin\IteratorPlugin;
use Jhofm\FlysystemIterator\RecursiveFilesystemIteratorIterator;

/**
 * Class RecursiveFilesystemIteratorSkipRootTest
 * @package Jhofm\FlysystemIterator\Test\Functional
 *
 * @group functional
 * @small
 */
class RecursiveFilesystemIteratorSkipRootTest extends AbstractFileSystemIteratorTest
{
    public function setUp() : void
    {
        parent::setUp();
        $this->fs->addPlugin(new IteratorPlugin());
    }

    /**
     * @test
     */
    public function testDoNotSkipRootByDefault()
    {
        $iter = $this->fs->createIterator([Options::OPTION_RETURN_VALUE => Options::VALUE_PATH_RELATIVE]);
        $this->assertInstanceOf(RecursiveFilesystemIteratorIterator::class, $iter);
        $firstItem = $iter->current();
        $this->assertEquals($this->expectedPaths[0], $firstItem);
        $this->assertCount(count($this->expectedPaths), $iter);
        foreach ($iter as $index => $path) {
            $this->assertEquals($this->expectedPaths[$index], $path);
        }
        $iter->seek(1);
        $this->assertEquals($this->expectedPaths[1], $iter->current());
        $arrayFromJson = json_decode(json_encode($iter), true);
        $this->assertCount(count($this->expectedPaths), $arrayFromJson);
    }

    /**
     * @test
     */
    public function testSkipRoot()
    {
        /** @var RecursiveFilesystemIteratorIterator $iter */
        $iter = $this->fs->createIterator(
            [
                Options::OPTION_RETURN_VALUE => Options::VALUE_PATH_RELATIVE,
                Options::OPTION_SKIP_ROOT_DIRECTORY => true
            ]
        );
        $this->assertInstanceOf(RecursiveFilesystemIteratorIterator::class, $iter);
        $firstItem = $iter->current();
        $this->assertEquals($this->expectedPaths[1], $firstItem);
        $this->assertEquals(0, $iter->key());
        $expected = $this->expectedPaths;
        array_shift($expected);
        $this->assertCount(count($expected), $iter);
        foreach ($iter as $index => $path) {
            $this->assertEquals($expected[$index], $path);
        }
        $iter->seek(1);
        $this->assertEquals($expected[1], $iter->current());
        $arrayFromJson = json_decode(json_encode($iter), true);
        $this->assertCount(count($expected), $arrayFromJson);
    }
}

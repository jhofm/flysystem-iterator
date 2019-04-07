<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Functional;

use Jhofm\FlysystemIterator\FilesystemIterator;
use Jhofm\FlysystemIterator\Options\Options;
use Jhofm\FlysystemIterator\Test\Framework\TestException;

/**
 * Class FilesystemIteratorInfoByPathTest
 * @package Jhofm\FlysystemIterator\Test\Functional
 *
 * @small
 */
class FilesystemIteratorInfoByPathTest extends AbstractFileSystemIteratorTest
{
    /** @var FilesystemIterator $subject */
    private $subject;

    /** @var array $expectedInfo */
    protected $expectedInfo = [
        'test-fs-iterator/a/',
        'test-fs-iterator/a/a',
        'test-fs-iterator/a/b/',
        'test-fs-iterator/a/b/a',
        'test-fs-iterator/a/c'
    ];

    /**
     * @throws TestException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->subject = new FilesystemIterator(
            $this->fs,
            $this->root,
            [
                Options::OPTION_RETURN_KEY => Options::VALUE_PATH_RELATIVE,
                Options::OPTION_RETURN_VALUE => Options::VALUE_LIST_INFO
            ]
        );
    }

    /**
     * @test
     */
    public function testIterateInfoByPaths()
    {
        $i = 0;
        foreach ($this->subject as $path => $info) {
            $expectedPath = $this->expectedInfo[$i++];
            $shouldBeDir = $expectedPath{strlen($expectedPath)-1} === '/';
            $this->assertEquals($expectedPath, $path);
            $this->assertIsArray($info);
            $this->assertArrayHasKey('type', $info);
            $this->assertEquals(
                $shouldBeDir ? 'dir' : 'file',
                $info['type']
            );
            $this->assertArrayHasKey('path', $info);
            $this->assertEquals(
                $shouldBeDir ? substr($expectedPath, 0, strlen($expectedPath)-1) : $expectedPath,
                $info['path']
            );
        }
    }
}

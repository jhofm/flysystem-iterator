<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Functional;

use Jhofm\FlysystemIterator\FilesystemFilterIterator;
use Jhofm\FlysystemIterator\FilesystemIterator;
use Jhofm\FlysystemIterator\Options\Options;
use Jhofm\FlysystemIterator\RecursiveFilesystemIteratorIterator;
use Jhofm\FlysystemIterator\Test\Framework\TestException;

/**
 * Class FilesystemIteratorCustomFileFilterTest
 * @package Jhofm\FlysystemIterator\Test\Functional
 * @group functional
 * @small
 */
class FilesystemIteratorCustomFileFilterTest extends AbstractFileSystemIteratorTest
{
    private $subject;

    /** @var array $expectedPaths paths the iterator is expected to contain by default */
    protected $expectedPaths = [
        'test-fs-iterator/a/a',
        'test-fs-iterator/a/b/a',
        'test-fs-iterator/a/b/c/d',
        'test-fs-iterator/a/c',
        'test-fs-iterator/a/d/a',
        'test-fs-iterator/b',
        'test-fs-iterator/c'
    ];

    /**
     * Test setup
     * @throws TestException
     */
    protected function setUp() : void
    {
        parent::setUp();
        $this->subject = new FilesystemFilterIterator(
            new RecursiveFilesystemIteratorIterator(
                new FilesystemIterator(
                    $this->fs,
                    $this->root,
                    [
                        Options::OPTION_RETURN_VALUE => Options::VALUE_PATH_RELATIVE,
                    ]
                )
            ),
            function (array $item) : bool {
                return $item['type'] === 'file';
            }
        );
    }

    /**
     * @test
     */
    public function testIteratePathsByIndex()
    {
        $i = 0;
        foreach ($this->subject as $index => $path) {
            $this->assertEquals($i, $index);
            $this->assertEquals($this->expectedPaths[$i++], $path);
        }
    }
}

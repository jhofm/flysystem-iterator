<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Functional;

use Jhofm\FlysystemIterator\FilesystemFilterIterator;
use Jhofm\FlysystemIterator\FilesystemIterator;
use Jhofm\FlysystemIterator\Options\Options;
use Jhofm\FlysystemIterator\RecursiveFilesystemIteratorIterator;
use Jhofm\FlysystemIterator\Test\Framework\TestException;

/**
 * Class FilesystemIteratorCustomFilterTest
 * @package Jhofm\FlysystemIterator\Test\Functional
 */
class FilesystemIteratorCustomDirectoryFilterTest extends AbstractFileSystemIteratorTest
{
    /** @var FilesystemIterator $subject */
    private $subject;

    /** @var array $setupPaths default path to setup in the filesystem fixture */
    protected $expectedPaths = [
        'test-fs-iterator/a/',
        'test-fs-iterator/a/b/',
        'test-fs-iterator/a/b/c/',
        'test-fs-iterator/a/d/'
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
                return $item['type'] === 'dir';
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

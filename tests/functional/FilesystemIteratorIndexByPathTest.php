<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Functional;

use Jhofm\FlysystemIterator\FilesystemIterator;
use Jhofm\FlysystemIterator\Options\Options;
use Jhofm\FlysystemIterator\Test\Framework\TestException;

/**
 * Class FilesystemIteratorIndexByPathTest
 * @package Jhofm\FlysystemIterator\Test\Functional
 */
class FilesystemIteratorIndexByPathTest extends AbstractFileSystemIteratorTest
{
    /** @var FilesystemIterator $subject */
    private $subject;

    /** @var array $expectedPaths */
    protected $expectedPaths = [
        'test-fs-iterator/a/',
        'test-fs-iterator/a/a',
        'test-fs-iterator/a/b/',
        'test-fs-iterator/a/b/a',
        'test-fs-iterator/a/c'
    ];

    /**
     * Test setup
     * @throws TestException
     */
    protected function setUp() : void
    {
        parent::setUp();
        $this->subject = new FilesystemIterator(
            $this->fs,
            $this->root,
            [
                Options::OPTION_RETURN_KEY => Options::VALUE_PATH_RELATIVE,
                Options::OPTION_RETURN_VALUE => Options::VALUE_INDEX,
                Options::OPTION_RECURSIVE => true
            ]
        );
    }

    /**
     * @test
     * @small
     *
     */
    public function testIterateIndexByPath()
    {
        $i = 0;
        foreach ($this->subject as $path => $index) {
            $this->assertEquals($i, $index);
            $this->assertEquals($this->expectedPaths[$i++], $path);
        }
    }
}

<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Functional;

use Jhofm\FlysystemIterator\FilesystemIterator;
use Jhofm\FlysystemIterator\IteratorException;
use Jhofm\FlysystemIterator\Options\Options;
use Jhofm\FlysystemIterator\Test\Framework\TestException;

/**
 * Class FilesystemIteratorSeekableTest
 * @package Jhofm\FlysystemIterator\Test\Functional
 */
class FilesystemIteratorSeekableTest extends AbstractFileSystemIteratorTest
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
                Options::OPTION_RETURN_KEY => Options::VALUE_INDEX,
                Options::OPTION_RETURN_VALUE => Options::VALUE_PATH_RELATIVE
            ]
        );
    }

    /**
     * @test
     * @throws IteratorException
     */
    public function testSeekPaths()
    {
        foreach ([4, 0, 2, 1, 3] as $index) {
            $this->subject->seek($index);
            $this->assertEquals($this->expectedPaths[$index], $this->subject->current());
        }
    }

    /**
     * @test
     * @expectedException \Jhofm\FlysystemIterator\IteratorException
     */
    public function testSeekNegativePositionThrowsException()
    {
        $this->subject->seek(-1);
    }

    /**
     * @test
     * @expectedException \Jhofm\FlysystemIterator\IteratorException
     */
    public function testSeekOutOfBoundsThrowsException()
    {
        $this->subject->seek(100);
    }

    /**
     * Coverage, yay.
     * @test
     */
    public function testSeekToZero()
    {
        $this->subject->seek(0);
        $this->assertEquals($this->subject->current(), $this->expectedPaths[0]);
    }
}

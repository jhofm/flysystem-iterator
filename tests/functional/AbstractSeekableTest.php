<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Functional;

use Jhofm\FlysystemIterator\Test\Framework\TestException;
use SeekableIterator;

/**
 * Class AbstractSeekableTest
 * @package Jhofm\FlysystemIterator\Test\Functional
 * @group functional
 * @small
 */
abstract class AbstractSeekableTest extends AbstractFileSystemIteratorTest
{
    abstract protected function getSubject() : SeekableIterator;

    /** @var SeekableIterator */
    protected $subject;

    /**
     * @throws TestException
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->getSubject();
    }

    /**
     * @test
     * @throws Jhofm\FlysystemIterator\IteratorException
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

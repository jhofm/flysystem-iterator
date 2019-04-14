<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Functional;

use Jhofm\FlysystemIterator\FilesystemIterator;
use Jhofm\FlysystemIterator\Options\Options;
use Jhofm\FlysystemIterator\Test\Framework\TestException;
use Jhofm\FlysystemIterator\RecursiveFilesystemIteratorIterator;

/**
 * Class FilesystemIteratorTest
 * @package Jhofm\FlysystemIterator\Test\Functional
 *
 * @small
 */
class RecursiveFilesystemIteratorIteratorPathTest extends AbstractFileSystemIteratorTest
{
    /** @var FilesystemIterator $subject */
    private $subject;

    /**
     * Test setup
     * @throws TestException
     */
    protected function setUp() : void
    {
        parent::setUp();
        $this->subject = new RecursiveFilesystemIteratorIterator(
            new FilesystemIterator(
                $this->fs,
                '/',
                [
                    Options::OPTION_RETURN_VALUE => Options::VALUE_PATH_RELATIVE,
                ]
            )
        );
    }

    /**
     * @test
     */
    public function testCurrentWithoutResetYieldsFirstResult()
    {
        $this->assertEquals($this->expectedPaths[0], $this->subject->current());
    }

    /**
     * @test
     */
    public function testIteratePathsByIndex()
    {
        $i=0;
        foreach ($this->subject as $index => $path) {
            $this->assertEquals($i, $index);
            $this->assertEquals($this->expectedPaths[$i++], $path);
        }
    }
}

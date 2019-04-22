<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Functional;

use Jhofm\FlysystemIterator\FilesystemIterator;
use Jhofm\FlysystemIterator\Options\Options;

/**
 * Class FilesystemIteratorInvalidOptionsTest
 * @package Jhofm\FlysystemIterator\Test\Functional
 * @group functional
 * @small
 */
class FilesystemIteratorInvalidOptionsTest extends AbstractFileSystemIteratorTest
{
    /**
     * @test
     * @expectedException \Jhofm\FlysystemIterator\IteratorException
     */
    public function testInvalidValueOptionThrowsException()
    {
        $iterator = new FilesystemIterator($this->fs, '/', ['value' => 'foo']);
        $iterator->current();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function getGetInvalidOptionThrowsInvalidArgumentException()
    {
        $options = Options::fromArray([]);
        $options->foo;
    }
}

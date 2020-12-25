<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Functional;

use InvalidArgumentException;
use Jhofm\FlysystemIterator\FilesystemIterator;
use Jhofm\FlysystemIterator\IteratorException;
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
     */
    public function testInvalidValueOptionThrowsException()
    {
        $this->expectException(IteratorException::class);
        $iterator = new FilesystemIterator($this->fs, '/', ['value' => 'foo']);
        $iterator->current();
    }

    /**
     * @test
     */
    public function getGetInvalidOptionThrowsInvalidArgumentException()
    {
        $this->expectException(InvalidArgumentException::class);
        $options = Options::fromArray([]);
        $options->foo;
    }
}

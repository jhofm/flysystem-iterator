<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Functional;

use Jhofm\FlysystemIterator\FilesystemIterator;

/**
 * Class FilesystemIteratorInvalidOptionsTest
 * @package Jhofm\FlysystemIterator\Test\Functional
 *
 * @small
 *
 */
class FilesystemIteratorInvalidOptionsTest extends AbstractFileSystemIteratorTest
{
    /**
     * @test
     * @expectedException \Jhofm\FlysystemIterator\IteratorException
     */
    public function testInvalidKeyOptionThrowsException()
    {
        $iterator = new FilesystemIterator($this->fs, '/', ['key' => 'foo']);
        $iterator->key();
    }

    /**
     * @test
     * @expectedException \Jhofm\FlysystemIterator\IteratorException
     */
    public function testInvalidValueOptionThrowsException()
    {
        $iterator = new FilesystemIterator($this->fs, '/', ['value' => 'foo']);
        $iterator->current();
    }
}

<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Functional;

use Jhofm\FlysystemIterator\FilesystemFilterIterator;
use Jhofm\FlysystemIterator\FilesystemIterator;
use Jhofm\FlysystemIterator\Options\Options;
use Jhofm\FlysystemIterator\RecursiveFilesystemIteratorIterator;
use League\Flysystem\Filesystem;

/**
 * Class FilesystemIteratorAllPassFilterTest
 * @package Jhofm\FlysystemIterator\Test\Functional
 * @group functional
 * @small
 */
class FilesystemIteratorAllPassFilterTest extends AbstractFileSystemIteratorTest
{
    private $subject;

    public function setUp(): void
    {
        parent::setUp();
        $this->subject = new FilesystemFilterIterator(
            new RecursiveFilesystemIteratorIterator(
                new FilesystemIterator(
                    $this->fs,
                    '/',
                    [Options::OPTION_RETURN_VALUE => Options::VALUE_PATH_RELATIVE]
                )
            ),
            function (array $item) {
                return true;
            }
        );
    }

    /**
     * @test
     */
    public function testIteratePaths()
    {
        $i = 0;
        foreach ($this->subject as $index => $path) {
            $this->assertEquals($i, $index);
            $this->assertEquals($this->expectedPaths[$i++], $path);
        }
    }
}

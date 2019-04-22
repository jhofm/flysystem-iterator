<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Functional;

use Jhofm\FlysystemIterator\FilesystemFilterIterator;
use Jhofm\FlysystemIterator\FilesystemIterator;
use Jhofm\FlysystemIterator\Filter\FilterFactory;
use Jhofm\FlysystemIterator\Options\Options;
use Jhofm\FlysystemIterator\RecursiveFilesystemIteratorIterator;
use Jhofm\FlysystemIterator\Test\Framework\TestException;

/**
 * Class FilesystemFilterIteratorTest
 * @package Jhofm\FlysystemIterator\Test\Functional
 * @group functional
 * @small
 */
class FilesystemFilterIteratorTest extends AbstractFileSystemIteratorTest
{
    /** @var FilesystemIterator $subject */
    private $subject;

    /** @var array $expectedPaths paths the iterator is expected to contain by default */
    protected $expectedPaths = [
        'test-fs-iterator/a/b/c/',
        'test-fs-iterator/a/b/c/d',
        'test-fs-iterator/a/c',
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
                    '/',
                    [
                        Options::OPTION_RETURN_VALUE => Options::VALUE_PATH_RELATIVE,
                    ]
                )
            ),
            FilterFactory::pathContainsString('c')
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

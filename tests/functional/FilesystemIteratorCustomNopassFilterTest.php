<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Functional;

use Jhofm\FlysystemIterator\FilesystemFilterIterator;
use Jhofm\FlysystemIterator\FilesystemIterator;
use Jhofm\FlysystemIterator\Options\Options;
use Jhofm\FlysystemIterator\RecursiveFilesystemIteratorIterator;
use Jhofm\FlysystemIterator\Test\Framework\TestException;

/**
 * Class FilesystemIteratorCustomNopassFilterTest
 * @package Jhofm\FlysystemIterator\Test\Functional
 * @group functional
 * @small
 */
class FilesystemIteratorCustomNopassFilterTest extends AbstractFileSystemIteratorTest
{
    private $subject;

    /**
     * Test setup
     * @throws TestException
     */
    protected function setUp()
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
            function (array $item) : bool {
                return false;
            }
        );
    }

    /**
     * @test
     */
    public function testCountIsZero()
    {
        $this->assertCount(0, $this->subject);
    }
}

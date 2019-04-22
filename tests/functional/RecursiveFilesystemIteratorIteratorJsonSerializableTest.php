<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Functional;

use Jhofm\FlysystemIterator\FilesystemIterator;
use Jhofm\FlysystemIterator\Options\Options;
use Jhofm\FlysystemIterator\RecursiveFilesystemIteratorIterator;
use Jhofm\FlysystemIterator\Test\Framework\TestException;

/**
 * Class RecursiveFilesystemIteratorIteratorJsonSerializableTest
 * @package Jhofm\FlysystemIterator\Test\Functional
 * @group functional
 * @small
 */
class RecursiveFilesystemIteratorIteratorJsonSerializableTest extends AbstractFileSystemIteratorTest
{
    /** @var RecursiveFilesystemIteratorIterator $subject */
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

    public function testJsonSerializeable()
    {
        $this->assertEquals(json_encode($this->expectedPaths), json_encode($this->subject));
    }
}

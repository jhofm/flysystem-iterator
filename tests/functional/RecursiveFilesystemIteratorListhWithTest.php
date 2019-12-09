<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Functional;

use Jhofm\FlysystemIterator\FilesystemIterator;
use Jhofm\FlysystemIterator\Options\Options;
use Jhofm\FlysystemIterator\RecursiveFilesystemIteratorIterator;

/**
 * Class RecursiveFilesystemIteratorListhWithTest
 *
 * @package Jhofm\FlysystemIterator\Test\Functional
 * @group functional
 * @small
 */
class RecursiveFilesystemIteratorListhWithTest extends AbstractFileSystemIteratorTest
{
    /** @var RecursiveFilesystemIteratorIterator $subject */
    private $subject;

    /**
     * @test
     */
    public function testEmptyListYieldsItem()
    {
        $this->subject = new RecursiveFilesystemIteratorIterator(
            new FilesystemIterator(
                $this->fs,
                '/',
                [
                    Options::OPTION_LIST_WITH => ['mimetype']
                ]
            )
        );

        // directories never have metadata
        $item = $this->subject->current();
        $this->assertArrayHasKey('type', $item);
        $this->assertEquals('dir', $item['type']);
        $this->assertArrayNotHasKey('mimetype', $item);

        //forward to first file
        $this->subject->next();
        $this->subject->next();
        $item = $this->subject->current();

        $this->assertArrayHasKey('type', $item);
        $this->assertEquals('file', $item['type']);
        $this->assertArrayHasKey('mimetype', $item);
        $this->assertEquals('application/octet-stream', $item['mimetype']);
    }

    /**
     * @test
     * @expectedException \Jhofm\FlysystemIterator\IteratorException
     */
    public function testIvalidMetaDataKeyThrowsxception()
    {
        $this->subject = new RecursiveFilesystemIteratorIterator(
            new FilesystemIterator(
                $this->fs,
                '/',
                [
                    Options::OPTION_LIST_WITH => ['foobar']
                ]
            )
        );
        //forward to first file
        $this->subject->next();
        $this->subject->next();
        $this->subject->current();
    }

}

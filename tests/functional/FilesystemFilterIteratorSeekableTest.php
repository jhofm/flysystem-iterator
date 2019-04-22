<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Functional;

use Jhofm\FlysystemIterator\FilesystemFilterIterator;
use Jhofm\FlysystemIterator\FilesystemIterator;
use Jhofm\FlysystemIterator\Options\Options;
use Jhofm\FlysystemIterator\RecursiveFilesystemIteratorIterator;
use SeekableIterator;

/**
 * Class FilesystemFilterIteratorSeekableTest
 * @package Jhofm\FlysystemIterator\Test\Functional
 * @group functional
 * @small
 */
class FilesystemFilterIteratorSeekableTest extends AbstractSeekableTest
{
    protected function getSubject(): SeekableIterator
    {
        return new FilesystemFilterIterator(
            new RecursiveFilesystemIteratorIterator(
                new FilesystemIterator(
                    $this->fs,
                    '/',
                    [
                        Options::OPTION_RETURN_VALUE => Options::VALUE_PATH_RELATIVE,
                    ]
                )
            ),
            function (array $item) {
                return true;
            }
        );
    }
}

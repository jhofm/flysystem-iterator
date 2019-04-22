<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator;

use Countable;
use JsonSerializable;
use RecursiveIteratorIterator;
use SeekableIterator;
use Traversable;

/**
 * Class RecursiveFilesystemIterator
 * @method FilesystemIterator getInnerIterator()
 * @method FilesystemIterator getSubIterator($level)
 */
class RecursiveFilesystemIteratorIterator extends RecursiveIteratorIterator implements Countable, SeekableIterator, JsonSerializable
{
    use SeekableIteratorTrait, JsonSerializableIteratorTrait, CountableIteratorTrait;

    /** @var int */
    private $globalIndex = 0;

    /**
     * RecursiveFilesystemIterator constructor.
     * @param Traversable $iterator
     * @param int $mode
     * @param int $flags
     */
    public function __construct(Traversable $iterator, $mode = self::SELF_FIRST, $flags = 0)
    {
        parent::__construct($iterator, $mode, $flags);
    }

    public function next()
    {
        parent::next();
        ++$this->globalIndex;
    }

    public function key() : int
    {
       return $this->globalIndex;
    }

    public function rewind()
    {
        parent::rewind();
        $this->globalIndex = 0;
    }
}

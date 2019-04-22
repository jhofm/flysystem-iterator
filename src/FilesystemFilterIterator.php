<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator;

use Closure;
use Countable;
use FilterIterator;
use Iterator;
use JsonSerializable;
use SeekableIterator;

/**
 * Class FilteredFilesystemIterator
 * @package Jhofm\FlysystemIterator
 *
 * @method FilesystemIterator getInnerIterator()
 */
class FilesystemFilterIterator extends FilterIterator implements SeekableIterator, Countable, JsonSerializable
{
    use SeekableIteratorTrait, CountableIteratorTrait, JsonSerializableIteratorTrait;

    /** @var callable  */
    protected $filter;

    /** @var int */
    private $globalIndex = 0;

    /**
     * FilesystemFilterIterator constructor.
     * @param Iterator $iterator
     * @param Closure $filter
     */
    public function __construct(Iterator $iterator, callable $filter)
    {
        parent::__construct($iterator);
        $this->filter = $filter;
    }

    /**
     * Check whether the current element of the iterator is acceptable
     * @link https://php.net/manual/en/filteriterator.accept.php
     * @return bool true if the current element is acceptable, otherwise false.
     * @since 5.1.0
     */
    public function accept()
    {
        return ($this->filter)($this->getInnerIterator()->getCurrentItem());
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

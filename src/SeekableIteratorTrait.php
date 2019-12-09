<?php

namespace Jhofm\FlysystemIterator;

/**
 * Trait GlobalKeyTrait
 * @package Jhofm\FlysystemIterator\Traits
 */
trait SeekableIteratorTrait
{
    /**
     * Seeks to a position
     * @link https://php.net/manual/en/seekableiterator.seek.php
     * @param int $position <p>
     * The position to seek to.
     * </p>
     * @return void
     * @since 5.1.0
     * @throws IteratorException
     */
    public function seek($position)
    {
        //recursiveiteratoriterator will deliver first element twice if starting on a dir without calling rewind() first
        $this->rewind();
        $current = $this->key();
        if ($current === $position) {
            return;
        }
        if (!is_int($position) || $position < 0) {
            throw new IteratorException(sprintf('Unable to seek invalid position "%s".', (string) $position), 1);
        }
        while ($this->valid() && $current < $position) {
            $this->next();
            $current = $this->key();
        }
        if ($current < $position) {
            throw new IteratorException(sprintf('Iterator out of bounds at position %u', $position));
        }
    }

    abstract function key();
    abstract function next();
    abstract function valid();
    abstract function current();
    abstract function rewind();
}
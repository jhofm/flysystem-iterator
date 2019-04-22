<?php


namespace Jhofm\FlysystemIterator;

/**
 * Trait IteratorCountTrait
 * @package Jhofm\FlysystemIterator
 */
trait CountableIteratorTrait
{
    /**
     * Count elements of an object
     *
     * Can be expensive to compute since it requires a full iteration over all elements
     *
     * @link https://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     * @since 5.1.0
     */
    public function count()
    {
        return iterator_count($this);
    }

    abstract function key();
    abstract function next();
    abstract function valid();
    abstract function current();
    abstract function rewind();
}

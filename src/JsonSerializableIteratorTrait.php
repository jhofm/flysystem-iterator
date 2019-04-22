<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator;

/**
 * Trait JsonSerializableTrait
 * @package Jhofm\FlysystemIterator
 */
trait JsonSerializableIteratorTrait
{
    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return iterator_to_array($this);
    }

    abstract function key();
    abstract function next();
    abstract function valid();
    abstract function current();
    abstract function rewind();
}

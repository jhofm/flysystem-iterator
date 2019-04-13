<?php

declare(strict_types = 1);

namespace Jhofm\FlysystemIterator;

use Jhofm\FlysystemIterator\Options\Options;
use League\Flysystem\Filesystem;
use SeekableIterator;

/**
 * Class FilesystemIterator
 * @package Jhofm\FlysystemIterator
 */
class FilesystemIterator implements SeekableIterator
{
    /** @var Filesystem $fs */
    private $fs;
    /** @var string $dir directory the iterator iterates over */
    private $dir;
    /** @var array|null $item current directory list item */
    private $item;
    /** @var array|null */
    private $list;
    /** @var int $index current index of the iterator's directory */
    private $index = 0;
    /** @var int $innerIterations total number of iterations of inner iterators */
    private $innerIterations = 0;
    /** @var FilesystemIterator $innerIterator */
    private $innerIterator;
    /** @var Options $options iterator configuration */
    private $options;

    /**
     * FilesystemPathIterator constructor.
     *
     * @param Filesystem $fs filesystem to use
     * @param string [optional] $dir directory for iteration
     * @param array [optional] $options additional options, currently unused
     */
    public function __construct(Filesystem $fs, string $dir = '/', array $options = [])
    {
        $this->fs = $fs;
        $this->dir = $dir;
        if ($this->dir{strlen($this->dir)-1} !== '/') {
            $this->dir = $this->dir . '/';
        }
        $this->options = Options::fromArray($options);
        $this->list = $this->fs->listContents($this->dir);
        $this->updateItem();
    }

    /**
     * Test if current path is a directory
     * @return bool
     */
    private function hasChildren() : bool
    {
        return $this->valid() && $this->isDirectory();
    }

    /**
     * Creates a filesystem iterator for the current directory
     * @return FilesystemIterator
     */
    private function getChildren() : FilesystemIterator
    {
        return new self($this->fs, $this->getCurrentPath(), $this->options->toArray());
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        if ($this->innerIterator === null
            && $this->isRecursive()
            && $this->hasChildren()) {
            $this->innerIterator = $this->getChildren();
            ++$this->innerIterations;
            return;
        }

        if ($this->innerIterator !== null) {
            $this->innerIterator->next();
            if ($this->innerIterator->valid()) {
                ++$this->innerIterations;
                return;
            } else {
                $this->innerIterator = null;
            }
        }

        ++$this->index;
        $this->updateItem();
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     * @throws IteratorException
     */
    public function key()
    {
        switch ($this->options->{Options::OPTION_RETURN_KEY}) {
            case Options::VALUE_INDEX: return $this->getAbsoluteIndex();
            case Options::VALUE_PATH_RELATIVE: return $this->innerIterator !== null
                ? $this->innerIterator->key()
                : $this->getCurrentPath();
            default: throw new IteratorException('Invalid return type for key.');
        }
    }

    /**
     * @return int position of the current element within all paths iterated over
     */
    private function getAbsoluteIndex() : int
    {
        return $this->index + $this->innerIterations;
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid() : bool
    {
        if ($this->innerIterator && $this->innerIterator->valid()) {
            return true;
        }

        return $this->item !== null;
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
       $this->index = 0;
       $this->updateItem();
       $this->innerIterator = null;
       $this->innerIterations = 0;
    }

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     * @throws IteratorException
     */
    public function current()
    {
        if ($this->options->{Options::OPTION_RETURN_VALUE} === Options::VALUE_INDEX) {
            return $this->getAbsoluteIndex();
        }
        if ($this->innerIterator !== null) {
            return $this->innerIterator->current();
        }
        switch ($this->options->{Options::OPTION_RETURN_VALUE}) {
            case Options::VALUE_LIST_INFO: return $this->item;
            case Options::VALUE_PATH_RELATIVE: return $this->getCurrentPath();
            default: throw new IteratorException('Invalid return type for value.');
        }
    }

    /**
     * @return string
     */
    private function getCurrentPath() : string
    {
        return $this->item['path'] . ($this->isDirectory() ? '/' : '');
    }

    /**
     * @return bool
     */
    private function isDirectory() : bool
    {
        return is_array($this->item) && $this->item['type'] === 'dir';
    }

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
        $current = $this->getAbsoluteIndex();
        if ($current === $position) {
            return;
        }
        if (!is_int($position) || $position < 0) {
            throw new IteratorException(sprintf('Unable to seek invalid position "%s".', (string) $position), 1);
        }
        if ($current > $position || $this->list === null) {
            $this->rewind();
            $current = 0;
        }
        while ($this->valid() && $current < $position) {
            $this->next();
            $current = $this->getAbsoluteIndex();
        }
        if ($current < $position) {
            throw new IteratorException(sprintf('Iterator out of bounds at position %u', $position));
        }
    }

    /**
     * set current item by directory list and current index
     */
    private function updateItem() : void
    {
        $this->item = isset($this->list[$this->index]) ? $this->list[$this->index] : null;
    }

    /**
     * Check if iterator is in recursive mode
     *
     * @return bool
     */
    private function isRecursive() : bool
    {
        return (bool) $this->options->{Options::OPTION_RECURSIVE};
    }
}

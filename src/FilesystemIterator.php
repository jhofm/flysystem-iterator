<?php

declare(strict_types = 1);

namespace Jhofm\FlysystemIterator;

use Countable;
use Jhofm\FlysystemIterator\Options\Options;
use League\Flysystem\Filesystem;
use RecursiveIterator;
use SeekableIterator;

/**
 * Class FilesystemIterator
 * @package Jhofm\FlysystemIterator
 */
class FilesystemIterator implements RecursiveIterator, Countable, SeekableIterator, \JsonSerializable
{
    use SeekableIteratorTrait, CountableIteratorTrait, JsonSerializableIteratorTrait;

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
    /** @var Options $options iterator configuration */
    private $options;
    /** @var bool include additional metadata such as mimetype if list-with options are provided */
    private $addMetaData = false;

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
        $this->dir = $dir[strlen($dir)-1] !== '/' ? $dir . '/' : $dir;
        $this->options = Options::fromArray($options);
        $this->addMetaData = count($this->options->{Options::OPTION_LIST_WITH}) > 0;
        $this->list = $this->fs->listContents($this->dir);
        $this->updateItem();
    }

    /**
     * Test if current path is a directory
     * @return bool
     */
    public function hasChildren() : bool
    {
        return $this->isDirectory();
    }

    /**
     * Creates a filesystem iterator for the current directory
     * @return FilesystemIterator
     */
    public function getChildren() : FilesystemIterator
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
        ++$this->index;
        $this->updateItem();
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->index;
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
        switch ($this->options->{Options::OPTION_RETURN_VALUE}) {
            case Options::VALUE_LIST_INFO: return $this->getCurrentItem();
            case Options::VALUE_PATH_RELATIVE: return $this->getCurrentPath();
            default: throw new IteratorException('Invalid return type for value.');
        }
    }

    /**
     * @return array|null
     */
    public function getCurrentItem() : ?array
    {
        if ($this->addMetaData && $this->item && $this->item['type'] === 'file') {
            $missingKeys = array_diff($this->options->{Options::OPTION_LIST_WITH}, array_keys($this->item));
            return array_reduce($missingKeys, [$this, 'getMetadataByName'], $this->item);
        }
        return $this->item;
    }

    /**
     * @return string
     */
    private function getCurrentPath() : string
    {
        return $this->item['path'] . ($this->isDirectory() ? '/' : '');
    }

    /**
     * Get a meta-data value by key name.
     * Emulates behaviour of FlySystems ListWith plugin
     * (add additional metadata from the filesystem to returned items)
     *
     * @param array $item
     * @param string $key
     *
     * @return array
     * @throws IteratorException
     */
    private function getMetadataByName(array $item, $key) : array
    {
        $method = 'get' . ucfirst($key);
        if ( ! method_exists($this->fs, $method)) {
            throw new IteratorException('Could not get metadata for key: ' . $key);
        }
        $item[$key] = $this->fs->{$method}($item['path']);
        return $item;
    }

    /**
     * @return bool
     */
    private function isDirectory() : bool
    {
        return is_array($this->item) && $this->item['type'] === 'dir';
    }

    /**
     * set current item by directory list and current index
     */
    private function updateItem() : void
    {
        $this->item = isset($this->list[$this->index]) ? $this->list[$this->index] : null;
    }
}

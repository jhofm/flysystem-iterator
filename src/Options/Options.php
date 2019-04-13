<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Options;

use InvalidArgumentException;

/**
 * Class Options
 * Iterator configuration 
 * 
 * @package Jhofm\FlysystemIterator\Options
 */
final class Options
{
    const OPTION_RETURN_KEY = 'key';
    const OPTION_RETURN_VALUE = 'value';
    const OPTION_RECURSIVE = 'recursive';
    const OPTION_FILTER = 'filter';

    const VALUE_PATH_RELATIVE = 'path';
    const VALUE_LIST_INFO = 'info';
    const VALUE_INDEX = 'index';

    /** @var array $defaults */
    private static $defaults = [
        self::OPTION_RETURN_KEY => self::VALUE_INDEX,
        self::OPTION_RETURN_VALUE => self::VALUE_LIST_INFO,
        self::OPTION_RECURSIVE => false,
        self::OPTION_FILTER => null
    ];

    /** @var array $options */
    protected $options;

    /**
     * @param array $options
     * @return Options
     */
    public static function fromArray(array $options) : Options
    {
        return new self(
            array_merge(self::$defaults, $options)
        );
    }

    /**
     * Options constructor.
     * @param array $options
     */
    private function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function __get(string $name)
    {
       if (!array_key_exists($name, $this->options)) {
           throw new InvalidArgumentException(sprintf('Unknown option "%s".', $name));
       }
       return $this->options[$name];
    }

    /**
     * @return array
     */
    public function toArray() : array
    {
        return $this->options;
    }
}

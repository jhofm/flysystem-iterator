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
    const OPTION_RETURN_VALUE = 'value';
    const VALUE_PATH_RELATIVE = 'path';
    const VALUE_LIST_INFO = 'info';

    const OPTION_IS_RECURSIVE = 'recursive';
    const OPTION_FILTER = 'filter';
    const OPTION_LIST_WITH = 'list-with';
    const OPTION_SKIP_ROOT_DIRECTORY = 'skip-root';

    /** @var array $defaults */
    private static $defaults = [
        self::OPTION_RETURN_VALUE => self::VALUE_LIST_INFO,
        self::OPTION_IS_RECURSIVE => true,
        self::OPTION_FILTER => null,
        self::OPTION_LIST_WITH => [],
        self::OPTION_SKIP_ROOT_DIRECTORY => false
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

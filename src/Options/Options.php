<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Options;

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

    const VALUE_PATH_RELATIVE = 'path';
    const VALUE_LIST_INFO = 'info';
    const VALUE_INDEX = 'index';

    /** @var array $defaults */
    private static $defaults = [
        self::OPTION_RETURN_KEY => self::VALUE_INDEX,
        self::OPTION_RETURN_VALUE => self::VALUE_LIST_INFO
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
     * @return string
     */
    public function __get(string $name) : string
    {
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

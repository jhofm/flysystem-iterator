<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Filter;

use Closure;

/**
 * Class FilterFactory
 * @package Jhofm\FlysystemIterator\Filter
 */
class FilterFactory
{
    /**
     * Combines an array of closures in an AND expression
     * @param Closure ...$functions
     * @return Closure
     */
    public static function and(Closure ...$functions) : Closure
    {
        return function (array $item) use ($functions) : bool {
            foreach ($functions as $function) {
                if (!$function($item)) {
                    return false;
                }
            }
            return true;
        };
    }

    /**
     * Combines an array of closures in an OR expression
     * @param Closure  ...$functions
     * @return Closure
     */
    public static function or(Closure ...$functions) : Closure
    {
        return function (array $item) use ($functions) : bool {
            foreach ($functions as $function) {
                if ($function($item) === true) {
                    return true;
                }
            }
            return false;
        };
    }

    /**
     * @param $function
     * @return Closure
     */
    public static function not(Closure $function) : Closure
    {
        return function (array $item) use ($function) : bool {
            return !$function($item);
        };
    }

    /**
     * @return Closure
     */
    public static function isDirectory() : Closure
    {
        return function (array $item) : bool {
            return array_key_exists('type', $item) && $item['type'] === 'dir';
        };
    }

    /**
     * @return Closure
     */
    public static function isFile() : Closure
    {
        return function (array $item) : bool {
            return array_key_exists('type', $item) && $item['type'] === 'file';
        };
    }

    /**
     * @param string $regex
     * @return Closure
     */
    public static function pathMatchesRegex(string $regex) : Closure
    {
        return function (array $item) use ($regex) : bool {
            return array_key_exists('path', $item) && (preg_match($regex, $item['path']) > 0);
        };
    }

    /**
     * @param string $search
     * @param bool $caseSensitive
     * @return Closure
     */
    public static function pathContainsString(string $search, bool $caseSensitive = false) : Closure
    {
        return function (array $item) use ($search, $caseSensitive) : bool {
            return $caseSensitive
                ? array_key_exists('path', $item) && strpos($item['path'], $search) !== false
                : array_key_exists('path', $item) && stripos($item['path'], $search) !== false;
        };
    }
}

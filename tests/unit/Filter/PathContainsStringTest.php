<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Unit\Filter;

use Jhofm\FlysystemIterator\Filter\FilterFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class PathContainsStringTest
 * @package Jhofm\FlysystemIterator\Test\Unit\Filter
 */
class PathContainsStringTest extends TestCase
{
    protected $csm;
    protected $cism;

    public function setup()
    {
        $this->csm = FilterFactory::pathContainsString('a', true);
        $this->cism = FilterFactory::pathContainsString('a', false);
    }

    public function dataProvider() : array
    {
        return [
            [['path' => '/bab'], true, true],
            [['path' => '/bAb'], false, true],
            [['path' => '/bb'], false, false]
        ];
    }

    /**
     * @dataProvider dataProvider
     * @param array $item
     * @param bool $expectedCsm
     * @param bool $expectedCism
     */
    public function testPathContainsString(array $item, bool $expectedCsm, bool $expectedCism)
    {
        $this->assertEquals($expectedCsm, ($this->csm)($item));
        $this->assertEquals($expectedCism, ($this->cism)($item));
    }
}
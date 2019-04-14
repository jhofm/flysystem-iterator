<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Unit\Filter;

use Closure;
use Jhofm\FlysystemIterator\Filter\FilterFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class NotFilterTest
 * @package Jhofm\FlysystemIterator\Test\Unit\Filter
 * @small
 */
class NotFilterTest extends TestCase
{
    /** @var Closure */
    private $subject;

    public function setUp()
    {
        $this->subject = FilterFactory::not(FilterFactory::isFile());
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            [['type' => 'dir'], true],
            [['type' => 'file'], false],
            [['type' => 'foo'], true],
            [['foo' => 'bar'], true]
        ];
    }

    /**
     * @test
     * @dataProvider dataProvider
     * @param array $item
     * @param bool $expectedResult
     */
    public function testFilter(array $item, bool $expectedResult)
    {
        $this->assertEquals($expectedResult, ($this->subject)($item));
    }
}

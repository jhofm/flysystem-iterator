<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Unit\Filter;

use Closure;
use Jhofm\FlysystemIterator\Filter\FilterFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class IsFileFilterTest
 * @package Jhofm\FlysystemIterator\Test\Unit\Filter
 *
 * @group unit
 * @small
 */
class IsFileFilterTest extends TestCase
{
    /** @var Closure */
    private $subject;

    public function setUp()
    {
        $this->subject = FilterFactory::isFile();
    }

    /**
     * @return array
     */
    public function dataProvider() : array
    {
        return [
            [['type' => 'dir'], false],
            [['type' => 'file'], true],
            [['type' => 'foo'], false],
            [['foo' => 'bar'], false]
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

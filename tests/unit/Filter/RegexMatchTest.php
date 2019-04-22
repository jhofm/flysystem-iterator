<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Unit\Filter;

use Jhofm\FlysystemIterator\Filter\FilterFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class RegexMatchTest
 * @package Jhofm\FlysystemIterator\Test\Unit\Filter
 *
 * @group unit
 * @small
 */
class RegexMatchTest extends TestCase
{
    private $subject;

    public function setUp()
    {
        $this->subject = FilterFactory::pathMatchesRegex('/^.*a$/');
    }

    public function dataProvider() : array
    {
        return [
            [['path' => '/bla'], true],
            [['path' => '/foo'], false],
            [['path' => '/foo/ba'], true],
            [['path' => '/'], false]
        ];
    }

    /**
     * @dataProvider dataProvider
     * @param array $item
     * @param bool $expectedResult
     */
    public function testRegexMatchFilter(array $item, bool $expectedResult)
    {
        $this->assertEquals($expectedResult, ($this->subject)($item));
    }
}

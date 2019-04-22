<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Unit\Filter;

use Jhofm\FlysystemIterator\Filter\FilterFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class AndFilterTest
 * @package Jhofm\FlysystemIterator\Test\Unit\Filter
 *
 * @group unit
 * @small
 */
class AndFilterTest extends TestCase
{
    public function testAnd()
    {
        $t = function (array $item) { return true; };
        $f = function (array $item) { return false; };

        $ff = FilterFactory::and($f, $f);
        $this->assertFalse($ff([]));
        $tf = FilterFactory::and($t, $f);
        $this->assertFalse($tf([]));
        $tt = FilterFactory::and($t, $t);
        $this->assertTrue($tt([]));
        $tttt = FilterFactory::and($t, $t, $t, $t);
        $this->assertTrue($tttt([]));
        $ttttf = FilterFactory::and($t, $t, $t, $t, $f);
        $this->assertFalse($ttttf([]));
    }
}

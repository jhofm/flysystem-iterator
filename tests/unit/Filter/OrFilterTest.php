<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Unit\Filter;

use Jhofm\FlysystemIterator\Filter\FilterFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class OrFilterTest
 * @package Jhofm\FlysystemIterator\Test\Unit\Filter
 *
 * @group unit
 * @small
 */
class OrFilterTest extends TestCase
{
    public function testOr()
    {
        $t = function (array $item) { return true; };
        $f = function (array $item) { return false; };

        $ff = FilterFactory::or($f, $f);
        $this->assertFalse($ff([]));
        $tf = FilterFactory::or($t, $f);
        $this->assertTrue($tf([]));
        $tt = FilterFactory::or($t, $t);
        $this->assertTrue($tt([]));
        $ffff = FilterFactory::or($f, $f, $f, $f);
        $this->assertFalse($ffff([]));
        $ttttf = FilterFactory::or($t, $t, $t, $t, $f);
        $this->assertTrue($ttttf([]));
    }
    
}

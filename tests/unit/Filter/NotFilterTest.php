<?php

declare(strict_types=1);

namespace Jhofm\FlysystemIterator\Test\Unit\Filter;

use Jhofm\FlysystemIterator\Filter\FilterFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class NotFilterTest
 * @package Jhofm\FlysystemIterator\Test\Unit\Filter
 *
 * @group unit
 * @small
 */
class NotFilterTest extends TestCase
{
    public function testFilter()
    {
        $this->assertFalse((FilterFactory::not(function(array $item) { return true;})([])));
        $this->assertTrue((FilterFactory::not(function(array $item) { return false;})([])));
    }
}

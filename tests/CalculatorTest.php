<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Services\Calculateor;

class CalculatorTest extends TestCase
{
    public function testSomething()
    {
        $calculator = new Calculateor();
        $result = $calculator->add(1,9);
        $this->assertEquals(10, $result);
    }
}

<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Vectorial1024\AlofLib\Alof;

final class AlofTest extends TestCase
{
    public function testIsAlo()
    {
        // is alo
        // implements array access and traversable
        $this->assertTrue(Alof::is_alo(new WeakMap()));
        $this->assertTrue(Alof::is_alo(new ArrayObject()));
        $this->assertTrue(Alof::is_alo(new ArrayIterator([])));
        $this->assertTrue(Alof::is_alo(new SplDoublyLinkedList()));
        $this->assertTrue(Alof::is_alo(new SplFixedArray(10)));
        $this->assertTrue(Alof::is_alo(new SplStack()));
        $this->assertTrue(Alof::is_alo(new SplQueue()));
        $this->assertTrue(Alof::is_alo(new SplObjectStorage()));

        // is not alo
        // basically, other primitive types and objects
        $this->assertFalse(Alof::is_alo(null));
        $this->assertFalse(Alof::is_alo(false));
        $this->assertFalse(Alof::is_alo(true));
        $this->assertFalse(Alof::is_alo(10));
        $this->assertFalse(Alof::is_alo(12.5));
        $this->assertFalse(Alof::is_alo('hello world'));
        $this->assertFalse(Alof::is_alo([]));
        $this->assertFalse(Alof::is_alo(new stdClass()));
        $handle = fopen(__FILE__, 'r');
        $this->assertFalse(Alof::is_alo($handle));
    }

    public function testAloKeys()
    {
        $testArray = [
            1 => 1,
            '4' => '4',
            'c' => 'c',
        ];
        $testAlo = new ArrayObject($testArray);
        $this->assertEquals([1, '4', 'c'], Alof::alo_keys($testAlo));
        $this->assertEquals(['4'], Alof::alo_keys($testAlo, '4'));
        $this->assertEquals(['4'], Alof::alo_keys($testAlo, 4));
        $this->assertEquals(['4'], Alof::alo_keys($testAlo, '4', true));
        $this->assertEquals([], Alof::alo_keys($testAlo, 4, true));
    }

    public function testAloValues()
    {
        $testArray = [
            1 => 1,
            '4' => '4',
            'c' => 'c',
        ];
        $testAlo = new ArrayObject($testArray);
        $this->assertEquals(array_values($testArray), Alof::alo_values($testAlo));
    }
}

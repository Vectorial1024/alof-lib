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

    public function testAloWalk()
    {
        // perform summation over all elements
        $testArray = [1, 2, 3, 4, 5];
        $testAlo = new ArrayObject($testArray);
        $sum = 0;
        $sumFunc = function (int $item) use (&$sum) {
            $sum += $item;
        };
        Alof::alo_walk($testAlo, $sumFunc);
        $this->assertEquals(15, $sum);

        // perform extraction of all elements
        $output = [];
        $appendFunc = function (int $item) use (&$output) {
            $output[] = $item;
        };
        Alof::alo_walk($testAlo, $appendFunc);
        $this->assertEquals($testArray, $output);

        // perform key-aware extraction over all elements
        $testArray2 = [
            3 => 1,
            4 => 2,
        ];
        $testAlo2 = new ArrayObject($testArray2);
        $output = [];
        $appendFunc = function (int $item, int $key) use (&$output) {
            $output[$key] = $item;
        };
        Alof::alo_walk($testAlo2, $appendFunc);
        $this->assertEquals($testArray2, $output);

        // perform key-aware extraction over all elements, with multiplication
        $testArray3 = [
            3 => 1,
            4 => 2,
        ];
        $factor = 3;
        $testAlo3 = new ArrayObject($testArray3);
        $output = [];
        Alof::alo_walk($testAlo3, function (int $item, int $key, int $factor) use (&$output) {
            $output[$key] = $item * $key * $factor;
        }, [$factor]);
        foreach ($output as $key => $value) {
            $this->assertEquals($value, $key * $testArray3[$key] * $factor);
        }
    }
}

<?php

declare(strict_types=1);

namespace Vectorial1024\AlofLib\Test;

use ArrayIterator;
use ArrayObject;
use PHPUnit\Framework\Attributes\DataProvider;
use stdClass;
use SplDoublyLinkedList;
use SplFixedArray;
use SplObjectStorage;
use SplQueue;
use SplStack;
use WeakMap;
use PHPUnit\Framework\TestCase;
use Vectorial1024\AlofLib\Alof;

final class AlofTest extends TestCase
{
    // list of ALO<TKey> where TKey instanceof object
    private array $aloWithObjAsKeyType;
    private array $sosKeys;
    private array $sosValues;

    public function setUp(): void
    {
        // for convenience to test with SplObjectStorage (and other known object-key ALOs), we are defining some convenient values

        /*
         * why we need to write code for SplObjectStorage separately?
         * refer to the following:
         * https://www.php.net/manual/en/class.splobjectstorage.php
         * https://bugs.php.net/bug.php?id=49967
         * TL;DR: SplObjectStorage does not provide the object keys correctly due to a legacy bug
         */

        $dummyObj1 = new stdClass();
        $dummyObj1->id = 1;
        $dummyObj2 = new stdClass();
        $dummyObj2->id = 2;
        $dummyObj3 = new stdClass();
        $dummyObj3->id = 3;

        $splObjectStore = new SplObjectStorage();
        $splObjectStore[$dummyObj1] = 1;
        $splObjectStore[$dummyObj2] = 2;
        $splObjectStore[$dummyObj3] = 3;
        $this->sosKeys = [$dummyObj1, $dummyObj2, $dummyObj3];
        $this->sosValues = [1, 2, 3];

        // we also set it up for WeakMap
        $weakMap = new WeakMap();
        $weakMap[$dummyObj1] = 1;
        $weakMap[$dummyObj2] = 2;
        $weakMap[$dummyObj3] = 3;

        $this->aloWithObjAsKeyType = [$splObjectStore, $weakMap];
    }

    #[DataProvider('aloCaseProvider')]
    public function testIsAlo(mixed $value, bool $truth)
    {
        $this->assertEquals($truth, Alof::is_alo($value));
    }

    public function testAloKeys()
    {
        $testArray = [
            0 => 3,
            '4' => 'c',
            'c' => 'g',
        ];
        $testAlo = new ArrayObject($testArray);
        $this->assertEquals(array_keys($testArray), Alof::alo_keys($testAlo));
        $this->assertEquals([0], Alof::alo_keys($testAlo, 0));
        $this->assertEquals([0], Alof::alo_keys($testAlo, '0'));
        $this->assertEquals([0], Alof::alo_keys($testAlo, 0, true));
        $this->assertEquals([], Alof::alo_keys($testAlo, '0', true));

        foreach ($this->aloWithObjAsKeyType as $alo) {
            $this->assertEquals($this->sosKeys, Alof::alo_keys($alo));
            $this->assertEquals($this->sosKeys, Alof::alo_keys($alo));
            $this->assertEquals([$this->sosKeys[0]], Alof::alo_keys($alo, $this->sosKeys[0]));
            $fakeDummy = clone $this->sosKeys[0];
            $this->assertEquals([$this->sosKeys[0]], Alof::alo_keys($alo, $fakeDummy));
            $this->assertEquals([$this->sosKeys[0]], Alof::alo_keys($alo, $this->sosKeys[0], true));
            $this->assertEquals([], Alof::alo_keys($alo, $fakeDummy, true));
        }
    }

    public function testAloValues()
    {
        $testArray = [
            0 => 3,
            '4' => 'c',
            'c' => 'g',
        ];
        $testAlo = new ArrayObject($testArray);
        $this->assertEquals(array_values($testArray), Alof::alo_values($testAlo));

        foreach ($this->aloWithObjAsKeyType as $alo) {
            $this->assertEquals($this->sosValues, Alof::alo_values($alo));
        }
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

        // perform key-aware extraction over all elements, via multiplication, with multiple arguments
        $testArray3 = [
            3 => 1,
            4 => 2,
        ];
        $factor = 3;
        $factor2 = 5;
        $testAlo3 = new ArrayObject($testArray3);
        $output = [];
        Alof::alo_walk($testAlo3, function (int $item, int $key, int $factor, int $factor2) use (&$output) {
            $output[$key] = $item * $key * $factor * $factor2;
        }, [$factor, $factor2]);
        foreach ($output as $key => $value) {
            $this->assertEquals($value, $key * $testArray3[$key] * $factor * $factor2);
        }

        // test against object keys by walk-sum
        foreach ($this->aloWithObjAsKeyType as $alo) {
            $sum = 0;
            Alof::alo_walk($alo, function ($item) use (&$sum) {
                $sum += $item;
            });
            $this->assertEquals(array_sum($this->sosValues), $sum);
        }
    }

    public static function aloCaseProvider()
    {
        // [test_value, is_alo]
        $cases = [];
        // is alo
        // implements array access and traversable
        $cases[] = [new WeakMap(), true];
        $cases[] = [new ArrayObject(), true];
        $cases[] = [new ArrayIterator([]), true];
        $cases[] = [new SplDoublyLinkedList(), true];
        $cases[] = [new SplFixedArray(10), true];
        $cases[] = [new SplStack(), true];
        $cases[] = [new SplQueue(), true];
        $cases[] = [new SplObjectStorage(), true];

        // is not alo
        // basically, other primitive types and objects
        $cases[] = [null, false];
        $cases[] = [false, false];
        $cases[] = [true, false];
        $cases[] = [10, false];
        $cases[] = [12.5, false];
        $cases[] = ['hello world', false];
        $cases[] = [[], false];
        $cases[] = [new stdClass(), false];
        $handle = fopen(__FILE__, 'r');
        $cases[] = [$handle, false];
        
        return $cases;
    }
}

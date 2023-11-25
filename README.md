# alof-lib
[![stable](http://poser.pugx.org/vectorial1024/alof-lib/v)](https://packagist.org/packages/vectorial1024/alof-lib)
![phpunit](https://github.com/vectorial1024/alof-lib/actions/workflows/php.yml/badge.svg)
[![downloads](http://poser.pugx.org/vectorial1024/alof-lib/downloads)](https://packagist.org/packages/vectorial1024/alof-lib)
[![license](http://poser.pugx.org/vectorial1024/alof-lib/license)](https://packagist.org/packages/vectorial1024/alof-lib)
[![php](http://poser.pugx.org/vectorial1024/alof-lib/require/php)](https://packagist.org/packages/vectorial1024/alof-lib)

(This is a WIP project!)

PHP array-like object functions library ("alof-lib"). Type-hinted functions for array-like objects, just like [those for native arrays](https://www.php.net/manual/en/ref.array.php). Think of this library as a polyfill of those useful functions for array-like objects, so that you may write clean code with array-like objects.

An array-like object ("ALO") is defined to be `implements ArrayAccess` and `implements Traversable`. Examples of array-like objects include:
- [ArrayObject](https://www.php.net/manual/en/class.arrayobject.php) (since PHP 5)
- [ArrayIterator](https://www.php.net/manual/en/class.arrayiterator.php) (since PHP 5)
- [SplObjectStorage](https://www.php.net/manual/en/class.splobjectstorage.php) (since PHP 5.1)
- [SplDoublyLinkedList](https://www.php.net/manual/en/class.spldoublylinkedlist.php) (since PHP 5.3)
- [SplFixedArray](https://www.php.net/manual/en/class.splfixedarray.php) (since PHP 5.3)
- [SplStack](https://www.php.net/manual/en/class.splstack.php) (since PHP 5.3)
- [SplQueue](https://www.php.net/manual/en/class.splqueue.php) (since PHP 5.3)
- [WeakMap](https://www.php.net/manual/en/class.weakmap.php) (since PHP 8)
- ... and perhaps more

A PHP `array` is NOT an ALO. It is still an array.

Latest version requires PHP 8.1+.

See the change log in the `CHANGELOG.md` file.

## Notes and Disclaimers
- ALO functions aim to be faithful user-land reproductions of their array function counterparts, but there might be slight differences between both sides
- Some ALO functions may not make sense depending on your exact ALO implementation; use judgement before you use the ALO functions

## Testing
This library uses PHPUnit for testing, which can be triggered from Composer. To test this library, run:

```shell
composer run-script test
```

## Example Usage
Refer to the test cases under `/tests` for more examples, but for a minimal example:
```php
use Vectorial1024\AlofLib\Alof;

$objKey = new stdClass();
$objKey->name = "foo";

// conveniently get the keys of the WeakMap (WeakMap becomes a "WeakHashSet" for objects)
$map = new WeakMap();
$map[$objKey] = "1";
$map[$objKey] = 2;
$map[$objKey] = "Hello World!";
$keys = Alof::alo_keys($map);
assert($keys === [$objKey]); // passes

// correctly get the keys of the SplObjectStorage (no more nasty foreach surprises!)
$splObjectStore = new SplObjectStorage();
$splObjectStore[$objKey] = "Hello World!";
$keys = Alof::alo_keys($splObjectStore);
assert($keys === [$objKey]); // passes
```

# alof-lib
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

Requires PHP 8.1.

Note: because this is a userland polyfill of the array functions, the exact behavior of ALO functions may be different from their array function counterparts.

Disclaimer: because of the many possibilities of array-like objects, perhaps some functions do not make sense for specific object types. Users should check that the operations make sense before using the ALO functions.

## Testing
This library uses PHPUnit for testing. To test this library, run:

```
./vendor/bin/phpunit tests
```

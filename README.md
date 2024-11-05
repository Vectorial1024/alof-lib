# alof-lib
[![Packagist License][packagist-license-image]][packagist-url]
[![Packagist Version][packagist-version-image]][packagist-url]
[![Packagist Downloads][packagist-downloads-image]][packagist-stats-url]
[![PHP Dependency Version][php-version-image]][packagist-url]
[![GitHub Actions Workflow Status][php-build-status-image]][github-actions-url]
[![GitHub Repo Stars][github-stars-image]][github-repo-url]
[![readthedocs](https://readthedocs.org/projects/alof-lib/badge/?version=latest)](https://alof-lib.readthedocs.io/en/latest/?badge=latest)

(This is a WIP project!)

PHP array-like object functions library ("alof-lib"). Type-hinted functions for array-like objects, just like [those for native arrays](https://www.php.net/manual/en/ref.array.php). Think of this library as a polyfill of those useful functions for array-like objects, so that you may write clean code with array-like objects.

An array-like object ("ALO") is defined to be `implements ArrayAccess` and `implements Traversable`. Examples include:
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

See also `vectorial1024/transmutation` for a collection-like library for ALOs.

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

## Relationship with `transmutation`
This library does the dirty work and ensures the correctness of ALO functions, while `transmutation` provides an easy-to-use API for ALO processing.

[packagist-url]: https://packagist.org/packages/vectorial1024/alof-lib
[packagist-stats-url]: https://packagist.org/packages/vectorial1024/alof-lib/stats
[github-repo-url]: https://github.com/Vectorial1024/alof-lib
[github-actions-url]: https://github.com/Vectorial1024/alof-lib/actions/workflows/php.yml

[packagist-license-image]: https://img.shields.io/packagist/l/vectorial1024/alof-lib?style=plastic
[packagist-version-image]: https://img.shields.io/packagist/v/vectorial1024/alof-lib?style=plastic
[packagist-downloads-image]: https://img.shields.io/packagist/dm/vectorial1024/alof-lib?style=plastic
[php-version-image]: https://img.shields.io/packagist/dependency-v/vectorial1024/alof-lib/php?style=plastic&label=PHP
[php-build-status-image]: https://img.shields.io/github/actions/workflow/status/Vectorial1024/alof-lib/php.yml?style=plastic
[github-stars-image]: https://img.shields.io/github/stars/vectorial1024/alof-lib

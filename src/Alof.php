<?php

declare(strict_types=1);

namespace Vectorial1024\AlofLib;

use ArrayAccess;
use SplObjectStorage;
use Traversable;

/**
 * The class containing various array-like object functions.
 */
class Alof
{
    /**
     * Returns whether the given value is an array-like object:
     *
     * A value is an array-like object if it implements both `ArrayAccess` and `Traversable`.
     * @param mixed $value the value to test
     * @return bool
     * @see ArrayAccess
     * @see Traversable
     */
    public static function is_alo(mixed $value): bool
    {
        return ($value instanceof ArrayAccess) && ($value instanceof Traversable);
    }

    /**
     * Returns the keys of the given array-like object, with optional filtering.
     * @template TKey
     * @param Traversable<TKey, mixed>&ArrayAccess<TKey, mixed> $alo the array-like object
     * @param mixed $filter_value (optional) the value to filter by
     * @param bool $strict (optional) whether to use strict comparison (===) while filtering
     * @return array<TKey>
     * @see array_keys() for equivalent behavior in arrays
     */
    public static function alo_keys(Traversable&ArrayAccess $alo, mixed $filter_value = null, bool $strict = false): array
    {
        if ($alo instanceof SplObjectStorage) {
            // special handling of SplObjectStorage
            return self::alo_keys_splObjectStore($alo, $filter_value, $strict);
        }
        // use separate loops for best performance
        $result = [];
        if (!isset($filter_value)) {
            // keys only
            foreach ($alo as $key => $value) {
                $result[] = $key;
            }
            return $result;
        }
        if (!$strict) {
            // non-strict filtering
            foreach ($alo as $key => $value) {
                if ($key != $filter_value) {
                    continue;
                }
                $result[] = $key;
            }
            return $result;
        }
        // strict filtering
        foreach ($alo as $key => $value) {
            if ($key !== $filter_value) {
                continue;
            }
            $result[] = $key;
        }
        return $result;
    }

    /**
     * alo_keys, but for SplObjectStorage to account for its legacy bug.
     * @template TKey
     * @param SplObjectStorage<TKey, mixed> $storage
     * @param mixed $filter_value
     * @param bool $strict
     * @return array
     * @see self::alo_keys()
     */
    private static function alo_keys_splObjectStore(SplObjectStorage $storage, mixed $filter_value = null, bool $strict = false): array
    {
        // use separate loops for best performance
        $result = [];
        if (!isset($filter_value)) {
            // keys only
            return iterator_to_array($storage, preserve_keys: false);
        }
        if (!$strict) {
            // non-strict filtering
            foreach ($storage as $theKey) {
                if ($theKey != $filter_value) {
                    continue;
                }
                $result[] = $theKey;
            }
            return $result;
        }
        // strict filtering
        // because this is SplObjectStorage, either the key exists, or it does not
        return isset($storage[$filter_value]) ? [$filter_value] : [];
    }

    /**
     * Returns the values of the given array-like object; the resulting array is numerically indexed.
     * @template TValue
     * @param Traversable<mixed, TValue>&ArrayAccess<mixed, TValue> $alo
     * @return list<TValue>
     * @see array_values() for equivalent behavior in arrays
     */
    public static function alo_values(Traversable&ArrayAccess $alo): array
    {
        if ($alo instanceof SplObjectStorage) {
            // special handling for SplObjectStorage
            return self::alo_values_splObjectStore($alo);
        }
        return iterator_to_array($alo, preserve_keys: false);
    }

    /**
     * alo_values, but for SplObjectStorage to account for its legacy bug.
     * @param SplObjectStorage $storage
     * @return array
     * @see self::alo_values()
     */
    private static function alo_values_splObjectStore(SplObjectStorage $storage): array
    {
        $result = [];
        foreach ($storage as $objKey) {
            $result[] = $storage[$objKey];
        }
        return $result;
    }

    /**
     * Applies a callback to each element of the given array-like object.
     * @param Traversable<mixed, mixed>&ArrayAccess<mixed, mixed> $alo the array-like object
     * @param callable $callback the callback to apply
     * @param array $args (optional) the arguments to be passed to the callback
     * @return true
     * @see array_walk() for equivalent behavior in arrays
     */
    public static function alo_walk(Traversable&ArrayAccess $alo, callable $callback, array $args = []): bool
    {
        if ($alo instanceof SplObjectStorage) {
            return self::alo_walk_splObjectStore($alo, $callback, $args);
        }
        foreach ($alo as $key => $value) {
            $callback($value, $key, ...$args);
        }
        return true;
    }

    /**
     * alo_walk, but for SplObjectStorage to account for its legacy bug.
     * @param SplObjectStorage<mixed, mixed> $objectStorage
     * @param callable $callback
     * @param array $args
     * @return true
     * @see self::alo_walk()
     */
    private static function alo_walk_splObjectStore(SplObjectStorage $objectStorage, callable $callback, array $args = []): bool
    {
        foreach ($objectStorage as $objKey) {
            $callback($objectStorage[$objKey], $objKey, ...$args);
        }
        return true;
    }
}

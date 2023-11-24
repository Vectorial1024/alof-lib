<?php

declare(strict_types=1);

namespace Vectorial1024\AlofLib;

use ArrayAccess;
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
     * @see array_keys for equivalent behavior in arrays
     */
    public static function alo_keys(ArrayAccess&Traversable $alo, mixed $filter_value = null, bool $strict = false): array
    {
        // use separate loops for best performance
        $result = [];
        if (!$filter_value) {
            // keys only
            foreach ($alo as $key => $value) {
                $result[] = $key;
            }
            return $result;
        }
        if (!$strict) {
            // non-strict filtering
            foreach ($alo as $key => $value) {
                if ($value != $filter_value) {
                    continue;
                }
                $result[] = $key;
            }
            return $result;
        }
        // strict filtering
        foreach ($alo as $key => $value) {
            if ($value !== $filter_value) {
                continue;
            }
            $result[] = $key;
        }
        return $result;
    }
}

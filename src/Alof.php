<?php

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
}

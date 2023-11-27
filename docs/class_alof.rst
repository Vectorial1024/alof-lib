Classes
=======

.. _alof:

Alof
------------

Alof is the class that contains the functions of AlofLib.

Its full qualification is Vectorial1024\AlofLib\Alof.

For now, please refer to the PHP source code for the documentation. We are finding a way to automatically generate compatible documentations from PHP sources.

.. php:class:: AlofLib
   :nocontentsentry:

   The class that contains the array-like object functions.

   .. php:method:: is_alo($value)
      :nocontentsentry:

      Returns whether the given value is an ALO: - implements Traversable - implements ArrayAccess

      :param mixed $value: The value for testing.
      :returns: True if the object is an alo, and therefore processable by this library.

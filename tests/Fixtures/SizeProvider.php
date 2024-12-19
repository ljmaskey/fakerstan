<?php

declare(strict_types=1);

namespace CalebDW\Fakerstan\Tests\Fixtures;

/** This is to test providers that do not extend the Base class. */
class SizeProvider
{
    public static function size(): string
    {
        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];

        return $sizes[array_rand($sizes)];
    }
}

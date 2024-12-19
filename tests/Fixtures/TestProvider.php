<?php

declare(strict_types=1);

namespace CalebDW\Fakerstan\Tests\Fixtures;

use Faker\Provider\Base;

class TestProvider extends Base
{
    public static function car(): string
    {
        $cars = ['Toyota', 'Ford', 'Chevrolet', 'Honda', 'Nissan', 'Dodge', 'Jeep', 'Hyundai'];

        return $cars[array_rand($cars)];
    }

    /**
     * @template T
     *
     * @param  T  $value
     * @return T
     */
    public static function passthrough(mixed $value): mixed
    {
        return $value;
    }

    // Should not be detected
    protected static function error(): string
    {
        return 'foo';
    }
}

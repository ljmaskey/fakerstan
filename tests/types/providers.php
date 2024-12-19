<?php

declare(strict_types=1);

namespace CalebDW\Fakerstan\Tests\types;

use Faker\Generator;

use function PHPStan\Testing\assertType;

/** @param array{bar: string} $foo */
function test(Generator $faker, array $foo): void
{
    assertType('string', $faker->car());
    assertType('string', $faker->size());
    assertType('*ERROR*', $faker->error());

    /** @todo This should be detected as a possible null */
    assertType('string', $faker->optional()->car());
    assertType('string', $faker->optional()->size());
    assertType('*ERROR*', $faker->optional()->error());

    assertType('string', $faker->unique()->car());
    assertType('string', $faker->unique()->size());
    assertType('*ERROR*', $faker->unique()->error());

    assertType('string', $faker->valid()->car());
    assertType('string', $faker->valid()->size());
    assertType('*ERROR*', $faker->valid()->error());

    // Test overloading and generics
    assertType('array{bar: string}', $faker->passthrough($foo));
    /** @todo This should be detected as a possible null */
    assertType('array{bar: string}', $faker->optional()->passthrough($foo));
    assertType('array{bar: string}', $faker->unique()->passthrough($foo));
    assertType('array{bar: string}', $faker->valid()->passthrough($foo));
}

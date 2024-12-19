<?php

declare(strict_types=1);

namespace CalebDW\Fakerstan\Tests\types;

use Faker\Generator;

use function PHPStan\Testing\assertType;

function test(Generator $faker): void
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
    assertType("'test'", $faker->passthrough('test'));
    assertType('42', $faker->passthrough(42));
    assertType('object{foo: 42}&stdClass', $faker->passthrough((object) ['foo' => 42]));

    /** @todo This should be detected as a possible null */
    assertType("'test'", $faker->optional()->passthrough('test'));
    assertType('42', $faker->optional()->passthrough(42));
    assertType('object{foo: 42}&stdClass', $faker->optional()->passthrough((object) ['foo' => 42]));

    assertType("'test'", $faker->unique()->passthrough('test'));
    assertType('42', $faker->unique()->passthrough(42));
    assertType('object{foo: 42}&stdClass', $faker->unique()->passthrough((object) ['foo' => 42]));

    assertType("'test'", $faker->valid()->passthrough('test'));
    assertType('42', $faker->valid()->passthrough(42));
    assertType('object{foo: 42}&stdClass', $faker->valid()->passthrough((object) ['foo' => 42]));
}

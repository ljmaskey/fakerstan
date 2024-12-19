<?php

declare(strict_types=1);

namespace CalebDW\Fakerstan\Tests\Fixtures;

use CalebDW\Fakerstan\FakerProvider;
use Faker\Generator;

class TestFakerProvider implements FakerProvider
{
    public function getFaker(): Generator
    {
        $faker = new Generator();
        $faker->addProvider(new TestProvider($faker));
        $faker->addProvider(new SizeProvider());

        return $faker;
    }
}

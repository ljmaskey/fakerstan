<?php

declare(strict_types=1);

namespace CalebDW\Fakerstan;

use Faker\Factory;
use Faker\Generator;

final class DefaultFakerProvider implements FakerProvider
{
    public function getFaker(): Generator
    {
        if (function_exists('fake')) {
            $fake = fake();

            if ($fake instanceof Generator) {
                return $fake;
            }
        }

        return Factory::create();
    }
}

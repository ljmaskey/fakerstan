<?php

declare(strict_types=1);

namespace CalebDW\Fakerstan;

use Faker\Generator;

/** @api */
interface FakerProvider
{
    /** Get the configured Faker instance. */
    public function getFaker(): Generator;
}

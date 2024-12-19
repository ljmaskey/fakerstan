<?php

declare(strict_types=1);

namespace CalebDW\Fakerstan\Tests\Fixtures;

use CalebDW\Fakerstan\FakerProvider;

class TestFakerProviderFactory
{
    public static function create(): FakerProvider
    {
        return new TestFakerProvider();
    }
}

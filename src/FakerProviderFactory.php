<?php

declare(strict_types=1);

namespace CalebDW\Fakerstan;

class FakerProviderFactory
{
    public static function create(): FakerProvider
    {
        return new DefaultFakerProvider();
    }
}

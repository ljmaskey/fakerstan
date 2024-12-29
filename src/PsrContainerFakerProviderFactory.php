<?php

declare(strict_types=1);

namespace CalebDW\Fakerstan;

use Faker\Generator;

class PsrContainerFakerProviderFactory
{
    private static string $phpContainerPath;
    private static ?string $setsVariable;
    private static string $containerFakerId;

    public function __construct(
        string $phpContainerPath,
        ?string $setsVariable = null,
        string $containerFakerId = Generator::class,
    ) {
        self::$phpContainerPath = $phpContainerPath;
        self::$setsVariable = $setsVariable;
        self::$containerFakerId = $containerFakerId;
    }

    public static function create(): FakerProvider
    {
        return new PsrContainerFakerProvider(
            self::$phpContainerPath,
            self::$setsVariable,
            self::$containerFakerId,
        );
    }
}

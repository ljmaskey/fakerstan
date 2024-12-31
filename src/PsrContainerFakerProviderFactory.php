<?php

declare(strict_types=1);

namespace CalebDW\Fakerstan;

use Faker\Generator;

class PsrContainerFakerProviderFactory
{
    public function __construct(
        private string $phpContainerPath,
        private ?string $setsVariable = null,
        private string $containerFakerId = Generator::class,
    ) {
    }

    public function create(): FakerProvider
    {
        return new PsrContainerFakerProvider(
            $this->phpContainerPath,
            $this->setsVariable,
            $this->containerFakerId,
        );
    }
}

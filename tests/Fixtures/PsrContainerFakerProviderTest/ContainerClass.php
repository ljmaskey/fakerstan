<?php

declare(strict_types=1);

namespace CalebDW\Fakerstan\Tests\Fixtures\PsrContainerFakerProviderTest;

class ContainerClass implements \Psr\Container\ContainerInterface
{
    public function get(string $id): mixed
    {
        return match ($id) {
            'generatorId' => new \Faker\Generator(),
            'notGeneratorId' => new \stdClass(),
            default => throw new class() extends \Exception implements \Psr\Container\NotFoundExceptionInterface
            {
            },
        };
    }

    public function has(string $id): bool
    {
        return match ($id) {
            'generatorId', 'notGeneratorId' => true,
            default => false,
        };
    }
}

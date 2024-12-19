<?php

declare(strict_types=1);

namespace CalebDW\Fakerstan\Tests;

use PHPStan\Testing\TypeInferenceTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class TypeTest extends TypeInferenceTestCase
{
    /** @return iterable<mixed> */
    public static function data(): iterable
    {
        yield from self::gatherAssertTypes(__DIR__.'/types/providers.php');
    }

    #[DataProvider('data')]
    #[Test]
    public function fileAsserts(string $assertType, string $file, mixed ...$args): void
    {
        $this->assertFileAsserts($assertType, $file, ...$args);
    }

    /** @return string[] */
    public static function getAdditionalConfigFiles(): array
    {
        return [__DIR__.'/phpstan-tests.neon'];
    }
}

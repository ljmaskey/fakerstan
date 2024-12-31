<?php

declare(strict_types=1);

namespace CalebDW\Fakerstan\Tests;

use CalebDW\Fakerstan\PsrContainerFakerProvider;
use Faker\Generator;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(PsrContainerFakerProvider::class)]
class PsrContainerFakerProviderTest extends TestCase
{
    private static ?string $temporaryContainerPath = null;

    #[After]
    public static function cleanUpTemporaryContainer(): void
    {
        if (is_string(static::$temporaryContainerPath)) {
            if (file_exists(static::$temporaryContainerPath)) {
                unlink(static::$temporaryContainerPath);
            }
        }

        static::$temporaryContainerPath = null;
    }

    /*
     * Every time we want to include a file for the container, we need to make sure
     * that it is a new file: otherwise, once one test has included it, the rest
     * will act differently when they go to do it.
     */
    private function containerFilePath(string $containerFixtureFileName): string
    {
        $sourceContainerFileName = __DIR__.'/Fixtures/PsrContainerFakerProviderTest/';
        $sourceContainerFileName .= $containerFixtureFileName;

        static::$temporaryContainerPath = tempnam(sys_get_temp_dir(), 'fakerstan-tests');

        // Make a copy of the source filename in the new (temporary) file, whose name
        // has hopefully not been included already.
        copy($sourceContainerFileName, static::$temporaryContainerPath);

        return static::$temporaryContainerPath;
    }

    #[Test]
    public function getFakerUsesContainerReturnedFromFile()
    {
        $containerFilename = $this->containerFilePath('ReturningContainerFile.php');
        $sut = new PsrContainerFakerProvider($containerFilename, null, 'generatorId');
        $faker = $sut->getFaker();

        $this->assertInstanceOf(Generator::class, $faker);
    }

    #[Test]
    public function getFakerUsesContainerSetInVariable()
    {
        $containerFilename = $this->containerFilePath('VariableSettingContainerFile.php');
        $sut = new PsrContainerFakerProvider($containerFilename, 'containerVariable', 'generatorId');
        $faker = $sut->getFaker();

        $this->assertInstanceOf(Generator::class, $faker);
    }

    #[Test]
    public function getFakerThrowsExceptionWhenUnableToReadContainerFile()
    {
        $this->expectException(RuntimeException::class);

        $containerFilename = __DIR__.'/a-file-that-does-not-exist';
        $sut = new PsrContainerFakerProvider($containerFilename, null, 'generatorId');
        $sut->getFaker();
    }

    #[Test]
    public function getFakerThrowsExceptionWhenTheContainerFileIsExpectedToReturnContainerButDoesNot()
    {
        $this->expectException(RuntimeException::class);

        $containerFilename = $this->containerFilePath('EmptyContainerFile.php');
        $sut = new PsrContainerFakerProvider($containerFilename, null, 'generatorId');
        $sut->getFaker();
    }

    #[Test]
    public function getFakerThrowsExceptionWhenTheContainerFileIsExpectedToSetAVariableButDoesNot()
    {
        $this->expectException(RuntimeException::class);

        $containerFilename = $this->containerFilePath('EmptyContainerFile.php');
        $sut = new PsrContainerFakerProvider($containerFilename, 'containerVariable', 'generatorId');
        $sut->getFaker();
    }

    #[Test]
    public function getFakerThrowsExceptionWhenTheDeterminedContainerIsNotActuallyAContainer()
    {
        $this->expectException(RuntimeException::class);

        $containerFilename = $this->containerFilePath('VariableSettingContainerFile.php');
        $sut = new PsrContainerFakerProvider($containerFilename, 'nonContainerVariable', 'generatorId');
        $sut->getFaker();
    }

    #[Test]
    public function getFakerThrowsExceptionWhenTheContainerDoesNotHaveServiceWithId()
    {
        $this->expectException(RuntimeException::class);

        $containerFilename = $this->containerFilePath('ReturningContainerFile.php');
        $sut = new PsrContainerFakerProvider($containerFilename, null, 'id-not-in-container');
        $sut->getFaker();
    }

    #[Test]
    public function getFakerThrowsExceptionWhenTheNamedServiceIsNotAGenerator()
    {
        $this->expectException(RuntimeException::class);

        $containerFilename = $this->containerFilePath('ReturningContainerFile.php');
        $sut = new PsrContainerFakerProvider($containerFilename, null, 'notGeneratorId');
        $sut->getFaker();
    }
}

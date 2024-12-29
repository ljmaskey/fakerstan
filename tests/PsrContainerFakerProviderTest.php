<?php

declare(strict_types=1);

namespace CalebDW\Fakerstan\Tests;

use CalebDW\Fakerstan\PsrContainerFakerProvider;
use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

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

        $targetContainerFileName = tempnam(sys_get_temp_dir(), 'fakerstan-tests');

        // Make a copy of the source filename in the new (temporary) file, whose name
        // has hopefully not been included already.
        copy($sourceContainerFileName, $targetContainerFileName);

        return $targetContainerFileName;
    }

    #[Test]
    public function getFakerUsesContainerReturnedFromFile()
    {
        $containerFilename = $this->containerFilePath('ReturningContainerFile.php');
        $sut = new PsrContainerFakerProvider($containerFilename, true, null, 'generatorId');
        $faker = $sut->getFaker();

        self::assertInstanceOf(\Faker\Generator::class, $faker);
    }

    #[Test]
    public function getFakerUsesContainerSetInVariable()
    {
        $containerFilename = $this->containerFilePath('VariableSettingContainerFile.php');
        $sut = new PsrContainerFakerProvider($containerFilename, false, 'containerVariable', 'generatorId');
        $faker = $sut->getFaker();

        self::assertInstanceOf(\Faker\Generator::class, $faker);
    }

    #[Test]
    public function cannotSayThatTheFileReturnsTheContainerAndSetsItIntoAVariable()
    {
        self::expectException(\InvalidArgumentException::class);

        new PsrContainerFakerProvider('', true, 'containerVariable', 'generatorId');
    }

    #[Test]
    public function mustSayThatTheFileReturnsTheContainerOrSetsItIntoAVariable()
    {
        self::expectException(\InvalidArgumentException::class);

        new PsrContainerFakerProvider('', false, null, 'generatorId');
    }

    #[Test]
    public function getFakerThrowsExceptionWhenUnableToReadContainerFile()
    {
        self::expectException(\RuntimeException::class);

        $containerFilename = __DIR__.'/a-file-that-does-not-exist';
        $sut = new PsrContainerFakerProvider($containerFilename, true, null, 'generatorId');
        $sut->getFaker();
    }

    #[Test]
    public function getFakerThrowsExceptionWhenTheContainerFileIsExpectedToReturnContainerButDoesNot()
    {
        self::expectException(\RuntimeException::class);

        $containerFilename = $this->containerFilePath('EmptyContainerFile.php');
        $sut = new PsrContainerFakerProvider($containerFilename, true, null, 'generatorId');
        $sut->getFaker();
    }

    #[Test]
    public function getFakerThrowsExceptionWhenTheContainerFileIsExpectedToSetAVariableButDoesNot()
    {
        self::expectException(\RuntimeException::class);

        $containerFilename = $this->containerFilePath('EmptyContainerFile.php');
        $sut = new PsrContainerFakerProvider($containerFilename, false, 'containerVariable', 'generatorId');
        $sut->getFaker();
    }

    #[Test]
    public function getFakerThrowsExceptionWhenTheDeterminedContainerIsNotActuallyAContainer()
    {
        self::expectException(\RuntimeException::class);

        $containerFilename = $this->containerFilePath('VariableSettingContainerFile.php');
        $sut = new PsrContainerFakerProvider($containerFilename, false, 'nonContainerVariable', 'generatorId');
        $sut->getFaker();
    }

    #[Test]
    public function getFakerThrowsExceptionWhenTheContainerDoesNotHaveServiceWithId()
    {
        self::expectException(\RuntimeException::class);

        $containerFilename = $this->containerFilePath('ReturningContainerFile.php');
        $sut = new PsrContainerFakerProvider($containerFilename, true, null, 'id-not-in-container');
        $sut->getFaker();
    }

    #[Test]
    public function getFakerThrowsExceptionWhenTheNamedServiceIsNotAGenerator()
    {
        self::expectException(\RuntimeException::class);

        $containerFilename = $this->containerFilePath('ReturningContainerFile.php');
        $sut = new PsrContainerFakerProvider($containerFilename, true, null, 'notGeneratorId');
        $sut->getFaker();
    }
}

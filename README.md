<div align="center">
  <p>
    <img src="/art/fakerstan.webp" alt="FakerStan" width="40%">
  </p>
  <p>A <a href="https://phpstan.org">PHPStan</a> extension for <a href="https://fakerphp.org/">Faker</a>.</p>
  <p>
    <a href="https://github.com/calebdw/fakerstan/actions/workflows/tests.yml"><img src="https://github.com/calebdw/fakerstan/actions/workflows/tests.yml/badge.svg" alt="Test Results"></a>
    <a href="https://github.com/calebdw/fakerstan"><img src="https://img.shields.io/github/license/calebdw/fakerstan" alt="License"></a>
    <a href="https://packagist.org/packages/calebdw/fakerstan"><img src="https://img.shields.io/packagist/v/calebdw/fakerstan.svg" alt="Packagist Version"></a>
    <a href="https://packagist.org/packages/calebdw/fakerstan"><img src="https://img.shields.io/packagist/dt/calebdw/fakerstan.svg" alt="Total Downloads"></a>
  </p>
</div>

## Install

```bash
composer require calebdw/fakerstan --dev
```

If you have the [PHPStan extension installer](https://phpstan.org/user-guide/extension-library#installing-extensions) installed then nothing more is needed, otherwise you will need to manually include the extension in the `phpstan.neon(.dist)` configuration file:

```neon
includes:
    - ./vendor/calebdw/fakerstan/extension.neon
```

## Features

### Custom Providers

FakerStan will automatically look for unknown methods on the custom providers on
the Faker instance and infer the return type based on the method signature, even
generics are supported:

```php
<?php
use Faker\Generator;
use Faker\Provider\Base;

class CustomProvider extends Base
{
    public function customMethod(): string
    {
        return 'custom';
    }

    /**
     * @template T
     * @param T $object
     * @return class-string<T>
     */
    public function classFromObject(object $object): string
    {
        return $object::class
    }
}

$faker = new Generator();
$faker->addProvider(new CustomProvider($faker));

$faker->customMethod(); // string
$faker->classFromObject(new User); // class-string<User>
```

## Configuration

In order for FakerStan to correctly detect the custom providers, it needs the actual
Faker instance used in the project (or at least an instance with the custom
providers added to it).

If using Laravel, FakerStan will automatically resolve the faker instance from
the container using the `fake()` helper function. If not using Laravel, you can
specify a custom factory class in the `phpstan.neon(.dist)` configuration file:

```neon
parameters:
  fakerstan:
    fakerProviderFactory: App\CustomFakerProviderFactory
services:
  - class: App\CustomFakerProviderFactory
```

where `App\CustomFakerProviderFactory` is a class that creates an implementation of
the `CalebDW\FakerStan\FakerProvider` interface:

```php
<?php

use Faker\Generator;
use CalebDW\FakerStan\FakerProvider;

class CustomFakerProviderFactory
{
    public static function create(): FakerProvider
    {
        return new CustomFakerProvider();
    }
}

class CustomFakerProvider implements FakerProvider
{
    public function getFaker(): Generator
    {
        // or from a DI container
        $faker = new Generator();
        $faker->addProvider(new CustomProvider($faker));
        return $faker;
    }
}

```

## Contributing

Thank you for considering contributing! You can read the contribution guide [here](CONTRIBUTING.md).

## License

FakerStan is open-sourced software licensed under the [MIT license](LICENSE).

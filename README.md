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

## Contributing

Thank you for considering contributing! You can read the contribution guide [here](CONTRIBUTING.md).

## License

FakerStan is open-sourced software licensed under the [MIT license](LICENSE).

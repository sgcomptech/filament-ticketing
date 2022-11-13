# A Laravel Filament plugin to support issue tracking and ticketing system.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sgcomptech/filament-ticketing.svg?style=flat-square)](https://packagist.org/packages/sgcomptech/filament-ticketing)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/sgcomptech/filament-ticketing/run-tests?label=tests)](https://github.com/sgcomptech/filament-ticketing/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/sgcomptech/filament-ticketing/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/sgcomptech/filament-ticketing/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/sgcomptech/filament-ticketing.svg?style=flat-square)](https://packagist.org/packages/sgcomptech/filament-ticketing)

A Laravel Filament plugin package to add support for issue tracking and ticketing system into your Filament project.

## Support us


## Installation

You can install the package via composer:

```bash
composer require sgcomptech/filament-ticketing
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-ticketing-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-ticketing-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-ticketing-views"
```

## Usage

```php
$filamentTicketing = new SGCompTech\FilamentTicketing();
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

If you discover any security related issues, please email sgcomptechpteltd@gmail.com instead of using the issue tracker.

## Credits

- [Lee Kai Mun](https://github.com/leekaimun)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

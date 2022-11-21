# A Laravel Filament plugin to support issue tracking and ticketing system.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/sgcomptech/filament-ticketing.svg?style=flat-square)](https://packagist.org/packages/sgcomptech/filament-ticketing)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/sgcomptech/filament-ticketing/run-tests?label=tests)](https://github.com/sgcomptech/filament-ticketing/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/sgcomptech/filament-ticketing/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/sgcomptech/filament-ticketing/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/sgcomptech/filament-ticketing.svg?style=flat-square)](https://packagist.org/packages/sgcomptech/filament-ticketing)

A Laravel Filament Admin Panel plugin package to add support for issue tracking and ticketing system into your Filament project.

## Support us


## Installation

You can install the package via composer:

```bash
composer require sgcomptech/filament-ticketing
```

Run the migrations with:

```bash
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

### Filament Admin Panel Menu

As with most other Filament resources, you can control and secure the access of Ticket operations in Admin Panel's menu with a policy file. For example, you can create a new policy file at app/Policies/TicketPolicy.php and register in your AuthServiceProvider.

```php
```

For more details, see [Laravel model policies](https://laravel.com/docs/9.x/authorization#creating-policies).

### Authorization

This package has to option to use Laravel policies to authorise various actions performed on the tickets.

Besides the usual Filament resource polices, the additional permissions to implement are:

1. `manageAllTickets` - grant permission to user who can add comments and change status of any tickets.
2. `manageAssignedTickets` - grant permission to user who can only add comments and change status to tickets that are explicitly assigned to them.
3. `assignTickets` - grant permission to user who can assign tickets to any users who have the permission `manageAssignedTickets`.

TODO: give example of implementation with Spatie Laravel-Permission

### Events

This package will dispatch the following events as listed in the table below. Note that the namespace of these events is `Sgcomptech\FilamentTicketing\Events`.

| Event | Event Object | Description |
| ----------- | ----------- | ----------- |
| `NewTicket` | `Ticket` | When a new ticket is created. |
| `NewComment` | `Comment` | When a new comment is created. |
| `NewResponse` | `Comment` | When a new response is created. |
| `NewAssignment` | `Ticket` | When a ticket is being assigned to a user. |

You can use these events to send notifications to relevant users in your application. Refer to Laravel document on how to [register Events and Listeners](https://laravel.com/docs/9.x/events#generating-events-and-listeners).

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

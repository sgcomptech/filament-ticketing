<?php

namespace Sgcomptech\FilamentTicketing;

use Spatie\LaravelPackageTools\Package;
use Filament\PluginServiceProvider;

// use Sgcomptech\FilamentTicketing\Filament\Resources\TicketResource\Pages\CreateTicket;

class FilamentTicketingServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-ticketing';
    // use Vendor\Package\Resources\CustomResource;
    // protected array $resources = [ CustomResource::class, ];
    // use Vendor\Package\Widgers\CustomWidget;
    // protected array $widgets = [ CustomWidget::class, ];
    // protected array $pages = [ CreateTicket::class, ];

    public function boot()
    {
        parent::boot();
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // $this->loadViewsFrom(__DIR__ . '/../resources/views', static::$name);
    }

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        // parent::configurePackage($package);
        $package
            ->name(static::$name)
            ->hasMigrations(['create_tickets_table', 'create_comments_table'])
            ->hasViews(static::$name) // spent half a day trying to make this work :( end up declaring at boot()
            ->hasConfigFile(static::$name);
            // ->hasTranslations()
    }

    protected function getResources(): array
    {
        return config('filament-ticketing.resources');
    }
}

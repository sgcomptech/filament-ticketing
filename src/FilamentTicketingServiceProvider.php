<?php

namespace SGCompTech\FilamentTicketing;

use Spatie\LaravelPackageTools\Package;
use Filament\PluginServiceProvider;

class FilamentTicketingServiceProvider extends PluginServiceProvider
{
    public static string $name = 'filament-ticketing';
    // use Vendor\Package\Resources\CustomResource;
    // protected array $resources = [ CustomResource::class, ];
    // use Vendor\Package\Pages\CustomPage;
    // protected array $pages = [ CustomPage::class, ];
    // use Vendor\Package\Widgers\CustomWidget;
    // protected array $widgets = [ CustomWidget::class, ];

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->hasMigrations(['create_tickets_table', 'create_comments_table']);
            // ->hasRoute('web');
            // ->hasCommand(FilamentTicketingCommand::class);
        parent::configurePackage($package);
    }

    protected function getResources(): array
    {
        return config('filament-ticketing.resources');
    }
}

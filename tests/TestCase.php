<?php

namespace Sgcomptech\FilamentTicketing\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\SpatieLaravelSettingsPluginServiceProvider;
use Filament\SpatieLaravelTranslatablePluginServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use RyanChandler\BladeCaptureDirective\BladeCaptureDirectiveServiceProvider;
use Sgcomptech\FilamentTicketing\FilamentTicketingServiceProvider;
use Sgcomptech\FilamentTicketing\Models\Ticket;
use Sgcomptech\FilamentTicketing\Tests\Policies\TicketPolicy;

class TestCase extends Orchestra
{
    // use  RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['filament-ticketing.user-model' => 'Sgcomptech\FilamentTicketing\Tests\User']);
        config(['auth.providers.users.model' => 'Sgcomptech\FilamentTicketing\Tests\User']);

        Gate::policy(Ticket::class, TicketPolicy::class);
    }

    protected function getPackageProviders($app)
    {
        return [
            BladeCaptureDirectiveServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            LivewireServiceProvider::class,
            NotificationsServiceProvider::class,
            SpatieLaravelSettingsPluginServiceProvider::class,
            SpatieLaravelTranslatablePluginServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            FilamentTicketingServiceProvider::class,
        ];
    }

    // public function defineEnvironment($app)
    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $migration = include __DIR__ . '/../database/migrations/create_tickets_table.php';
        $migration->up();
        $migration = include __DIR__ . '/../database/migrations/create_comments_table.php';
        $migration->up();
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }
}

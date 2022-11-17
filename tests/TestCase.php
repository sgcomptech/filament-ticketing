<?php

namespace Sgcomptech\FilamentTicketing\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Gate;
use Orchestra\Testbench\TestCase as Orchestra;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Livewire\LivewireServiceProvider;
use Sgcomptech\FilamentTicketing\FilamentTicketingServiceProvider;
use Sgcomptech\FilamentTicketing\Models\Ticket;
use Sgcomptech\FilamentTicketing\Tests\Policies\TicketPolicy;

class TestCase extends Orchestra
{
    use  RefreshDatabase;
    protected $policies = [];

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
            LivewireServiceProvider::class,
            FilamentServiceProvider::class,
            FormsServiceProvider::class,
            FilamentTicketingServiceProvider::class,
            NotificationsServiceProvider::class,
            SupportServiceProvider::class,
            TablesServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            BladeIconsServiceProvider::class,
        ];
    }

    // public function getEnvironmentSetUp($app)
    public function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
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

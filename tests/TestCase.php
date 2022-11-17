<?php

namespace Sgcomptech\FilamentTicketing\Tests;

use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Livewire\LivewireServiceProvider;
use Sgcomptech\FilamentTicketing\FilamentTicketingServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TestCase extends Orchestra
{
    use  RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Factory::guessFactoryNamesUsing(
        // fn (string $modelName) => 'Sgcomptech\\FilamentTicketing\\Database\\Factories\\'.class_basename($modelName).'Factory'
        // );
        config(['filament-ticketing.user-model' => 'Sgcomptech\FilamentTicketing\Tests\User']);
        config(['auth.providers.users.model' => 'Sgcomptech\FilamentTicketing\Tests\User']);

        // now re-register all the roles and permissions (clears cache and reloads relations)
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
        Role::create(['name' => 'Super Admin']);
        $adminRole = Role::create(['name' => 'Administrator']);
        $managerRole = Role::create(['name' => 'Manager']);
        $supportRole = Role::create(['name' => 'Support']);

        $deleteTickets = Permission::create(['name' => 'delete tickets']);
        $manageAllTickets = Permission::create(['name' => 'manage all tickets']);
        $manageAssignedTickets = Permission::create(['name' => 'manage assigned tickets']);
        $assignTickets = Permission::create(['name' => 'assign tickets']);

        $adminRole->givePermissionTo($deleteTickets);
        $adminRole->givePermissionTo($manageAllTickets);
        $managerRole->givePermissionTo($manageAllTickets);
        $managerRole->givePermissionTo($assignTickets);
        $supportRole->givePermissionTo($manageAssignedTickets);

        $adminUser = new User();
        $adminUser->id = 1;
        $adminUser->name = 'Admin';
        $adminUser->email = 'admin@admin.com';
        $adminUser->password = \Illuminate\Support\Facades\Hash::make('password');
        $adminUser->email_verified_at = now();
        $adminUser->save();
        $adminUser->assignRole('Super Admin');
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
            PermissionServiceProvider::class,
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

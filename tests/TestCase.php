<?php

namespace SGCompTech\FilamentTicketing\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use SGCompTech\FilamentTicketing\FilamentTicketingServiceProvider;
use Filament\FilamentServiceProvider;
use Livewire\LivewireServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        // Factory::guessFactoryNamesUsing(
            // fn (string $modelName) => 'SGCompTech\\FilamentTicketing\\Database\\Factories\\'.class_basename($modelName).'Factory'
        // );
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            FilamentServiceProvider::class,
            FilamentTicketingServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $this->loadLaravelMigrations(['--database' => 'testbench']);
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
        $migration = include __DIR__.'/../database/migrations/create_tickets_table.php';
        $migration->up();
        $migration = include __DIR__.'/../database/migrations/create_comments_table.php';
        $migration->up();
    }
}

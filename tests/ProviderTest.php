<?php


namespace Orchid\Crud\Tests;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Orchid\Crud\Middleware\BootCrudGenerator;

class ProviderTest extends TestCase
{
    public function testMiddlewareRegister(): void
    {
        $this->assertTrue(Route::hasMiddlewareGroup('platform'));

        $this->assertContains(
            BootCrudGenerator::class, Route::getMiddlewareGroups()['platform']
        );
    }

    public function testRouteRegister(): void
    {
        $this->assertTrue(Route::has('platform.resource.create'));
        $this->assertTrue(Route::has('platform.resource.edit'));
        $this->assertTrue(Route::has('platform.resource.list'));
    }

    public function testArtisanMakeResource(): void
    {
        $name = Str::random();

        $this->artisan('orchid:resource', ['name' => $name])
            ->expectsOutput('Resource created successfully.')
            ->assertExitCode(0);

        $this->assertFileExists(app_path('Orchid/Resources/' . $name . '.php'));

        $this->artisan('orchid:resource', ['name' => $name])
            ->expectsOutput('Resource already exists!')
            ->assertExitCode(0);
    }
}

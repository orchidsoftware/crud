<?php


namespace Orchid\Crud\Tests;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Orchid\Crud\ResourceRoute;

class ProviderTest extends TestCase
{
    public function testRouteRegister(): void
    {
        $this->assertTrue(Route::has('platform.resource.create'));
        $this->assertTrue(Route::has('platform.resource.edit'));
        $this->assertTrue(Route::has(ResourceRoute::LIST->name()));
    }

    public function testArtisanMakeResource(): void
    {
        $name = Str::random();

        $this->artisan('orchid:resource', ['name' => $name])
            ->expectsOutputToContain('created successfully.')
            ->assertExitCode(0);

        $this->assertFileExists(app_path('Orchid/Resources/' . $name . '.php'));

        $this->artisan('orchid:resource', ['name' => $name])
            ->expectsOutputToContain('already exists')
            ->assertExitCode(0);
    }
}

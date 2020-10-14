<?php


namespace Orchid\Crud\Tests;

use Illuminate\Support\Facades\Route;

class ProviderTest extends TestCase
{
    public function testRouteRegister():void
    {
        $this->assertTrue(Route::has('platform.resource.create'));
        $this->assertTrue(Route::has('platform.resource.edit'));
        $this->assertTrue(Route::has('platform.resource.list'));
    }

    public function testArtisanMakeResource(): void
    {
        $name = time();

        $this->artisan('orchid:resource', ['name' => $name])
            ->expectsOutput('Resource created successfully.')
            ->assertExitCode(0);

        $this->assertFileExists(app_path('Orchid/Resources/' . $name . '.php'));

        $this->artisan('orchid:resource', ['name' => $name])
            ->expectsOutput('Resource already exists!')
            ->assertExitCode(0);
    }
}

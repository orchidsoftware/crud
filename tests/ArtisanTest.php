<?php


namespace Orchid\Crud\Tests;

class ArtisanTest extends TestCase
{
    public function testArtisanMakeResource(): void
    {
        $name = time();

        $this->artisan('orchid:resource', ['name' => $name])
            ->expectsOutput('Resource created successfully.')
            ->assertExitCode(0);

        $this->assertFileExists(app_path('Orchid/Resource/' . $name . '.php'));

        $this->artisan('orchid:resource', ['name' => $name])
            ->expectsOutput('Resource already exists!')
            ->assertExitCode(0);
    }
}

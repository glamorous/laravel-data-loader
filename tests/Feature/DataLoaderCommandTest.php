<?php

use Glamorous\DataLoader\Tests\Support\LoaderThatShouldLoad;
use Glamorous\DataLoader\Tests\Support\LoaderThatShouldNotLoad;
use Illuminate\Support\Facades\Config;

it('reports when there are no loaders to execute', function () {
    Config::set('data-loader.loaders', []);

    $this->artisan('data-loader:run')
        ->expectsOutputToContain('No loaders to execute.')
        ->assertExitCode(0);
});

it('executes loaders that should load', function () {
    Config::set('data-loader.loaders', [LoaderThatShouldLoad::class]);

    $this->artisan('data-loader:run')
        ->expectsOutputToContain('LoaderThatShouldLoad executed successfully.')
        ->assertExitCode(0);
});

it('skips loaders that should not load', function () {
    Config::set('data-loader.loaders', [LoaderThatShouldNotLoad::class]);

    $this->artisan('data-loader:run')
        ->expectsOutputToContain('No loaders to execute.')
        ->assertExitCode(0);
});

it('lists loaders without executing them on a dry run', function () {
    Config::set('data-loader.loaders', [LoaderThatShouldLoad::class]);

    $this->artisan('data-loader:run --dry-run')
        ->expectsOutputToContain('LoaderThatShouldLoad would have executed.')
        ->assertExitCode(0);
});

it('errors when the given loader class is not configured', function () {
    Config::set('data-loader.loaders', []);

    $this->artisan('data-loader:run UnknownLoader')
        ->expectsOutputToContain("Given class 'UnknownLoader' does not exist in your data loader config.")
        ->assertExitCode(0);
});

it('only runs the loader matching the given class argument', function () {
    Config::set('data-loader.loaders', [
        LoaderThatShouldLoad::class,
        LoaderThatShouldNotLoad::class,
    ]);

    $this->artisan('data-loader:run LoaderThatShouldLoad')
        ->expectsOutputToContain('LoaderThatShouldLoad executed successfully.')
        ->assertExitCode(0);
});

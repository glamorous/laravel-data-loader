<?php

namespace Glamorous\DataLoader\ServiceProviders;

use Glamorous\DataLoader\Commands\DataLoaderCommand;
use Illuminate\Support\ServiceProvider;

class DataLoaderServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../../config/data-loader.php' => config_path('data-loader.php'),
        ], 'laravel-data-loader-config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                DataLoaderCommand::class,
            ]);
        }
    }
}

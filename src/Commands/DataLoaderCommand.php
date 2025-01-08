<?php

namespace Glamorous\DataLoader\Commands;

use Glamorous\DataLoader\Database\DataLoader;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class DataLoaderCommand extends Command
{
    protected $signature = 'data-loader:run {--force}';

    protected $description = 'Ensure the required data is loaded to run the application.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        collect(config('data-loader.loaders'))
            ->map(fn (string $className) => new $className)
            ->ensure(DataLoader::class)
            ->filter(fn (DataLoader $loaderClass) => $this->option('force') || $loaderClass->shouldLoad())
            ->each(fn (DataLoader $loaderClass) => $loaderClass());
    }

    protected function getOptions(): array
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run ignore the checks'],
        ];
    }
}

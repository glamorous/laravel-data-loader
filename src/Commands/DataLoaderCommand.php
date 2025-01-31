<?php

namespace Glamorous\DataLoader\Commands;

use Glamorous\DataLoader\Database\DataLoader;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DataLoaderCommand extends Command
{
    protected $signature = 'data-loader:run {loaderClass?} {--force}';

    protected $description = 'Ensure the required data is loaded to run the application.';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $loaders = collect(Config::get('data-loader.loaders', []));
        $givenLoaderClass = $this->argument('loaderClass');

        if ($givenLoaderClass) {
            $loader = $loaders->first(function (string $loaderFullClassName) use ($givenLoaderClass) {
                return Str::endsWith($loaderFullClassName, $givenLoaderClass);
            });

            if (is_null($loader)) {
                $this->error("Given class '{$givenLoaderClass}' does not exist in your data loader config.");
                return;
            }

            $loaders = collect([$loader]);
        }

        $this->invokeLoaders($loaders);
    }

    protected function getOptions(): array
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run ignore the checks'],
        ];
    }

    protected function getArguments(): array
    {
        return [
            ['loader', InputArgument::OPTIONAL, 'The name of the loader you would like to run'],
        ];
    }

    private function invokeLoaders(Collection $loaders): void
    {
        $loaders
            ->map(fn (string $fullClassName) => new $fullClassName)
            ->ensure(DataLoader::class)
            ->filter(fn (DataLoader $loaderClass) => $this->option('force') || $loaderClass->shouldLoad())
            ->each(fn (DataLoader $loaderClass) => $loaderClass());
    }
}

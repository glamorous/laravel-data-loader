<?php

namespace Glamorous\DataLoader\Tests\Support;

use Glamorous\DataLoader\Database\DataLoader;

class LoaderThatShouldLoad implements DataLoader
{
    public function __invoke(): void
    {
        //
    }

    public function shouldLoad(): bool
    {
        return true;
    }
}

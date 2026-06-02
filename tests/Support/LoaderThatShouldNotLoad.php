<?php

namespace Glamorous\DataLoader\Tests\Support;

use Glamorous\DataLoader\Database\DataLoader;

class LoaderThatShouldNotLoad implements DataLoader
{
    public function __invoke(): void
    {
        //
    }

    public function shouldLoad(): bool
    {
        return false;
    }
}

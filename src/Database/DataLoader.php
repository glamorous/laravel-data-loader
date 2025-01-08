<?php

namespace Glamorous\DataLoader\Database;

interface DataLoader
{
    public function __invoke(): void;

    public function shouldLoad(): bool;
}

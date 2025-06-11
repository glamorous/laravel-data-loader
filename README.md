# Laravel Dataloader
Small package to provide tools for data migrations and loading initial data into your database.

## Installation

You can install the package via composer:

```bash
composer require glamorous/laravel-data-loader
```

You should publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-data-loader-config"
```

This is the contents of the published config file:

```php
return [
    'loaders' => [
        // EnsureSomeDataIsPresent::class,
    ],
];
```

## Usage

Provide a list of loaders in `config/data-loader.php` with their class names. Those classes should implement the `DataLoader`-interface.

### Example data loader class

```php
<?php

namespace Database\States;

use App\Models\User;
use Glamorous\Database\DataLoader;

readonly final class EnsureSuperAdminIsPresent implements DataLoader
{
    public function __invoke(): void
    {
        $user = User::query()
            ->where('identifier', '=', $this->getSuperAdminIdentifier())
            ->firstOrNew();

        $user->identifier = $this->getSuperAdminIdentifier();
        $user->email = $this->getEmail();
        $user->password =  $this->getPassword();
        $user->name = 'Super Admin';

        $user->save();
    }

    public function shouldLoad(): bool
    {
        return !User::where('identifier', '=', $this->getSuperAdminIdentifier())->exists();
    }

    protected function getEmail(): string
    {
        return config('custom.superadmin.email');
    }

    protected function getPassword(): string
    {
        return Hash::make(config('custom.superadmin.password'))
    }

    protected function getSuperAdminIdentifier(): string
    {
        return config('custom.superadmin.identifier');
    }
}

```

### Calling the loader

The command can be run after the migrations if you put the following in the boot of a Service Provider:

```php
Event::listen(MigrationsEnded::class, function() {
    Artisan::call(DataLoaderCommand::class);
});
```

Or you can just call the command in your CI-scripts:

```bash
php artisan data-loader:run
```

It's also possible to run the data-loader only for one specific data-loader class:

```bash
php artisan data-loader:run EnsureSuperAdminIsPresent
```

#### Options

If necessary you can run the command with the `--force` flag. This way it would not check if its data needs to be loaded or not. It's required to confirm your choice.

If you want to see which loaders would be executed, without executing them, you can pass the `--dry-run` option, and it will show you the loaders that would have executed.

## Contributing

Package is open for pull requests!

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jonas De Smet](https://github.com/glamorous)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

# :package_description

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dive-be/:package_name.svg?style=flat-square)](https://packagist.org/packages/dive-be/:package_name)

**Note:** Run `./init.sh` to get started, or manually replace  ```:author_name``` ```:author_username``` ```:author_email``` ```:package_name``` ```:package_description``` with their correct values in [README.md](README.md), [CHANGELOG.md](CHANGELOG.md), [CONTRIBUTING.md](.github/CONTRIBUTING.md), [LICENSE.md](LICENSE.md) and [composer.json](composer.json) files, then delete this line. You can also run `init.sh` to do this automatically.

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

⚠️ Minor releases of this package may cause breaking changes as it has no stable release yet.

## What problem does this package solve?

Optionally describe why someone would want to use this package.

## Installation

You can install the package via composer:

```bash
composer require dive-be/:package_name
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --provider="Dive\Skeleton\SkeletonServiceProvider" --tag="migrations"
php artisan migrate
```

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Dive\Skeleton\SkeletonServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$skeleton = new Dive\Skeleton();
echo $skeleton->echoPhrase('Hello, Dive!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email oss@dive.be instead of using the issue tracker.

## Credits

- [:author_name](https://github.com/:author_username)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

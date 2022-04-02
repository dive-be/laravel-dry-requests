# Dry run requests

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dive-be/laravel-dry-requests.svg?style=flat-square)](https://packagist.org/packages/dive-be/laravel-dry-requests)

This package allows you to check if your requests would pass validation if you executed them normally.
The Laravel equivalent of `--dry-run` in various CLI tools.

⚠️ Minor releases of this package may cause breaking changes as it has no stable release yet.

## What problem does this package solve?

A traditional approach to validating user input in JavaScript applications (Inertia / SPA / Mobile) is using a library such as **yup**
to do the heavy lifting and delegating complex business validations to the server.

However, the client-side can never be trusted, so you can't simply omit the validation rules that ran on the front-end.
This means that validation has to live in 2 distinct places and you will have to keep them in sync.
This is very tedious and wasteful, so this is where this package comes into play.

Hit the endpoint **as users are entering information on the form** to provide real-time feedback with 100% accuracy. 

## Installation

You can install the package via composer:

```bash
composer require dive-be/laravel-dry-requests
```

## Usage

TODO

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

- [Muhammed Sari](https://github.com/mabdullahsari)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

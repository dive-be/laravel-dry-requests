# Changelog

All notable changes to `laravel-dry-requests` will be documented in this file.

## 2.3.0 - 2023-05-03

### Removed

- PHP 8.1 support

## 2.2.0 - 2023-05-03

### Added

- Laravel 10 support

### Removed

- Laravel 9 support

## 2.1.0 - 2022-06-16

### Added

- Invoke dry request behavior through regular `Illuminate\Http\Request` objects.

## 2.0.0 - 2022-06-16

### Added

- Change validation behavior of dry requests using the `Dry` attribute
- Change validation behavior of dry requests using the `X-Dry-Run` header
- Globally define default validation behavior using the config file.

### Changed

- Succesful dry requests now return `200 OK` instead of `202 Accepted`.
This is to ensure compatibility with apps using Inertia.

### Removed

- `dry` request parameter. Use header `X-Dry-Run` instead.

## 1.1.0 - 2022-04-28

### Added

- Made the `DryRunnable` trait more flexible

## 1.0.0 - 2022-04-03

- Initial release

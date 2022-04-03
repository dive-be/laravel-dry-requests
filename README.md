# 🥵 Dry run your requests

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dive-be/laravel-dry-requests.svg?style=flat-square)](https://packagist.org/packages/dive-be/laravel-dry-requests)

This package allows you to check if your requests would pass validation if you executed them normally.
The Laravel equivalent of `--dry-run` in various CLI tools.

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

You can publish the config file with:
```bash
php artisan vendor:publish --provider="Dive\DryRequests\ServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
    /**
     * Name of the request parameter that will determine whether a request is running dry.
     */
    'parameter' => 'dry',
];
```

## Usage

> 📣 You **must** use `FormRequest` classes

Assume the following endpoint: `POST /users` and `Controller`:

```php
class UsersController
{
    public function store(StoreUserRequest $request): UserResource
    {
        $user = User::create($request->validated());
    
        return new UserResource($user);
    }
}
```

Add the `DryRunnable` trait to your `FormRequest`:

```php
class StoreUserRequest extends FormRequest
{
    use DryRunnable;

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'nickname' => ['nullable', 'string', 'min:2', 'max:255'],
        ];
    }
}
```

Now, hit the endpoint from the client-side like you normally would, but with the added `dry` flag (or the one you configured in the config file).

```js
axios.post('/users', {
    dry: true,
    email: 'muhammed@dive.be',
    name: 'Muhammed Sari'
}).then(function (response) {
    return response.status; // 202
});
```

- If the request succeeds, your `Controller` logic **will not be executed**.
- If a validation error occurs, the first error will be returned.
- Absent fields that are required **will not be validated** to ensure good UX.

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

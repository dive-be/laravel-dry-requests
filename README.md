# 🥵 Dry run your requests

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dive-be/laravel-dry-requests.svg?style=flat-square)](https://packagist.org/packages/dive-be/laravel-dry-requests)

This package allows you to check if your requests would pass validation if you executed them normally. 
(The Laravel equivalent of `--dry-run` in various CLI tools, or what some call "preflight requests").

🚀 Hit the endpoint **as users are entering information on the form** to provide real-time feedback with 100% accuracy. 

🚀 Validate only a subset of data of a multi-step form to guarantee success when the form is eventually submitted.


## What problem does this package solve?

A traditional approach to validating user input in JavaScript applications (Inertia / SPA / Mobile) is using a library such as **yup**
to do the heavy lifting and delegating complex business validations to the server.

However, the client-side can never be trusted, so you can't simply omit the validation rules that ran on the front-end.
This means that validation has to live in 2 distinct places and you will have to keep them in sync.
This is very tedious and wasteful, so this is where this package comes into play.

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
    /*
    |--------------------------------------------------------------------------
    | Default Dry Validation Behavior
    |--------------------------------------------------------------------------
    |
    | All dry requests are validated against a subset of the defined rules.
    | In other words only present fields are checked during the request.
    | You may choose to halt validation on an error or check 'em all.
    |
    | Supported: "all", "first"
    |
    */

    'validation' => 'first',
];
```

## Usage

### Prerequisites

📣 You **must** be using [`FormRequest` classes](https://laravel.com/docs/9.x/validation#form-request-validation), otherwise the included `DryRunnable` trait will not work.

### Example

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
// 422 Unprocessable Entity
axios.post('/users', { dry: true, email: 'muhammed' }).then(response => response.status);
     
// 202 Accepted
axios.post('/users', { dry: true, name: 'Muhammed Sari' }).then(response => response.status);

// 202 Accepted
axios.post('/users', { dry: true, email: 'muhammed@dive.be', name: 'Muhammed Sari' }).then(response => response.status);

// 201 Created
axios.post('/users', { email: 'muhammed@dive.be', name: 'Muhammed Sari' }).then(response => response.status);
```

### Behavior

- `Controller` logic is *not* executed after a *successful* validation attempt.
- Validation stops as soon as an error is encountered. This error is then returned.
- Absent fields that have the `required` rule will *not* be validated to ensure good UX. 
  - This means that you don't *really have to* keep track of a `touched` state for your FE form fields, but you still should nonetheless for other purposes.

### Conflicting `FormRequest` methods

The package makes use of the available methods `passedValidation` and `withValidator` available on `FormRequest` classes to enable its behavior.

If you define these in your own requests, you must call the "dry" methods manually:

```php
class CreateUserRequest extends FormRequest
{
    protected function passedValidation()
    {
        $this->stopWhenDry(); // must be called first
        
        // your custom logic
    }
    
    protected function withValidator(Validator $instance)
    {
        $instance = /* your custom logic */;
        
        $this->withDryValidator($instance); // must be called last
    }
}
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

- [Muhammed Sari](https://github.com/mabdullahsari)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

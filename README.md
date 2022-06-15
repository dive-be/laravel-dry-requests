<p align="center"><img src="/art/socialcard.jpg" alt="Social Card of Laravel Dry Requests"></p>

# X-Dry-Run your requests

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dive-be/laravel-dry-requests.svg?style=flat-square)](https://packagist.org/packages/dive-be/laravel-dry-requests)
[![Total Downloads](https://img.shields.io/packagist/dt/dive-be/laravel-dry-requests.svg?style=flat-square)](https://packagist.org/packages/dive-be/laravel-dry-requests)

This package allows you to check if your requests would pass validation if you executed them normally. 
(The Laravel equivalent of `--dry-run` in various CLI tools, or what some call "preflight requests").

🚀 Hit the endpoint as users are entering information on the form to provide real-time feedback with 100% accuracy. 

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

## Behavior

💡 `Controller` logic is not executed after a successful validation attempt. `200 OK` is returned upon a successful dry run.

💡 **Only present fields** are validated to ensure good UX. Other fields are skipped using the `sometimes` rule.
This means that *you* are responsible for only sending the relevant fields for validating e.g. a step of a multi-step wizard.

## Usage

📣 You **must** be using [`FormRequest` classes](https://laravel.com/docs/9.x/validation#form-request-validation), otherwise the included `DryRunnable` trait will not work.

### Back-end preparation

Assume the following endpoint: `POST /users` and `Controller` injecting a `StoreUserRequest`:

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
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'username' => ['required', 'string', 'min:2', 'max:255', 'unique:users'],
            'nickname' => ['nullable', 'string', 'min:2', 'max:255'],
        ];
    }
}
```

### Front-end execution

Now, hit the endpoint from the client-side like you normally would.
But with the added `X-Dry-Run` header.

```js
// 1. "Username has already been taken" validation error
axios.post('/users', { username: 'Agent007' }, { headers: { 'X-Dry-Run': true } })
     .then(response => response.status); // 422 Unprocessable Entity
     
// 2. Successful unique username check: Controller did not execute
axios.post('/users', { username: 'Asil Kan' }, { headers: { 'X-Dry-Run': true } })
     .then(response => response.status); // 200 OK
     
// 3. Successful unique e-mail check: Controller did not execute
axios.post('/users', { email: 'muhammed@dive.be' }, { headers: { 'X-Dry-Run': true } })
     .then(response => response.status); // 200 OK

// 4. Submit entire form: Controller executed
axios.post('/users', { email: 'muhammed@dive.be', username: 'Asil Kan' })
     .then(response => response.status); // 201 Created
```

### Fine-tuning Dry Validations: All Failures / First Failure

- The default validation behavior for dry requests is halting validation as soon as an error is found.
This is especially useful when handling async validation for a **single field**. 
- The other option is to keep validating even if an error is encountered. 
This is especially useful for multi-step forms.

You can alter this behavior on 3 distinct levels.

1. Change `first` to `all` in the `dry-request` config file. This will apply to all of your requests.
2. Use the `Dive\DryRequests\Dry` attribute along with `Dive\DryRequests\Validation` on the `rules` method 
to force a specific `Validation` behavior for a particular `FormRequest`.
```php
#[Dry(Validation::FirstFailure)]
public function rules(): array
{
    return [...];
}
```
3. Dictate the behavior on the fly from the front-end using the `X-Dry-Run` header. Valid values: `all`, `first`.
```php
axios.post('/users', { email: '...', username: '...' }, { headers: { 'X-Dry-Run': 'all' } })
     .then(response => response.status); // 200 OK
```

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

## Upgrading

Please see [UPGRADING](UPGRADING.md) for details.

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

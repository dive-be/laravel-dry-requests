<?php declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Default Dry Validation Behavior
    |--------------------------------------------------------------------------
    |
    | All dry requests are validated against a subset of the defined rules.
    | In other words only present fields are checked during the request.
    | You may choose to halt validation as soon as a failure occurs,
    | or continue validating all fields and return all failures.
    |
    | Supported: "all", "first"
    |
    */

    'validation' => 'first',
];

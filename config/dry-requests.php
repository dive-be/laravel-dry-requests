<?php declare(strict_types=1);

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

<?php declare(strict_types=1);

namespace Tests;

use function Pest\Laravel\artisan;

afterAll(function () {
    file_exists(config_path('skeleton.php')) && unlink(config_path('skeleton.php'));
    array_map('unlink', glob(database_path('migrations/*_create_skeleton_table.php')));
});

it('copies the config', function () {
    artisan('skeleton:install')->execute();

    expect(
        file_exists(config_path('skeleton.php'))
    )->toBeTrue();
});

it('copies the migration', function () {
    artisan('skeleton:install')->execute();

    expect(
        glob(database_path('migrations/*_create_skeleton_table.php'))
    )->toHaveCount(1);
});

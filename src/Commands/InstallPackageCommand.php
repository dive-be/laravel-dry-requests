<?php declare(strict_types=1);

namespace Dive\Skeleton\Commands;

use Illuminate\Console\Command;

class InstallPackageCommand extends Command
{
    protected $description = 'Install skeleton.';

    protected $signature = 'skeleton:install';

    public function handle(): int
    {
        if ($this->isHidden()) {
            $this->error('🤚  Skeleton is already installed.');

            return self::FAILURE;
        }

        $this->line('🏎  Installing skeleton...');
        $this->line('📑  Publishing configuration...');

        $this->call('vendor:publish', [
            '--provider' => "Dive\Skeleton\SkeletonServiceProvider",
            '--tag' => 'config',
        ]);

        $this->line('📑  Publishing migration...');

        $this->call('vendor:publish', [
            '--provider' => "Dive\Skeleton\SkeletonServiceProvider",
            '--tag' => 'migrations',
        ]);

        $this->info('🏁  Skeleton installed successfully!');

        return self::SUCCESS;
    }

    public function isHidden(): bool
    {
        return file_exists(config_path('skeleton.php'));
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Override the filesystem instance with our custom implementation
        $this->app->singleton('files', function () {
            return new \App\Services\CustomFilesystem();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Override tempnam function to use proper temp directory
        if (app()->environment('local')) {
            ini_set('sys_temp_dir', '/var/www/html/storage/tmp');
            ini_set('upload_tmp_dir', '/var/www/html/storage/tmp');
        }

        // Register class-based components
        $this->loadViewComponentsAs('', [
            \App\View\Components\LineChart::class,
            \App\View\Components\BarChart::class,
            \App\View\Components\PieChart::class,
        ]);
    }
}

<?php

namespace Ghalibilal\LaravelHmacEncryption;

use Illuminate\Support\ServiceProvider;
use Ghalibilal\LaravelHmacEncryption\EncryptionService;
use Ghalibilal\LaravelHmacEncryption\Support\QueryBuilderMacros;
use Ghalibilal\LaravelHmacEncryption\Console\EncryptionCommand;

class EncryptionServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(__DIR__.'/../config/encryption.php', 'encryption');

        // Bind service
        $this->app->singleton('encryption-service', function () {
            return new EncryptionService();
        });
    }

    public function boot()
    {
        // Allow publishing config
        if ($this->app->runningInConsole()) {
            // Register Artisan command
            $this->commands([
                EncryptionCommand::class,
            ]);
            $this->publishes([
                __DIR__.'/../config/encryption.php' => config_path('encryption.php'),
            ], 'config');
        }
        QueryBuilderMacros::register();
    }
}

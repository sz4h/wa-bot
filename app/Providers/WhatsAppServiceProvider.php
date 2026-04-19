<?php

namespace App\Services\WhatsApp;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class WhatsAppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Bind the abstract driver to the concrete implementation
        $this->app->singleton(WhatsAppDriver::class, function ($app) {
            // Determine which driver to use based on config
            $driver = config('services.whatsapp.default_driver', 'evolutionapi'); // Default to Evolution API

            switch ($driver) {
                case 'evolutionapi':
                    return $app->make(EvolutionApiDriver::class);
                // Add cases for other drivers like UltramsgDriver, etc.
                // case 'ultramsg':
                //     return $app->make(UltramsgDriver::class);
                default:
                    throw new \Exception("Unsupported WhatsApp driver: {$driver}");
            }
        });

        // Register concrete drivers if they are not automatically resolved
        $this->app->singleton(EvolutionApiDriver::class);
        // $this->app->singleton(UltramsgDriver::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish the configuration file
        $this->publishes([
            __DIR__.'/../../config/whatsapp.php' => config_path('whatsapp.php'),
        ], 'whatsapp-config');
    }
}

<?php

namespace Docstron\Laravel;

use Illuminate\Support\ServiceProvider;

class DocstronServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/docstron.php', 'docstron'
        );

        $this->app->singleton('docstron', function ($app) {
            return new DocstronClient(
                config('docstron.api_key'),
                config('docstron.base_url', 'https://api.docstron.com/v1')
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/docstron.php' => config_path('docstron.php'),
            ], 'docstron-config');
        }
    }
}

<?php

namespace Zaichaopan\Taggable;

use Illuminate\Support\ServiceProvider;

class TaggableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
    }

    /**
    * Register the application services.
    *
    * @return void
    */
    public function register()
    {
    }
}

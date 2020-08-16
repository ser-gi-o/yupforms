<?php

namespace YupForms;

use Illuminate\Support\ServiceProvider;

class YupFormsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //config file
        /*$this->publishes([
            __DIR__.'yupforms.php' => config_path('yupforms.php'),
        ]);*/

        //routes
        $this->loadRoutesFrom(__DIR__.'/Http/routes.php');

        //migrations
        $this->loadMigrationsFrom(__DIR__.'/migrations');

        //views
        $this->loadViewsFrom(__DIR__.'/views', 'yupforms');
    }
}

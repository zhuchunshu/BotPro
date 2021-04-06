<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class BotCoreServiceProvider extends ServiceProvider
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
        $this->app->call("\App\BotPro\Core@handle");
    }
}

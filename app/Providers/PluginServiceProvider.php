<?php

namespace App\Providers;

use App\Models\Plugin;
use App\Services\PluginManager;
use Illuminate\Support\ServiceProvider;

class PluginServiceProvider extends ServiceProvider
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
    public function boot(PluginManager $plugins)
    {
        $bootstrappers = $plugins->getAllPlugins();

        foreach ($bootstrappers as $name => $value) {
           
            // $this->app->call($value->);
            if(Plugin::where(['name' => $name,'status'=>1])->count()){
                if(@count($value['data']['class'])){
                    foreach ($value['data']['class'] as $dataClass) {
                        $c= $value['class'].$dataClass;
                        if (method_exists(new $c(), 'handle')) {
                            $this->app->call($value['class'].$dataClass."@handle");
                        }
                    }
                }
            }
        }
    }
}

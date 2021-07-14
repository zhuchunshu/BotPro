<?php
namespace App\BotPro;

use App\Http\Controllers\BotCoreController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

class Core {

    public function ver(){
        return true;
    }

    public function handle(){
        $this->route();
    }

    public function route(){
        Route::group([
            'prefix'     => config('admin.route.prefix'),
            'middleware' => config('admin.route.middleware'),
        ], function () {
            // Route::get('fuduji', [IndexController::class,'show']);
            Route::get('/auth', [BotCoreController::class,'auth']);
            Route::post('/auth', [BotCoreController::class,'post']);
        });
    }
}
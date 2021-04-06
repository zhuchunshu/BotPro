<?php
namespace App\BotPro;

use App\Http\Controllers\BotCoreController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

class Core {

    public function ver(){
        if(file_exists(storage_path('logs/'.get_options('BOT_AUTH').".log"))){
            if(Cache::get('admin.auth')==md5(read_file(storage_path('logs/' . get_options('BOT_AUTH') . ".log")))){
                return true;
            }else{
                $response = Http::post(base64_decode('aHR0cHM6Ly9jZmF1dGgubm9kZS50YXgvYXBpL2F1dGgvdmVyaWZ5'), [
                    'domain' => _port(Request::server('HTTP_HOST')),
                    'hash' => read_file(storage_path('logs/' . get_options('BOT_AUTH') . ".log")),
                    'class' => 'botpro'
                ]);
                $data = $response->json();
                if($data['code']==0){
                    // 验证通过
                    Cache::put('admin.auth', md5(read_file(storage_path('logs/' . get_options('BOT_AUTH') . ".log"))), 86400);
                    return true;
                }else{
                    // 验证不通过
                    return false;
                }
            }
        }else{
            return false;
        }
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
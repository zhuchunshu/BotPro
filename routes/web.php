<?php

use App\Models\Option;
use App\BotPro\Bootstrap;
use App\Services\BotCore;
use App\Services\PluginManager;
use Illuminate\Support\Facades\Route;
use Psr\Container\ContainerInterface;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    ob_end_clean();
    ob_implicit_flush(1);
    $i = 1;
    while (true) {
        echo $i."<br>";
        $i++;
        sleep(1);
    }
});

Route::get('/dev/Plugin', function (PluginManager $pluginManager) {
    return $pluginManager->getAllPlugins();
});

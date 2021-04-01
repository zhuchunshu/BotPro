<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Dcat\Admin\Admin;

Admin::routes();

Route::group([
    'prefix'     => config('admin.route.prefix'),
    'namespace'  => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    // 设置

    $router->group(['prefix' => 'setting'],function(Router $router){
        $router->get('/','OptionController@index');
        $router->get('/create','OptionController@create');
        $router->post('/','OptionController@store');
        $router->delete('/{id}','OptionController@destroy');
        $router->put('/{id}','OptionController@update');
        $router->get('/{id}','OptionController@show');
        $router->get('/{id}/edit','OptionController@edit');
    });

    // 核心授权

    $router->group(['prefix' => 'BotCore'],function(Router $router){
        $router->get('/','BotCoreController@index');
        $router->get('/create','BotCoreController@create');
        $router->post('/','BotCoreController@store');
        $router->delete('/{id}','BotCoreController@destroy');
        $router->put('/{id}','BotCoreController@update');
        $router->get('/{id}','BotCoreController@show');
        $router->get('/{id}/edit','BotCoreController@edit');
    });

    // 插件

    $router->group(['prefix' => 'Plugin'],function(Router $router){
        $router->get('/','PluginController@index');
        $router->put('/{name}','PluginController@update');
    });

});

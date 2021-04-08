<?php

use App\Models\Option;
use App\Models\BotCore;
use Illuminate\Support\Str;
use App\Jobs\BotPro\SendMsg;
use App\Models\Plugin;
use App\Services\BotCore as ServicesBotCore;

function get_options($name){
    if(Option::where('name',$name)->count()){
        return Option::where('name',$name)->first()->value;
    }else{
        return null;
    }
}
function get_options_count($name){
    return Option::where('name',$name)->count();
}
function getPath($path)
{
    if (!is_dir($path)) {
        return false;
    }
    $arr = array();
    $data = scandir($path);
    foreach ($data as $value) {
        if ($value != '.' && $value != '..') {
            $arr[] = $value;
        }
    }
    return $arr;
}

function read_file($file_path)
{
    if (file_exists($file_path)) {
        $fp = fopen($file_path, "r");
        $str = fread($fp, filesize($file_path)); //指定读取大小，这里把整个文件内容读取出来
        return $str;
    } else {
        return null;
    }
}

/**
 * 发送通知
 *
 * @param array 发送数据 $data
 * @param string 请求路径 $action
 * @param string 网站 $url
 * @return void
 */
function sendMsg($data,$action,$url=null){
    return dispatch(new SendMsg($data,$action,$url));
}

/**
 * 发送数据
 *
 * @param array 发送数据 $data
 * @param string 请求路径 $action
 * @param string 网站 $url
 * @return void
 */
function sendData($data,$action,$url=null){
    $ServicesBotCore = new ServicesBotCore();
    return $ServicesBotCore->Send($data,$action,$url);
}

/**
 * 分割指令
 *
 * @param object $data
 * @param string $delimiter
 * @return array
 */
function GetZhiling($data,$delimiter=" "){
    return explode($delimiter,$data->message);
}

function authorizeGroup_get(){
    return BotCore::where(['type' => 'group'])->get();
}

function authorizeGroup_check(int $number){
    return BotCore::where(['type' => 'group','value' => $number])->count();
}

function Json_Api($status,$message,$type){
    return [
        'status'=>$status,
        'data'=>[
            'message' => $message,
            'type' => $type
        ]
    ];
}

function cq_at_qq($text){
    $text = Str::after($text, 'qq=');
    $text = Str::before($text, ']');
    return $text;
}
/**
 * 获取插件信息
 *
 * @param string $name
 * @return object
 */
function get_plugin_data($name){
    if(file_exists(app_path("Plugins/".$name."/"."data.json"))){
        return json_decode(@read_file(app_path("Plugins/".$name."/"."data.json")));
    }else{
        return null;
    }
}

function get_plugin_status($name){
    if(Plugin::where(['name' => $name,'status' => 1])->count()){
        return true;
    }else{
        return false;
    }
}

function _port($text)
{
    $text = Str::before($text, ':');
    $text = str_replace('www.', '', $text);
    return $text;
}

function plugin_path($path){
    return app_path("Plugins/".$path);
}
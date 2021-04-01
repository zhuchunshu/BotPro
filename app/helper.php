<?php

use App\Models\Option;

function get_options($name){
    if(Option::where('name',$name)->count()){
        return Option::where('name',$name)->first()->value;
    }else{
        return null;
    }
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
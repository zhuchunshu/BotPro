<?php

namespace App\BotPro;

class Bootstrap{
    
    /**
     * 正向WS链接地址
     * @var string
     */
    public $zxws;

    /**
     * 机器人连接Token
     *
     * @var string
     */
    public $token;

    public function __construct()
    {
        if(get_options('BOT_ZXWS') && get_options('BOT_TOKEN')){
            // 设置正向ws链接
            $this->zxws = get_options('BOT_ZXWS') . "?access_token=" . get_options('BOT_TOKEN');
        }
    }
    
    /**
     * 连接前检查
     *
     * @return void
     */
    public function Check(){
        if(get_options('BOT_ZXWS') && get_options('BOT_TOKEN') && get_options('BOT_HTTP')){
            // 设置正向ws链接
            return true;
        }else{
            return false;
        }
    }

    /**
     * 程序版本
     *
     * @var integer
     */
    public $version = 9;
}
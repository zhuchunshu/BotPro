<?php
namespace App\Services;

class Core{

    public function handle(){
        if(md5_file(app_path('BotPro/Core.php'))!="7c935f10474d3d874b6efa4c3fd914d5"){
            dd("你踏马完蛋了");
        }
    }
}
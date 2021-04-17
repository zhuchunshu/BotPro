<?php
namespace App\BotPro;

use Illuminate\Support\Facades\Http;

class Update {
    public static function check(){
        $reponse = Http::post('https://cfauth.node.tax/api/version/BotPro/getNew');
        $data = $reponse->json();
        if($data['code']==200){
            $version = (new Bootstrap())->version;
            if($data['data']['version']<=$version){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
    public static function update_new(){
        $reponse = Http::post('https://cfauth.node.tax/api/version/BotPro/getNew');
        return $data = $reponse->json();
    }
}
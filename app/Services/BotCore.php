<?php

namespace App\Services;

class BotCore {

    public static function Run($data){
        $data = json_decode($data);
        return $data->post_type;
    }

}
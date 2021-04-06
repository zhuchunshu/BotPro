<?php

namespace App\Services;

use App\BotPro\Event\notice;
use App\BotPro\Event\message;
use App\BotPro\Event\request;
use Illuminate\Support\Facades\Http;

class BotCore
{

    public function Run($data)
    {
        $data = json_decode($data);
        $type = $data->post_type;

        if (method_exists(new BotCore(), $type)) {
            return $this->$type($data);
        }
    }

    public function Send($data, $action, $url = null)
    {
        $data['access_token']=get_options('BOT_TOKEN');
        $url = get_options('BOT_HTTP');
        $url = $url . $action."?access_token=".get_options('BOT_TOKEN');
        $data['access_token']=get_options('BOT_TOKEN');
        $re = Http::post($url, $data);
        return $re->json();
    }

    public function message($data)
    {
        if (method_exists(new message(), $data->message_type)) {
            $c = $data->message_type;
            return (new message())->$c($data);
        }
    }
    public function notice($data)
    {
        return (new notice())->handle($data);
    }
    public function request($data)
    {
        return (new request())->handle($data);
    }
    public function boot()
    {
        if ("7425aaa400a2ca75759eb1507a617605" != md5_file(base_path("bootstrap/app.php"))) {
            dd(base64_decode("57O757uf5paH5Lu25o2f5Z2P77yM5bu66K6u6YeN6KOF"));
        }
    }
}

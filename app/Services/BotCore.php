<?php

namespace App\Services;

use App\Models\Plugin;
use App\Models\BotCore as BotCoreModels;
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
        $url = get_options('BOT_HTTP');
        $re = Http::withToken(get_options('BOT_TOKEN'))->post($url . $action, $data);
        return $re->json();
    }

    public function message($data)
    {
        if ($data->message_type == "group") {
            // 群组消息
            if (BotCoreModels::where(['type' => 'group', 'value' => $data->group_id])->count()) {
                $pluginManager = new PluginManager();
                foreach ($pluginManager->getAllPlugins() as $name => $value) {
                    $value['PluginMark'] = $name;
                    if (Plugin::where(['name' => $name, 'status' => 1])->count()) {
                        if (@count($value['data']['post_type']['message']['group'])) {
                            foreach ($value['data']['post_type']['message']['group'] as $dataClass) {
                                $c = $value['class'] . "src\\" . $dataClass;
                                if (method_exists(new $c(), 'register')) {
                                    try {
                                        (new $c())->register($data, $value);
                                    } catch (\Throwable $th) {
                                        sendMsg([
                                            'group_id' => $data->group_id,
                                            'message' => "出错啦:\n\n" . $th
                                        ], "send_group_msg");
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($data->message_type == "private") {
            // 私聊
            $pluginManager = new PluginManager();
            foreach ($pluginManager->getAllPlugins() as $name => $value) {
                $value['PluginMark'] = $name;
                if (Plugin::where(['name' => $name, 'status' => 1])->count()) {
                    if (@count($value['data']['post_type']['message']['private'])) {
                        foreach ($value['data']['post_type']['message']['private'] as $dataClass) {
                            $c = $value['class'] . "src\\" . $dataClass;
                            if (method_exists(new $c(), 'register')) {
                                try {
                                    (new $c())->register($data, $value);
                                } catch (\Throwable $th) {
                                    sendMsg([
                                        'user_id' => $data->user_id,
                                        'message' => "出错啦:\n\n" . $th
                                    ], "send_private_msg");
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

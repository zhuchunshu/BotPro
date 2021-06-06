<?php

namespace App\BotPro\Event;

use App\Models\Plugin;
use App\Services\PluginManager;
use Illuminate\Support\Facades\Http;
use App\Models\BotCore as BotCoreModels;

class message
{

    public function group($data)
    {
        if (BotCoreModels::where(['type' => 'group', 'value' => $data->group_id])->count()) {
            $pluginManager = new PluginManager();
            foreach ($pluginManager->getAllPlugins() as $name => $value) {
                $value['PluginMark'] = $name;
                if (Plugin::where(['name' => $name, 'status' => 1])->count()) {
                    if (@$value['data']['post_type']['message']['group'] && @count($value['data']['post_type']['message']['group'])) {
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

    public function private($data)
    {
        $pluginManager = new PluginManager();
        foreach ($pluginManager->getAllPlugins() as $name => $value) {
            $value['PluginMark'] = $name;
            if (Plugin::where(['name' => $name, 'status' => 1])->count()) {
                if (@$value['data']['post_type']['message']['private'] && @count($value['data']['post_type']['message']['private'])) {
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

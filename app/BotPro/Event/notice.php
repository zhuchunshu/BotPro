<?php

namespace App\BotPro\Event;

use App\Models\Plugin;
use App\Services\PluginManager;
use Illuminate\Support\Facades\Http;
use App\Models\BotCore as BotCoreModels;

class notice
{
    public function handle($data)
    {
        if (@$data->group_id) {
            if (BotCoreModels::where(['type' => 'group', 'value' => $data->group_id])->count()) {
                $pluginManager = new PluginManager();
                foreach ($pluginManager->getAllPlugins() as $name => $value) {
                    $value['PluginMark'] = $name;
                    if (Plugin::where(['name' => $name, 'status' => 1])->count()) {
                        if (@count($value['data']['post_type']['notice'][$data->notice_type])) {
                            foreach ($value['data']['post_type']['notice'][$data->notice_type] as $dataClass) {
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
        } else {
            $pluginManager = new PluginManager();
            foreach ($pluginManager->getAllPlugins() as $name => $value) {
                $value['PluginMark'] = $name;
                if (Plugin::where(['name' => $name, 'status' => 1])->count()) {
                    if (@count($value['data']['post_type']['notice'][$data->notice_type])) {
                        foreach ($value['data']['post_type']['notice'][$data->notice_type] as $dataClass) {
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
}

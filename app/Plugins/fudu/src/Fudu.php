<?php
namespace App\Plugins\fudu\src;

class Fudu {

    /**
     * 接收到的数据
     *
     * @var array
     */
    public $data;

    /**
     * 指令
     *
     * @var array
     */
    public $zhiling;

    /**
     * 插件执行
     *
     * @param object 接收到的数据 $data
     * @param array 插件信息 $value
     * @return void
     */
    public function handle($data,$value){
        $this->data = $data;
        sendMsg([
            'group_id' => $data->group_id,
            'message' => "插件标识:\n\n{$value['PluginMark']}"
        ], "send_group_msg");
    }

}
<?php

namespace App\Services;

class PluginManager{

    /**
     * 获取所有插件
     *
     * @return array
     */
    public function getAllPlugins(){
        $path = app_path("Plugins");
        $arr = getPath($path);
        $plugin_arr = [];
        foreach ($arr as $value) {
            if(file_exists(app_path("Plugins/".$value."/"."boot.php")) && file_exists(app_path("Plugins/".$value."/"."data.json"))){
                $plugin_arr[$value]['path']=app_path("Plugins/".$value."/");
                $ns = "\App\Plugins\\".$value."\\";
                $plugin_arr[$value]['class']= $ns;
                $plugin_arr[$value]['data']=json_decode(@read_file(app_path("Plugins/".$value."/"."data.json")),true);
            }
        }
        return $plugin_arr;
    }

}
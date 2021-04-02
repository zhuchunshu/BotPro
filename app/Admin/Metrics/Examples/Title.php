<?php

namespace App\Admin\Metrics\Examples;

use App\Models\Plugin;
use Illuminate\Http\Request;
use App\Services\PluginManager;
use Dcat\Admin\Widgets\Metrics\RadialBar;

class Title
{
    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        return view('info');
    }

    /**
     * 处理请求
     *
     * @param Request $request
     *
     * @return mixed|void
     */
    public function handle(Request $request)
    {
        return view('info');
    }
}

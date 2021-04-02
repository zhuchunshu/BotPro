<?php

namespace App\Admin\Metrics\Examples;

use App\Models\Plugin;
use Illuminate\Http\Request;
use App\Services\PluginManager;
use Dcat\Admin\Widgets\Metrics\RadialBar;

class Tickets extends RadialBar
{
    /**
     * 初始化卡片内容
     */
    protected function init()
    {
        parent::init();

        $this->title('插件统计');
        $this->height(400);
        $this->chartHeight(300);
        $this->chartLabels('已启用插件');
        // $this->dropdown([
        //     '7' => 'Last 7 Days',
        //     '28' => 'Last 28 Days',
        //     '30' => 'Last Month',
        //     '365' => 'Last Year',
        // ]);
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
        switch ($request->get('option')) {
            case '365':
            case '30':
            case '28':
            case '7':
            default:
                // 插件总数
                $pluginManager = new PluginManager();
                $this->withContent(count($pluginManager->getAllPlugins()));
                // 卡片底部
                $data_en = Plugin::where('status',1)->count();
                $data_d = Plugin::where('status',0)->count();
                $data_c = Plugin::count();
                $this->withFooter($data_en, $data_d, $data_c);
                // 图表数据
                $this->withChart(($data_en/count($pluginManager->getAllPlugins()))*100);
        }
    }

    /**
     * 设置图表数据.
     *
     * @param int $data
     *
     * @return $this
     */
    public function withChart(int $data)
    {
        return $this->chart([
            'series' => [$data],
        ]);
    }

    /**
     * 卡片内容
     *
     * @param string $content
     *
     * @return $this
     */
    public function withContent($content)
    {
        return $this->content(
            <<<HTML
<div class="d-flex flex-column flex-wrap text-center">
    <h1 class="font-lg-2 mt-2 mb-0">{$content}</h1>
    <small>扫描到的插件总数</small>
</div>
HTML
        );
    }

    /**
     * 卡片底部内容.
     *
     * @param string $new
     * @param string $open
     * @param string $response
     *
     * @return $this
     */
    public function withFooter($new, $open, $response)
    {
        return $this->footer(
            <<<HTML
<div class="d-flex justify-content-between p-1" style="padding-top: 0!important;">
    <div class="text-center">
        <p>数据库中已启用的插件数量</p>
        <span class="font-lg-1">{$new}</span>
    </div>
    <div class="text-center">
        <p>数据库中已禁用的插件数量</p>
        <span class="font-lg-1">{$open}</span>
    </div>
    <div class="text-center">
        <p>数据库中的插件记录总量</p>
        <span class="font-lg-1">{$response}</span>
    </div>
</div>
HTML
        );
    }
}

<?php
namespace App\Admin\Repositories;

use Dcat\Admin\Grid\Model;
use Dcat\Admin\Grid\Filter\Scope;
use Dcat\Admin\Repositories\Repository;

class Update extends Repository {

    /**
     * 定义主键字段名称 
     * 
     * @return string
     */
    public function getPrimaryKeyColumn()
    {
        return 'id';
    }

    /**
     * 查询表格数据
     *
     * @param Model $model
     * @return LengthAwarePaginator
     */
    public function get(Model $model)
    {
        // 当前页数
        $currentPage = $model->getCurrentPage();
        // 每页显示行数
        $perPage = $model->getPerPage();
        
        $client = new \GuzzleHttp\Client();

        $response = $client->post("https://cfauth.node.tax/api/version/BotPro/get?page={$currentPage}&perPage={$perPage}");
        $data = json_decode((string)$response->getBody(), true);

        return $model->makePaginator(
            $data['data']['total'] ?? 0, // 传入总记录数
            $data['data']['data'] ?? [] // 传入数据二维数组
        );
    }
}
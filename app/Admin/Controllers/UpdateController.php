<?php

namespace App\Admin\Controllers;

use ZipArchive;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Widgets\Card;
use Dcat\Admin\Widgets\Form;
use Illuminate\Http\Request;
use Dcat\Admin\Layout\Content;
use App\Services\PluginManager;
use App\Admin\Repositories\Update;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class UpdateController extends Controller
{

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new Update(), function (Grid $grid) {
            $grid->column('');
            $grid->column('id', '版本id');
            $grid->column('version', '版本号');
            $grid->column('content', '更新说明');
            //$grid->column('value');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();
            $grid->paginate(15);
            $grid->disablePerPages();
            $grid->disableRowSelector();
            $grid->disableCreateButton();
            $grid->tools('<a class="btn btn-primary disable-outline">测试按钮</a>');
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
            });
        });
    }

    /**
     * 获取所有插件
     *
     * @return array
     */
    public function generate()
    {
        // $PluginManager = new PluginManager();
        // $data = [];
        // foreach ($PluginManager->getAllPlugins() as $key => $value) {
        //     $check = ModelsPlugin::where(['name' => $key, 'status' => 1])->count();
        //     $data[] = [
        //         'id' => $key,
        //         'name' => $key,
        //         'PluginName' => ($value['data']['name']),
        //         'path' => $value['path'],
        //         'namespace' => $value['class'],
        //         'status' => $check,
        //     ];
        // }
        // return $data;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        $data = Http::post('https://cfauth.node.tax/api/version/BotPro@'.$id.'/getData');
        if($data['code']==200){
            $card = Card::make($data['code'], $data['msg']);
        }else{
            $card = Card::make($data['code'], $data['msg']);
        }
        return $card;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
    }
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = "BotPro软件升级";

    /**
     * Set description for following 4 action pages.
     *
     * @var array
     */
    protected $description = [
        'index'  => '更新日志',
        //        'show'   => 'Show',
        //        'edit'   => 'Edit',
        //        'create' => 'Create',
    ];

    /**
     * Get content title.
     *
     * @return string
     */
    protected function title()
    {
        return $this->title ?: admin_trans_label();
    }

    /**
     * Get description for following 4 action pages.
     *
     * @return array
     */
    protected function description()
    {
        return $this->description;
    }

    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description()['index'] ?? trans('admin.list'))
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     *
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description()['show'] ?? trans('admin.show'))
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description()['edit'] ?? trans('admin.edit'))
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description()['create'] ?? trans('admin.create'))
            ->body($this->form());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($name, Form $form)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store(Request $request)
    {
        //$path = $request->file('_file_')->store('file');
        $file = $request->_file_;
        $file->move(app_path("Plugins"), $file->getClientOriginalName());
        $path = app_path("Plugins/" . $file->getClientOriginalName());
        //实例化ZipArchive类
        $zip = new ZipArchive();
        //打开压缩文件，打开成功时返回true

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->form()->destroy($id);
    }
}

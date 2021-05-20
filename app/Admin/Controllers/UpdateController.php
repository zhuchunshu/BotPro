<?php

namespace App\Admin\Controllers;

use ZipArchive;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Admin;
use Illuminate\Support\Str;
use Dcat\Admin\Widgets\Card;
use Dcat\Admin\Widgets\Form;
use Illuminate\Http\Request;
use Dcat\Admin\Layout\Content;
use App\Services\PluginManager;
use Dcat\Admin\Widgets\Markdown;
use App\Admin\Repositories\Update;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\BotPro\Update as BotProUpdate;
use Illuminate\Support\Facades\Artisan;

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
            $grid->column('content', '更新说明')->display(function ($text) {
                return Str::limit($text, 30, '...');
            });
            //$grid->column('value');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();
            $grid->paginate(15);
            $grid->disablePerPages();
            $grid->disableRowSelector();
            $grid->disableCreateButton();
            $grid->disableEditButton();
            $grid->disableDeleteButton();
            $update = BotProUpdate::check();
            if ($update === true) {
                $grid->tools('<button class="btn btn-primary" id="update">立即更新</button>');
            }
            $r = config('admin.route.prefix');
            Admin::script(
                <<<JS
                    $("#update").click(function(){
                        Dcat.confirm('确定要更新吗? 更新前做好备份！！！', null, function () {
                            location.href="/{$r}/update/@/Run";
                        });
                    })
                JS
            );
            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
            });
        });
    }

    public function Run(Content $content){
        return $content
        ->title($this->title())
        ->description($this->description()['index'] ?? trans('admin.list'))
        ->body($this->Run_show());
    }

    public function Run_show(){
        if(!config('app.update')){
            $content = "您禁用了远程更新功能";
        }else{
            if(BotProUpdate::check()){
                $content = shell_exec('cd ../ && bash ./bootstrap/update2.sh');
            }else{
                $content = "已是最新版，无需更新";
            }
        }
        $card = Card::make("BotPro软件更新", $content);
        return $card;
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
        $data = Http::post('https://cfauth.node.tax/api/version/BotPro@' . $id . '/getData');
        if ($data['code'] == 200) {
            $card = Card::make(
                $data['data']['class']['name'] . "版本ID为:" . $data['data']['id'] . "的更新内容",
                Markdown::make("#### 版本号: {$data['data']['version']}
#### 更新说明:
{$data['data']['content']}"));
        } else {
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

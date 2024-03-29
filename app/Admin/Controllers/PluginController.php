<?php

namespace App\Admin\Controllers;

use ZipArchive;
use Faker\Factory;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Dcat\Admin\Layout\Content;
use App\Services\PluginManager;
use Madnest\Madzipper\Madzipper;
use App\Admin\Repositories\Plugin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\Plugin as ModelsPlugin;
use Illuminate\Support\Facades\Storage;

class PluginController extends Controller
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return new Grid(null, function (Grid $grid) {
            $grid->column('id', '插件标识')->explode()->label();
            $grid->column('PluginName', '插件名称')->explode()->label();
            $grid->column('namespace', '插件命名空间')->explode('\\')->label();
            $grid->column('path', '插件路径');
            $grid->column('status', '开启/关闭')->status()->switch();
            $grid->disableRowSelector();
            //$grid->disableCreateButton();
            $grid->disableActions();
            $grid->disableBatchDelete();
            $grid->disablePagination();
            $grid->model()->setData($this->generate());
        });
    }

    /**
     * 获取所有插件
     *
     * @return array
     */
    public function generate()
    {
        $PluginManager = new PluginManager();
        $data = [];
        foreach ($PluginManager->getAllPlugins() as $key => $value) {
            $check = ModelsPlugin::where(['name' => $key, 'status' => 1])->count();
            $data[] = [
                'id' => $key,
                'name' => $key,
                'PluginName' => ($value['data']['name']),
                'path' => $value['path'],
                'namespace' => $value['class'],
                'status' => $check,
            ];
        }
        return $data;
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
        return Show::make($id, new Plugin(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('path');
            $show->field('class');
            $show->field('status');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new Plugin(), function (Form $form) {
            $form->file('file', '选择插件')->accept('zip')->removable();
            $form->disableFooter();
        });
    }
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title;

    /**
     * Set description for following 4 action pages.
     *
     * @var array
     */
    protected $description = [
        //        'index'  => 'Index',
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
    public function update($name)
    {
        $data = read_plugin_data($name,false);
        if(@Arr::has($data,"dirname") && $data['dirname']!=$name){
            rename(plugin_path($name),plugin_path($data['dirname']));
            $name = $data['dirname'];
        }
        $status = request()->input('status', 0);
        if (ModelsPlugin::where('name', $name)->count()) {
            // 存在
            ModelsPlugin::where('name', $name)->update([
                'status' => $status
            ]);
            if ($status) {
                $ev = "启用";
            } else {
                $ev = "禁用";
            }
        } else {
            // 不存在
            ModelsPlugin::insert([
                'name' => $name,
                'status' => $status,
                'created_at' => date("Y-m-d H:i:s")
            ]);
            $ev = "禁用";
        }
        return [
            'status' => true,
            'data' => [
                'message' => "插件:" . $name . $ev . '成功!',
                'type' => 'success'
            ]
        ];
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
        if ($zip->open($path) === true) {
            //解压文件到获得的路径a文件夹下
            $zip->extractTo(app_path("Plugins/"));
            //关闭
            $zip->close();
            File::delete($path);
            return Json_Api(true,"插件安装成功!","success");
        } else {
            return Json_Api(true,"插件上传失败!","error");
        }
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

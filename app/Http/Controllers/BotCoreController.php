<?php

namespace App\Http\Controllers;

use App\BotPro\Core;
use Dcat\Admin\Form;
use App\Models\Option;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Dcat\Admin\Layout\Content;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class BotCoreController extends Controller
{
    protected function form()
    {
        $core = new Core();
        if($core->ver()){
            return redirect('/admin');
        }else{
            return Form::make(null, function (Form $form) {
                //$form->text('name');
                $form->action('auth');
                $form->text('domain','域名')->disable()->value(request()->server('HTTP_HOST'));
                $form->text('code','输入授权码')->required();
                $form->disableListButton();
                $form->disableViewButton();
                $form->disableDeleteButton();
                $form->disableViewCheck();
                $form->disableEditingCheck();
                $form->disableCreatingCheck();
            });
        }
    }
    protected function view(){
        return <<<HTML
        <h3>已授权!</h3>
        <h3>感谢您对BotPro的支持</h3>
HTML;
    }

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = "BotPro";

    /**
     * Set description for following 4 action pages.
     *
     * @var array
     */
    protected $description = [
               'index'  => 'Index',
               'show'   => 'Show',
               'edit'   => 'Edit',
               'create' => '授权验证',
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

    public function auth(Content $content)
    {
        return $content
            ->title($this->title())
            ->description($this->description()['create'] ?? trans('admin.create'))
            ->body($this->form());
    }

    public function post(Request $request){
        $value = $request->input('code');
        $response = Http::post(base64_decode('aHR0cHM6Ly9jZmF1dGgubm9kZS50YXgvYXBpL2F1dGgvdmVyaWZ5'),[
            'domain' => _port($request->server('HTTP_HOST')),
            'hash' => $value,
            'class' => 'botpro'
        ]);
        $data = $response->json();
        if($data['code']==0){
            $name = Str::random(19);
            File::put(storage_path('logs/' . $name . ".log"),$value);
            if(get_options_count('BOT_AUTH')){
                // 更新
                Option::where(['name' => 'BOT_AUTH'])->update([
                    'value' => $name
                ]);
            }else{
                // 新增
                Option::insert([
                    'name' => 'BOT_AUTH',
                    'value' => $name,
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }
            Cache::put('admin.auth', md5(read_file(storage_path('logs/' . get_options('BOT_AUTH') . ".log"))), 86400);
            return Json_Api(1,base64_decode('5o6I5p2D6aqM6K+B6YCa6L+H'),'success');
        }else{
            return Json_Api(1,base64_decode('5o6I5p2D6aqM6K+B5aSx6LSl'),'error');
        }
    }
}

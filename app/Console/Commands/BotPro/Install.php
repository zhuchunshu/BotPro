<?php

namespace App\Console\Commands\BotPro;

use App\Models\Option;
use Dcat\Admin\Models\Administrator;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BotPro:Install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'BotPro安装';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if(PHP_VERSION>="7.3.0"){
            if(config('app.url')=="http://localhost"){
                $web_url = $this->ask('网站地址(https://www.codefec.com)');
                $database = [];
                $database['name'] = $this->ask('数据库名');
                $database['user'] = $this->ask('数据库用户名');
                $database['pwd'] = $this->ask('数据库密码');
                $env = [
                    'APP_URL' => $web_url,
                    'DB_DATABASE' => $database['name'],
                    'DB_USERNAME' => $database['user'],
                    'DB_PASSWORD' => $database['pwd'],
                    'ADMIN_ROUTE_PREFIX' => Str::random(8),
                    'APP_DEBUG' => 'false'
                ];
                $this->modifyEnv($env);
                Artisan::call('key:generate');
                $this->info('配置成功! 如果需要修改配置请编辑网站根目录下.env文件');
                $this->info('请重新运行脚本进行数据库迁移');
            }else{
                Artisan::call('migrate --force');
                $this->info('数据库迁移成功!');
                Artisan::call('admin:install');
                $pwd = Str::random(12);
                Administrator::where('username','admin')->update([
                    'password' => bcrypt($pwd),
                    'updated_at' => date("Y-m-d H:i:s")
                ]);
                $this->info('后台管理员账号创建成功!');
                $d['BOT_TOKEN'] = $this->ask('机器人连接秘钥');
                $d['BOT_HTTP'] = $this->ask('机器人http连接地址(http://127.0.0.1:5700/)');
                $d['BOT_ZXWS'] = $this->ask('机器人正向websocket连接地址(ws://127.0.0.1:6700/)');
                foreach ($d as $key => $value) {
                    Option::insert([
                        'name' => $key,
                        'value' => $d[$key],
                        'created_at' => date("Y-m-d H:i:s")
                    ]);
                }
                $this->info('必要设置项创建成功!');
                File::put(base_path() . DIRECTORY_SEPARATOR . 'install.lock', 'BotPro 已安装');
                $this->info('本次安装结束。');
                $this->info("访问: 域名/".config('admin.route.prefix')." 进入后台!");
                $this->info('默认账号: admin');
                $this->info('密码: '.$pwd);
            }
        }else{
            $this->error('PHP版本必须大于7.3.0');
        }
    }
    public function modifyEnv(array $data)
    {
        $envPath = base_path() . DIRECTORY_SEPARATOR . '.env';

        $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));

        $contentArray->transform(function ($item) use ($data) {
            foreach ($data as $key => $value) {
                if (str_contains($item, $key)) {
                    return $key . '=' . $value;
                }
            }

            return $item;
        });

        $content = implode("\n",$contentArray->toArray());

        File::put($envPath, $content);
    }
}

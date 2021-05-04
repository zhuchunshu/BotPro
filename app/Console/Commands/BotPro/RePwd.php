<?php

namespace App\Console\Commands\BotPro;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Dcat\Admin\Models\Administrator;

class RePwd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BotPro:Repwd';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '重置登录密码';

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
        $pwd = Str::random(12);
        Administrator::where('id',1)->update([
            'password' => bcrypt($pwd),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
        $this->info("重置成功,新密码:".$pwd);
    }
}

<?php

namespace App\Console\Commands\BotPro;

use App\Models\Option;
use Illuminate\Console\Command;

class Watcher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BotPro:Watcher';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '监控文件变动';

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
        $watcher = new \App\BotPro\Watcher\Watcher(app_path(), function ($event) {
            if(!get_options_count("BOT_START")){
                Option::insert([
                    'name' => 'BOT_START',
                    'value' => $event->file,
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            }
            $this->info("文件变动:".$event->file);
        });
        $watcher->watch();
    }
}

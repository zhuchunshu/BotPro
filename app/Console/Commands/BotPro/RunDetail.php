<?php

namespace App\Console\Commands\BotPro;

use App\Models\Option;
use App\BotPro\Bootstrap;
use App\Services\BotCore;
use Illuminate\Console\Command;

class RunDetail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BotPro:RunDetail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'BotPro运行中展示细节';

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
        $run = new Bootstrap();
        if ($run->Check()) { 
            \Ratchet\Client\connect($run->zxws)->then(function($conn) {
                $conn->on('message', function($msg) use ($conn) {
                    if(!get_options_count("BOT_START")){
                        $conn->close();
                    }else{
                        try {
                            $this->info($msg."\n");
                            $this->info((new BotCore)->Run($msg)."\n");
                        } catch (\Throwable $th) {
                            $this->error($th."\n");
                        }
                    }
                });
            }, function ($e) {
                $this->error("Could not connect: {$e->getMessage()}\n");
            });
        } else {
            $this->error('连接失败,请确保机器人设置完整');
        }
        return 0;
    }
}

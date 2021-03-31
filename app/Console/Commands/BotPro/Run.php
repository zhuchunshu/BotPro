<?php

namespace App\Console\Commands\BotPro;

use App\BotPro\Bootstrap;
use Illuminate\Console\Command;
use Illuminated\Console\WithoutOverlapping;

class Run extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    use WithoutOverlapping;
    protected $signature = 'BotPro:Run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '运行机器人';

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
                    $this->info("$msg \n");
                    //$conn->close();
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

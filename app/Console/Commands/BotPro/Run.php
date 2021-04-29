<?php

namespace App\Console\Commands\BotPro;

use App\Models\Option;
use App\BotPro\Bootstrap;
use App\Jobs\BotPro\RunJob;
use App\Services\BotCore;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminated\Console\WithoutOverlapping;

class Run extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    use WithoutOverlapping;
    protected $signature = 'BotPro';

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
            $this->r();
        } else {
            $this->error('连接失败,请确保机器人设置完整');
        }
        //return 0;
    }
    public function r()
    {
        $run = new Bootstrap();
        \Ratchet\Client\connect($run->zxws)->then(function ($conn) {
            $conn->on('message', function ($msg) use ($conn) {
                try {
                    if (get_options_count("BOT_START")) {
                        Option::where('name', 'BOT_START')->delete();
                        $conn->close();
                        $this->info($msg . "\n\n重载\n");
                        system("php artisan BotPro");
                    }
                    $this->info($msg . "\n");
                    $this->info((new BotCore)->Run($msg) . "\n");
                } catch (\Throwable $th) {
                    $this->error($th . "\n");
                }
            });
        }, function ($e) {
            $this->error("Could not connect: {$e->getMessage()}\n");
        });
    }
}

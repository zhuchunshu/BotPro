<?php

namespace App\Console\Commands\BotPro;

use App\Models\Option;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Restart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'BotPro:stop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '停止BotPro';

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
        Option::insert([
            'name' => 'BOT_START',
            'value' => "stop",
            'created_at' => date("Y-m-d H:i:s")
        ]);
        
    }
}

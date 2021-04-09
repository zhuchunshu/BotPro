<?php

namespace App\Jobs\BotPro;

use Exception;
use App\BotPro\Bootstrap;
use App\Services\BotCore;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class RunJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $run = new Bootstrap();
        \Ratchet\Client\connect($run->zxws)->then(function($conn) {
            $conn->on('message', function($msg) use ($conn) {
                if(!get_options_count("BOT_START")){
                    $conn->close();
                }
                try {
                    (new BotCore)->Run($msg);
                } catch (\Throwable $th) {
                    throw new Exception($th);
                }
            });
        }, function ($e) {
           throw new Exception($e);
        });
    }
}

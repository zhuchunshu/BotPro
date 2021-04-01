<?php

namespace App\Jobs\BotPro;

use App\Services\BotCore;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMsg implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public $data;

    public $action;

    public $url;

    public function __construct($data,$action,$url=null)
    {
        $this->data = $data;
        $this->action = $action;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $BotCore = new BotCore();
        return $BotCore->Send($this->data,$this->action,$this->url);
    }
}

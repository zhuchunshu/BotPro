<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class BotCore extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'bot_core';
    
}

<?php

namespace App\Admin\Repositories;

use App\Models\BotCore as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class BotCore extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

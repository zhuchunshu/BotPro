<?php

namespace App\Admin\Repositories;

use App\Models\Option as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Option extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}

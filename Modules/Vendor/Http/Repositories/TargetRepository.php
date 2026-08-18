<?php

namespace Modules\Vendor\Http\Repositories;

use App\Models\Target;

class TargetRepository extends Repository
{
    public function __construct(Target $target)
    {
        // the model instance can be accessed with "$this->model" variable
        parent::__construct($target);
    }
}
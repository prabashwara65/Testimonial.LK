<?php

namespace Modules\Vendor\Http\Repositories;

use App\Models\Subproduct;

class SubproductRepository extends Repository
{
    public function __construct(Subproduct $subproduct)
    {
        // the model instance can be accessed with "$this->model" variable
        parent::__construct($subproduct);
    }
}
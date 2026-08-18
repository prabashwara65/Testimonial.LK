<?php

namespace Modules\Admin\Http\Repositories;

use App\Models\District;

class DistrictRepository extends Repository
{
    public function __construct(District $district)
    {
        // the model instance can be accessed with "$this->model" variable
        parent::__construct($district);
    }
}

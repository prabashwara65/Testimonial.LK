<?php

namespace Modules\Admin\Http\Repositories;

use App\Models\Vendor;

class VendorRepository extends Repository
{
    public function __construct(Vendor $vendor)
    {
        // the model instance can be accessed with "$this->model" variable
        parent::__construct($vendor);
    }
}
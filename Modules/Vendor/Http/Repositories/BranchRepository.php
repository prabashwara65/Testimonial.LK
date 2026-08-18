<?php

namespace Modules\Vendor\Http\Repositories;

use App\Models\Branch;

class BranchRepository extends Repository
{
    public function __construct(Branch $branch)
    {
        // the model instance can be accessed with "$this->model" variable
        parent::__construct($branch);
    }
}
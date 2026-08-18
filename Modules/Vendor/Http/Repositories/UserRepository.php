<?php

namespace Modules\Vendor\Http\Repositories;

use App\Models\Vendor;

class UserRepository extends Repository
{
    public function __construct(Vendor $user)
    {
        // the model instance can be accessed with "$this->model" variable
        parent::__construct($user);
    }
}
<?php

namespace Modules\Admin\Http\Repositories;

use App\Models\Admin;

class UserRepository extends Repository
{
    public function __construct(Admin $admin)
    {
        // the model instance can be accessed with "$this->model" variable
        parent::__construct($admin);
    }
}
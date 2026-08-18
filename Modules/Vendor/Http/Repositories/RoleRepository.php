<?php

namespace Modules\Vendor\Http\Repositories;

use Spatie\Permission\Models\Role;

class RoleRepository extends Repository
{
    public function __construct(Role $role)
    {
        // the model instance can be accessed with "$this->model" variable
        parent::__construct($role);
    }


}

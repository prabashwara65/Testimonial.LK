<?php

namespace Modules\Admin\Http\Repositories;

use Spatie\Permission\Models\Permission;

class PermissionRepository extends Repository
{
    public function __construct(Permission $permission)
    {
        // the model instance can be accessed with "$this->model" variable
        parent::__construct($permission);
    }


}

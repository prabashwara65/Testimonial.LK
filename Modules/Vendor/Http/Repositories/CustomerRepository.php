<?php

namespace Modules\Vendor\Http\Repositories;

use App\Models\User;

class CustomerRepository extends Repository
{
    public function __construct(User $customer)
    {
        // the model instance can be accessed with "$this->model" variable
        parent::__construct($customer);
    }
}
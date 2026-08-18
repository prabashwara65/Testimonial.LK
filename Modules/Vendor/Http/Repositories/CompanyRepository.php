<?php

namespace Modules\Vendor\Http\Repositories;

use App\Models\VendorCompany;

class CompanyRepository extends Repository
{
    public function __construct(VendorCompany $company)
    {
        // the model instance can be accessed with "$this->model" variable
        parent::__construct($company);
    }
}
<?php

namespace Modules\Admin\Http\Repositories;

use App\Models\VendorCompany;

class VendorCompanyRepository extends Repository
{
    public function __construct(VendorCompany $vendorCompany)
    {
        // the model instance can be accessed with "$this->model" variable
        parent::__construct($vendorCompany);
    }
}
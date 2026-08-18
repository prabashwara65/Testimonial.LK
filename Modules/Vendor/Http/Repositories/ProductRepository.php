<?php

namespace Modules\Vendor\Http\Repositories;

use App\Models\Product;

class ProductRepository extends Repository
{
    public function __construct(Product $product)
    {
        // the model instance can be accessed with "$this->model" variable
        parent::__construct($product);
    }
}
<?php

namespace Modules\Vendor\Http\Repositories;

use App\Models\Reward;

class RewardRepository extends Repository
{
    public function __construct(Reward $reward)
    {
        // the model instance can be accessed with "$this->model" variable
        parent::__construct($reward);
    }
}
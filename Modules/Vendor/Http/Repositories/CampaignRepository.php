<?php

namespace Modules\Vendor\Http\Repositories;

use App\Models\Campaign;

class CampaignRepository extends Repository
{
    public function __construct(Campaign $campaign)
    {
        // the model instance can be accessed with "$this->model" variable
        parent::__construct($campaign);
    }
}
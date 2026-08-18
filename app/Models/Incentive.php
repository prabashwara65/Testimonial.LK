<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incentive extends Model
{
    protected $guarded = [];

    public function vendor() {
        return $this->belongsTo(Vendor::class);
    }

    public function campaign() {
        return $this->belongsTo(Campaign::class);
    }
}

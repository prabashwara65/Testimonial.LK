<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $guarded = [];

    public function users() {
        return $this->belongsToMany(User::class, 'user_has_rewards')->withPivot(['vendor_company_id', 'start_date', 'end_date']);
    }
}

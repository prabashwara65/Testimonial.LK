<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    protected $guarded = [];

    public function campaigns() {
        return $this->hasMany(Campaign::class);
    }

}

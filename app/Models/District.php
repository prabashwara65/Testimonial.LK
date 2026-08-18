<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function region() {
        return $this->belongsTo(Region::class);
    }

    public function country() {
        return $this->belongsTo(Country::class);
    }

    public function province() {
        return $this->belongsTo(Province::class);
    }

    public function users() {
        return $this->hasMany(User::class);
    }

    public function branches() {
        return $this->hasMany(Branch::class);
    }

    public function vendors() {
        return $this->hasMany(Vendor::class);
    }
}

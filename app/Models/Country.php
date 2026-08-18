<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    public function region() {
        return $this->belongsTo(Region::class);
    }

    public function provinces() {
        return $this->hasMany(Province::class);
    }

    public function districts() {
        return $this->hasMany(District::class);
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorCompany extends Model
{
    protected $guarded = [];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function country() {
        return $this->belongsTo(Country::class);
    }

    public function region() {
        return $this->belongsTo(Region::class);
    }

    public function vendors()
    {
        return $this->hasMany(Vendor::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function payments()
    {
        return $this->hasMany(PaymentRenewal::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}

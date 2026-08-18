<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function company() {
        return $this->belongsTo(VendorCompany::class, 'vendor_company_id', 'id');
    }

    public function subproducts() {
        return $this->hasMany(Subproduct::class);
    }

    public function campaigns() {
        return $this->belongsToMany(Campaign::class, 'campaign_has_products');
    }

    public function testimonials() {
        return $this->hasMany(Testimonial::class);
    }
}

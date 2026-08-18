<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $guarded = [];

    public function company() {
        return $this->belongsTo(VendorCompany::class, 'vendor_company_id', 'id');
    }

    public function vendors() {
        return $this->hasMany(Vendor::class);
    }

    public function district() {
        return $this->belongsTo(District::class);
    }

    public function province() {
        return $this->belongsTo(Province::class);
    }

    public function country() {
        return $this->belongsTo(Country::class);
    }

    public function region() {
        return $this->belongsTo(Region::class);
    }

    public function campaigns() {
        return $this->belongsToMany(Campaign::class, 'campaign_has_branches');
    }

    public function testimonials() {
        return $this->hasMany(Testimonial::class);
    }
}

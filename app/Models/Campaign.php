<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $guarded = [];

    public function questionnaires() {
        return $this->hasMany(Questionnaire::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'campaign_has_products');
    }

    public function subproducts() {
        return $this->belongsToMany(Subproduct::class, 'campaign_has_subproducts');
    }

    public function target() {
        return $this->belongsTo(Target::class);
    }

    public function testimonials() {
        return $this->hasMany(Testimonial::class);
    }

    public function incentives() {
        return $this->hasMany(Incentive::class);
    }

    public function branches() {
        return $this->belongsToMany(Branch::class, 'campaign_has_branches');
    }

    public function employees() {
        return $this->belongsToMany(Vendor::class, 'campaign_has_employees');
    }

    public function responses() {
        return $this->hasMany(Response::class);
    }

    public function linkIncentives() {
        return $this->belongsToMany(Vendor::class, 'incentives')->withPivot(['vendor_company_id']);
    }
}

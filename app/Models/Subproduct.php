<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subproduct extends Model
{
    protected $guarded = [];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function campaigns() {
        return $this->belongsToMany(Campaign::class, 'campaign_has_subproducts');
    }

    public function testimonials() {
        return $this->hasMany(Testimonial::class);
    }
}

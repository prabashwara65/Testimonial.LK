<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $guarded = [];
    protected $appends = ['diff_in_days'];

    public function getDiffInDaysAttribute()
    {
        return $this->created_at->diffInDays() . ' Days';
    }

    //////////////////////////////////////////////////////

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function campaign() {
        return $this->belongsTo(Campaign::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function subproduct() {
        return $this->belongsTo(Subproduct::class);
    }

    public function responseQuestions() {
        return $this->hasMany(ResponseQuestion::class);
    }

    public function responseRecord() {
        return $this->hasOne(ResponseRecord::class);
    }

    public function user() {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function employee() {
        return $this->belongsTo(Vendor::class, 'emp_id', 'id');
    }

    public function vendorCompany() {
        return $this->belongsTo(VendorCompany::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentRenewal extends Model
{
    protected $guarded = [];

    public function company() {
        return $this->belongsTo(VendorCompany::class, 'vendor_company_id', 'id');
    }
}

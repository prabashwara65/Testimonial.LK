<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;

use App\Notifications\VendorResetPasswordNotification;

use Spatie\Permission\Traits\HasRoles;

class Vendor extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new VendorResetPasswordNotification($token));
    }

    protected $guard = 'vendors';

    protected $fillable = [
        'vendor_company_id',
        'emp_id',
        'name',
        'last_name',
        'nic',
        'email',
        'mobile',
        'address',
        'address_line1',
        'address_line2',
        'username',
        'password',
        'region_id',
        'country_id',
        'designation',
        'department',
        'branch_id',
        'incentive_cal',
        'incentive_rate',
        'bank_account',
        'bank',
        'bank_branch',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function country() {
        return $this->belongsTo(Country::class);
    }

    public function region() {
        return $this->belongsTo(Region::class);
    }

    public function vendorCompany() {
        return $this->belongsTo(VendorCompany::class);
    }

    public function responses() {
        return $this->hasMany(Response::class);
    }

    public function incentives() {
        return $this->hasMany(Incentive::class);
    }

    public function campaigns() {
        return $this->belongsToMany(Campaign::class, 'campaign_has_employees');
    }

    public function linkIncentives() {
        return $this->belongsToMany(Campaign::class, 'incentives')->withPivot(['vendor_company_id']);
    }
}

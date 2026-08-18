<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Notifications\AdminResetPasswordNotification;

use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */

     public function sendPasswordResetNotification($token)
     {
         $this->notify(new AdminResetPasswordNotification($token));
     }

    protected $guard = 'admins';
    
    protected $fillable = [
        'name',
        'last_name',
        'emp_id',
        'username',
        'password',
        'nic',
        'mobile',
        'region_id',
        'country_id',
        'email',
        'address',
        'address_line1',
        'address_line2',
        'department',
        'designation',
        'vendor_company_id',
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

    public function country() {
        return $this->belongsTo(Country::class);
    }

    public function region() {
        return $this->belongsTo(Region::class);
    }

    public function campaigns() {
        return $this->belongsToMany(Campaign::class, 'campaign_has_employees');
    }
}

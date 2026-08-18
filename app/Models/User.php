<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Spatie\Permission\Traits\HasRoles;

use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
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
        'otp_code',
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

    public function rewards() {
        return $this->belongsToMany(Reward::class, 'user_has_rewards')->withPivot(['vendor_company_id', 'start_date', 'end_date']);
    }

    public function responses() {
        return $this->hasMany(Response::class, 'customer_id');
    }

    /**
     * Appends.
     *
     * @var array
     */

    protected $appends = ['testimonial_count', 'feedback_count', 'reward_count'];

    public function getTestimonialCountAttribute()
    {
        return Response::where('vendor_company_id', auth()->user()->vendor_company_id)
                ->where('customer_id', $this->id)
                ->where('type', 1)
                ->count();
    }

    public function getFeedbackCountAttribute()
    {
        return Response::where('vendor_company_id', auth()->user()->vendor_company_id)
                ->where('customer_id', $this->id)
                ->where('type', 2)
                ->count();
    }

    public function getRewardCountAttribute()
    {
        return DB::table('user_has_rewards')
                ->where('vendor_company_id', auth()->user()->vendor_company_id)
                ->where('user_id', $this->id)
                ->count();
    }
}

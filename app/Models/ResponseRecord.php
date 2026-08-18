<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResponseRecord extends Model
{
    protected $guarded = [];
    
    public function response() {
        return $this->belongsTo(Response::class);
    }
}

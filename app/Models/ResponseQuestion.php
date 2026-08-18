<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResponseQuestion extends Model
{
    protected $guarded = [];
    
    public function response() {
        return $this->belongsTo(Response::class);
    }

    public function question() {
        return $this->belongsTo(Question::class);
    }
}

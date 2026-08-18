<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded = [];

    public function type() {
        return $this->belongsTo(QuestionType::class);
    }

    public function questionnaire() {
        return $this->belongsTo(Questionnaire::class);
    }

    public function answers() {
        return $this->hasMany(QuestionAnswer::class);
    }

    public function responseQuestions() {
        return $this->hasMany(ResponseQuestion::class);
    }
}

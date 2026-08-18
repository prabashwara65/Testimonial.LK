<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionAnswer extends Model
{
    protected $guarded = [];

    public function question() {
        return $this->belongsTo(Question::class);
    }

    public function subQuestion() {
        return $this->belongsTo(Question::class,'sub_questionnaire_question_id', 'id');
    }
}

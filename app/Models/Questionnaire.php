<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questionnaire extends Model
{
    protected $guarded = [];
    protected $hidden = ['pivot'];

    public function campaign() {
        return $this->belongsTo(Campaign::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function originQuestions() {
        return $this->hasMany(Question::class)->where('survey_question',null)->where('sub_question',null);
    }

    public function surveyQuestions() {
        return $this->hasMany(Question::class)->where('survey_question','Y')->where('sub_question',null);
    }

    public function subQuestions() {
        return $this->hasMany(Question::class)->where('sub_question','Y');
    }

    public function user() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function brands() {
        return $this->belongsToMany(Brand::class, 'brand_has_questionnaires');
    }

    /* NEW REQUIREMENT V2 (Multi Campaign)*/
    // Brand wise BA Target
    public function baTargets() {
        return $this->belongsToMany(Target::class, 'target_has_questionnaires', 'ba_questionnaire_id', 'target_id');
    }

    // Brand wise Caller Target
    public function callerTargets() {
        return $this->belongsToMany(Target::class, 'target_has_questionnaires', 'caller_questionnaire_id', 'target_id');
    }

}

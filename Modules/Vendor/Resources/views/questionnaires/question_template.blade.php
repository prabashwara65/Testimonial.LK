@php
    $isEdit = isset($question);
@endphp
<div id="question{{$count}}">
    <div class="row mt-2">
        <div class='col-lg-12'>
            <div class="form-group row">
                <label for="question_type{{$count}}" class="col-sm-2 col-form-label">Question Type</label>
                <div class="col-sm-4">
                    <select name="questions[{{$count}}][question_type]" id="question_type" data-count="{{$count}}" class="form-control">
                        @foreach ($question_types as $type)
                            <option value="{{$type->id}}" @if($isEdit && $question->type_id == $type->id) selected @endif >{{$type->type}}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback questions.{{$count}}.question_type-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row survey_row">
                <div class="col-sm-4 offset-sm-2">
                    <div class="checkbox checkbox-circle checkbox-info peers ai-c" >
                        <input type="checkbox" id="required_needed{{$count}}" name="questions[{{$count}}][required_needed]" class="peer" @if($isEdit && $question->required_needed == '1') checked @endif>
                        <label for="required_needed{{$count}}" class=" peers peer-greed js-sb ai-c">
                            <span class="peer peer-greed">If Required</span>
                        </label>
                    </div>
                    <span class="invalid-feedback questions.{{$count}}.required_needed-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="question{{$count}}" class="col-sm-2 col-form-label">Question <br> @if($isEdit) (No {{$question->id}}) @endif</label>
                <div class="col-sm-10">
                    <textarea name="questions[{{$count}}][question]" id="question{{$count}}" style="width: 100%" rows="3" class="form-control">@if($isEdit) {{$question->question}} @endif</textarea>
                    <span class="invalid-feedback questions.{{$count}}.question-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row star-count-section-{{$count}}" style="display: @if($isEdit && $question->type_id == 4) flex @else none @endif;">
                <label for="question{{$count}}" class="col-sm-2 col-form-label">Stars Count</label>
                <div class="col-sm-4">
                    <input type="number" class="form-control" id="questions[{{$count}}][star_count]" name="questions[{{$count}}][star_count]" value="@if($isEdit && $question->type_id == 4){{$question->answers[0]->value}}@endif">
                    <span class="invalid-feedback questions.{{$count}}.star_count-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row answer-section-{{$count}}" style="display: @if($isEdit && in_array($question->type_id, [2, 3])) flex @else none @endif;">
                <label for="question{{$count}}" class="col-sm-2 col-form-label">Answers</label>
                <div class="col-sm-10 answers-wrapper">
                    <!-- Answer 1 -->

                    <div class="row answer">
                        <div class="col-sm-6 pr-0 mb-2">
                            <input type="text" class="form-control" name="questions[{{$count}}][answers][@if($isEdit && in_array($question->type_id, [2, 3])){{$question->answers[0]->id}}@endif]" value="@if($isEdit && in_array($question->type_id, [2, 3])){{$question->answers[0]->value}}@endif">
                            <span class="invalid-feedback questions.{{$count}}.answers.0-error" role="alert"></span>
                        </div>
                        <div class="col-sm-4 pr-0 mb-2">
                            @if($isEdit && in_array($question->type_id, [2, 3]))
                                <input type="text" class="form-control" placeholder="Sub Question No" name="questions[{{$count}}][subQuestion][{{$question->answers[0]->id}}]" value="{{$question->answers[0]->sub_questionnaire_question_id}}"/>
                                <span class="invalid-feedback questions.{{$count}}.subQuestion.{{$question->answers[0]->id}}-error" role="alert"></span>
                            @endif

                            <div class="pr-0 subQuestion-section-{{$count}}" style="display:none">
                                <input type="number" class="form-control" placeholder="Sub Question No" name="questions[{{$count}}][subQuestion][]" />
                                <span class="invalid-feedback questions.{{$count}}.subQuestion.0-error" role="alert"></span>
                            </div>
                        </div>
                        <div class="col-sm-2 mb-2 remove-answer-btn-wrapper pl-0 d-none">
                            <button type="button" class="remove-answer-btn btn btn-sm btn-default text-danger"><i class="fa fa-times"></i> Remove</button>
                        </div>
                    </div>

                    <!-- Answer 2 -->
                    <div class="row answer">
                        <div class="col-sm-6 pr-0 mb-2">
                            <input type="text" class="form-control" name="questions[{{$count}}][answers][@if($isEdit && in_array($question->type_id, [2, 3])){{$question->answers[1]->id}}@endif]" value="@if($isEdit && in_array($question->type_id, [2, 3])){{$question->answers[1]->value}}@endif">
                            <span class="invalid-feedback questions.{{$count}}.answers.1-error" role="alert"></span>
                        </div>
                        <div class="col-sm-4 pr-0 mb-2">
                            @if($isEdit && in_array($question->type_id, [2, 3]))
                                <input type="text" class="form-control" placeholder="Sub Question No" name="questions[{{$count}}][subQuestion][{{$question->answers[1]->id}}]" value="{{$question->answers[1]->sub_questionnaire_question_id}}" />
                                <span class="invalid-feedback questions.{{$count}}.subQuestion.{{$question->answers[1]->id}}-error" role="alert"></span>
                            @endif

                            <div class="pr-0 subQuestion-section-{{$count}}" style="display:none">
                                <input type="number" class="form-control" placeholder="Sub Question No" name="questions[{{$count}}][subQuestion][]" />
                                <span class="invalid-feedback questions.{{$count}}.subQuestion.1-error" role="alert"></span>
                            </div>
                        </div>
                    </div>

                    @if($isEdit && count($question->answers) > 2 && in_array($question->type_id, [2, 3]) )
                        @for($i = 2; $i < count($question->answers); $i++)
                            <div class="row answer">
                                <div class="col-sm-6 pr-0 mb-2">
                                    <input type="text" class="form-control" name="questions[{{$count}}][answers][{{$question->answers[$i]->id}}]" value="{{$question->answers[$i]->value}}">
                                    <span class="invalid-feedback questions.{{$count}}.answers.{{$question->answers[$i]->value}}-error" role="alert"></span>
                                </div>
                                <div class="col-sm-4 pr-0 mb-2">
                                    @if($isEdit) <input type="text" class="form-control" placeholder="Sub Question No" name="questions[{{$count}}][subQuestion][@if($isEdit && in_array($question->type_id, [2, 3])){{$question->answers[$i]->id}}@endif]" value="@if($isEdit && in_array($question->type_id, [2, 3])){{$question->answers[$i]->sub_questionnaire_question_id}}@endif" /> @endif
                                        <span class="invalid-feedback questions.{{$count}}.subQuestion.{{$question->answers[$i]->id}}-error" role="alert"></span>
                                </div>
                                <div class="col-sm-2 mb-2 remove-answer-btn-wrapper pl-md-1">
                                    <button type="button" class="remove-answer-btn btn btn-sm btn-default text-danger"><i class="fa fa-times"></i> Remove</button>
                                </div>
                            </div>
                        @endfor
                    @endif
                </div>
                <div class="col-md-8 offset-md-2">
                    <button data-question="{{$count}}" class="btn btn-outline-primary btn-sm add-answer-btn" type="button"><i class="fa fa-plus"></i> Add Answer</button>
                </div>
            </div>
        </div>
    </div>
    @if(!$isEdit)
    <div class="row">
        <div class="col-md-3 offset-md-9">
            <p class="text-right">
                <button type="button" data-count="{{$count}}" class="remove-question-btn btn btn-sm btn-outline-danger pull-right"><i class="fa fa-minus"></i> Remove Question</button>
            </p>
        </div>
    </div>
    @endif
    <hr>
</div>

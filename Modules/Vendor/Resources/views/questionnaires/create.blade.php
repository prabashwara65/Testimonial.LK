{{ Form::open(array('url' => route('vendor.questionnaires.store'), 'class'=>'text-form', 'files'=>'true', 'onsubmit' => 'return false;')) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Add New Questionnaire</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class='col-lg-12 offset-lg-0'>
        <div class="form-group row">
            <label for="name" class="col-sm-2 col-form-label">Name <span class="required-star"></span></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" >
                <span class="invalid-feedback name-error" role="alert"></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="campaign_id" class="col-sm-2 col-form-label">Campaign Name <span class="required-star"></span></label>
            <div class="col-sm-4">
                <select name="campaign_id" id="campaign_id" class="form-control">
                    @foreach ($campaigns as $campaign)
                        <option value="{{$campaign->id}}" >{{$campaign->campaign_name}}</option>
                    @endforeach
                </select>
                <span class="invalid-feedback type-error" role="alert"></span>
            </div>

            <label for="status" class="col-sm-2 col-form-label">Status <span class="required-star"></span></label>
            <div class="col-sm-4">
                <select name="status" id="status" class="form-control" style="width: 100%">
                    <option value="1" >Active</option>
                    <option value="0" >Inactive</option>
                </select>
                <span class="invalid-feedback status-error" role="alert"></span>
            </div>
        </div>

        <small>
            Note
            <hr>
            Please follow below instructions for report generation purposes.
            <ul>
                {{--<li>Include "regular brand" phrase in questions regarding consumer's favourite/preferred brand</li>--}}
                <li>Include "daily consumption" phrase in questions regarding the daily usage amount</li>
                <li>Add only one question with each phrase</li>
                <li>Copy and paste the phrases to avoid typing mistakes</li>
                <li>Only letters, numbers, and this symbol are <b>(dot, comma, &)</b> allowed </li>
            </ul>

        </small>

        <hr>

        <div class="question-holder"></div>

        <input type="hidden" class="question-count-holder" name="question_count" value="0">
        <button class="btn btn-outline-primary btn-sm add-question-btn" type="button" data-url="{{route('vendor.questionnaires.get-question-template', '')}}"><i class="fa fa-plus"></i> Add a Question</button>

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
    {{ Form::submit('Save', array('class' => 'text-form-submit btn btn-primary')) }}
</div>
{{ Form::close() }}

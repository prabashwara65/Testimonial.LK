{{ Form::open(array('url' => route('vendor.campaigns.store'), 'class'=>'text-form', 'onsubmit' => 'return false;')) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Add New Campaign</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="col-lg-12 offset-lg-0">
        <div class="form-group row">
            <label for="campaign_name" class="col-sm-2 col-form-label">Campaign Name<i class="required-star"></i></label>
            <div class="col-sm-4">
                <input type="text" class="form-control" id="campaign_name" name="campaign_name" >
                <span class="invalid-feedback campaign_name-error" role="alert"></span>
            </div>

            <label for="campaign_type" class="col-sm-2 col-form-label">Campaign Type<i class="required-star"></i></label>
            <div class="col-sm-4">
                <select name="campaign_type" id="campaign_type" class="form-control select2" style="width: 100%" data-live-search="true">
                    <option value="Single" >Single</option>
                    <option value="Multiple" >Multiple</option>
                </select>
                <span class="invalid-feedback campaign_type-error" role="alert"></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="target_id" class="col-sm-2 col-form-label">Target Type<i class="required-star"></i></label>
            <div class="col-sm-4">
                <select name="target_id" id="target_id" class="form-control select2" style="width: 100%" data-live-search="true">
                    @foreach ($targets as $target)
                        <option value="{{$target['id']}}" >{{ucfirst($target['target_name'])}}</option>
                    @endforeach
                </select>
                <span class="invalid-feedback target_id-error" role="alert"></span>
            </div>

            <label for="response_type" class="col-sm-2 col-form-label">Question Type<i class="required-star"></i></label>
            <div class="col-sm-4">
                <select name="response_type" id="response_type" class="form-control select2" style="width: 100%" data-live-search="true">
                    <option value="Questionnaire" >Questionnaire</option>
                    <option value="Record" >Record</option>
                    <option value="Both" >Both</option>
                </select>
                <span class="invalid-feedback response_type-error" role="alert"></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="branches" class="col-sm-2 col-form-label">Select Branch<i class="required-star"></i></label>
            <div class="col-sm-4">
                <select name="branches[]" id="branches" class="form-control select2 load-data-on-change" data-url="{{$loadEmployeeUrl}}" data-target="#employees" style="width: 100%" multiple data-live-search="true">
                    @foreach ($branches as $branch)
                        <option value="{{$branch['id']}}" >{{$branch['name']}}</option>
                    @endforeach
                </select>
                <span class="invalid-feedback branches-error" role="alert"></span>
            </div>

            <label for="employees" class="col-sm-2 col-form-label">Employee<i class="required-star"></i></label>
            <div class="col-sm-4">
                <select name="employees[]" id="employees" class="form-control select2" style="width: 100%" multiple data-live-search="true">
                </select>
                <span class="invalid-feedback employees-error" role="alert"></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="incentive" class="col-sm-2 col-form-label">Incentive Rate<i class="required-star"></i></label>
            <div class="col-sm-4">
                <input type="text" name="incentive" id="incentive" class="form-control">
                <span class="invalid-feedback incentive-error" role="alert"></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="start_date" class="col-sm-2 col-form-label">Start Date<i class="required-star"></i></label>
            <div class="col-sm-4">
                <input type="text" name="start_date" id="start_date" class="form-control datepicker" data-date-format="yyyy-mm-dd">
                <span class="invalid-feedback start_date-error" role="alert"></span>
            </div>

            <label for="end_date" class="col-sm-2 col-form-label">End Date<i class="required-star"></i></label>
            <div class="col-sm-4">
                <input type="text" name="end_date" id="end_date" class="form-control datepicker" data-date-format="yyyy-mm-dd">
                <span class="invalid-feedback end_date-error" role="alert"></span>
            </div>
        </div>

        <hr>

        <h6 class="modal-title c-grey-900 ">Add Products</h6>
        <hr>
        <div class="form-group row campaign-header">
            <label for="brand_id" class="col-sm-6 col-form-label text-center">Products <span class="required-star"></span></label>
            <label for="brand_id" class="col-sm-6 col-form-label text-center">Subproducts <span class="required-star"></span></label>
        </div>

        <div class="campaign-holder"></div>

        <div class="form-group row">
            <input type="hidden" class="campaign-count-holder" name="question_count" value="0">
            <button class="btn btn-outline-primary btn-sm add-new-btn" type="button" data-url="{{route('vendor.campaigns.get-campaign-template', '')}}"><i class="fa fa-plus"></i> Add a New Product</button>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
    {{ Form::submit('Save', array('class' => 'text-form-submit btn btn-primary')) }}
</div>
{{ Form::close() }}

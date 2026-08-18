{{ Form::open(array('url' => route('vendor.countries.store'), 'class'=>'text-form', 'onsubmit' => 'return false;')) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Add New Country</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class='col-lg-8 offset-lg-2'>
        <div class="form-group row">
            <label for="region_id" class="col-sm-2 col-form-label">Region <i
                    class="required-star"></i></label>
            <div class="col-sm-10">
                <select name="region_id" id="region_id" class="form-control" data-live-search="true">
                    @foreach ($regions as $region)
                    <option value="{{ $region->id }}">{{ $region->region }}</option>
                    @endforeach
                </select>
                <span class="invalid-feedback role-error" role="alert"></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="country" class="col-sm-2 col-form-label">Country<i class="required-star"></i></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="country" name="country" >
                <span class="invalid-feedback country-error" role="alert"></span>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
    {{ Form::submit('Save', array('class' => 'text-form-submit btn btn-primary')) }}
</div>
{{ Form::close() }}
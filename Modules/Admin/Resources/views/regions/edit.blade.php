{{ Form::open(array('url' => route('admin.regions.update', $region['id']), 'class'=>'text-form', 'method' => 'put', 'onsubmit' => 'return false;')) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Edit Region</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class='col-lg-8 offset-lg-2'>
        <div class="form-group row">
            <label for="region" class="col-sm-2 col-form-label">Region<i class="required-star"></i></label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="region" name="region" value="{{$region['region']}}">
                <span class="invalid-feedback region-error" role="alert"></span>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
    {{ Form::submit('Save', array('class' => 'text-form-submit btn btn-primary')) }}
</div>
{{ Form::close() }}
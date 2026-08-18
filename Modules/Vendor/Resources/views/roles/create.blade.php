{{ Form::open(array('url' => route('vendor.roles.store'), 'class'=>'text-form', 'onsubmit' => 'return false;')) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Add New Role</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class='col-lg-10 offset-lg-1'>
        <div class="form-group row">
            <label for="name" class="col-sm-3 col-form-label">Name <i class="required-star"></i></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="name" name="name" >
                <span class="invalid-feedback name-error" role="alert"></span>
            </div>
        </div>

        <b>Assign Role to Permissions</b>

        <div class="form-group row">
            <label for="permissions" class="col-sm-3 col-form-label">Permissions <i class="required-star"></i></label>
            <div class="col-sm-9">
                <select name="permissions[]" id="permissions" class="form-control" multiple data-live-search="true">
                    @foreach ($permissions as $permission)
                        <option value="{{$permission->id}}" >{{ucfirst($permission->name)}}</option>
                    @endforeach
                </select>
                <span class="invalid-feedback permissions-error" role="alert"></span>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
    {{ Form::submit('Save', array('class' => 'text-form-submit btn btn-primary')) }}
</div>
{{ Form::close() }}
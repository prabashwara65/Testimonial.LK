{{ Form::open(array('url' => route('admin.permissions.update', $permission['id']), 'class'=>'text-form', 'method' => 'put', 'onsubmit' => 'return false;')) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Edit '{{$permission['name']}}' Permission</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class='col-lg-8 offset-lg-2'>
        <div class="form-group row">
            <label for="name" class="col-md-4 col-form-label">Name<i class="required-star"></i></label>
            <div class="col-md-8">
                <input type="text" class="form-control" id="name" name="name" value="{{$permission['name']}}">
                <span class="invalid-feedback name-error" role="alert"></span>
            </div>
        </div>

        <div class="form-group row">
            <label for="guard_name" class="col-md-4 col-form-label">Guard Name<i class="required-star"></i></label>
            <div class="col-md-8">
                <select name="guard_name" id="guard_name" class="form-control">
                    <option value="admin" @if($permission['guard_name'] == "admin") selected @endif>admin</option>
                    <option value="vendor" @if($permission['guard_name'] == "vendor") selected @endif>vendor</option>
                    <option value="user" @if($permission['guard_name'] == "user") selected @endif>user</option>
                    <option value="web" @if($permission['guard_name'] == "web") selected @endif>web</option>
                    <option value="api" @if($permission['guard_name'] == "api") selected @endif>api</option>
                </select>
                <span class="invalid-feedback guard_name-error" role="alert"></span>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
    {{ Form::submit('Update', array('class' => 'text-form-submit btn btn-primary')) }}
</div>
{{ Form::close() }}

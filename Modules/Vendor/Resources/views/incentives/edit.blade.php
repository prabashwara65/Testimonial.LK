{{ Form::open(array('url' => route('vendor.incentives.update', $incentive), 'class'=>'text-form', 'method' => 'put', 'onsubmit' => 'return false;')) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Mark as Paid/Reject</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="col-lg-10 offset-lg-1">
        <div class="form-group row">
            <label for="status" class="col-sm-4 col-form-label">Status <i
                    class="required-star"></i></label>
            <div class="col-sm-8">
                <select name="status" id="status" class="form-control" data-live-search="true">
                    <option value="Paid">Paid</option>
                    <option value="Reject">Reject</option>
                </select>
                <span class="invalid-feedback status-error" role="alert"></span>
            </div>
        </div>
    </div>

    <div class="col-lg-10 offset-lg-1">
        <div class="form-group row">
            <label for="date" class="col-sm-4 col-form-label">Date <i
                    class="required-star"></i></label>
            <div class="col-sm-8">
                <input type="text" name="date" id="date" class="form-control datepicker" data-date-format="yyyy-mm-dd" value="{{date("Y-m-d")}}">
                <span class="invalid-feedback date-error" role="alert"></span>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
    {{ Form::submit('Save', array('class' => 'text-form-submit btn btn-primary')) }}
</div>
{{ Form::close() }}

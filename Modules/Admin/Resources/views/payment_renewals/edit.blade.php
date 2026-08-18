{{ Form::open(array('url' => route('admin.payment-renewals.update', $incentive), 'class'=>'text-form', 'method' => 'put', 'onsubmit' => 'return false;')) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Mark as Paid</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="col-lg-10 offset-lg-1">
        <div class="form-group row">
            <label for="status" class="col-sm-4 col-form-label">Paid Date <i
                    class="required-star"></i></label>
            <div class="col-sm-8">
                <input type="text" name="paid_date" id="paid_date" class="form-control datepicker" data-date-format="yyyy-mm-dd" value="{{date("Y-m-d")}}">
                <span class="invalid-feedback paid_date-error" role="alert"></span>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
    {{ Form::submit('Paid', array('class' => 'text-form-submit btn btn-primary')) }}
</div>
{{ Form::close() }}
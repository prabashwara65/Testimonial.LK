{{ Form::open(array('url' => route('vendor.customers.storereward', $id), 'class'=>'text-form', 'onsubmit' => 'return false;')) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Assign Reward</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="col-lg-10 offset-lg-1">
        <div class="form-group row">
            <label for="reward_type" class="col-sm-3 col-form-label">Reward Type <i class="required-star"></i></label>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-4">
                        <input type="radio" class="reward_type" id="reward_type" name="reward_type" value="discount" checked />
                        <label for="reward_type">Discount</label>
                    </div>
                    <div class="col-sm-4">
                        <input type="radio" class="reward_type" id="reward_type" name="reward_type" value="gift"/>
                        <label for="reward_type">Gift</label>
                    </div>
                </div>
            </div>
        </div>
        <div id="discount-panel">
            <div class="form-group row">
                <label for="discount" class="col-sm-3 col-form-label">Discount <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <select name="discount" id="discount" class="form-control reward" data-live-search="true">
                        <option></option>
                        @foreach ($rewards->where('reward_type','discount') as $discount)
                        <option value="{{ $discount->id }}" data-end-date="{{$discount->date}}">{{ $discount->reward_code }} - ({{ $discount->discount }})</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback discount-error" role="alert"></span>
                </div>
            </div>
        </div>
        <div id="gift-panel" style="display: none;">
            <div class="form-group row">
                <label for="gift" class="col-sm-3 col-form-label">Gift <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <select name="gift" id="gift" class="form-control reward" data-live-search="true">
                        <option></option>
                        @foreach ($rewards->where('reward_type','gift') as $gift)
                        <option value="{{ $gift->id }}" data-end-date="{{$gift->date}}">{{ $gift->reward_code }} - ({{ $gift->gift }})</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback gift-error" role="alert"></span>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="start_date" class="col-sm-3 col-form-label">Start Date <i class="required-star"></i></label>
            <div class="col-sm-9">
                <input type="text" name="start_date" id="start_date" class="form-control datepicker" data-date-format="yyyy-mm-dd" value="{{date("Y-m-d")}}">
                <span class="invalid-feedback form.start_date-error" role="alert"></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="end_date" class="col-sm-3 col-form-label">End Date <i class="required-star"></i></label>
            <div class="col-sm-9">
                <input type="text" name="end_date" id="end_date" class="form-control datepicker" data-date-format="yyyy-mm-dd" value="{{date("Y-m-d")}}">
                <span class="invalid-feedback form.end_date-error" role="alert"></span>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
    {{ Form::submit('Add', array('class' => 'text-form-submit btn btn-primary')) }}
</div>
{{ Form::close() }}

<script>
    $(document).on('change', '.reward', function (e){
        const endDate = $(this).find(':selected').data('end-date');

        $('.datepicker').datepicker('destroy').datepicker({ endDate: new Date(endDate) });
    });
</script>

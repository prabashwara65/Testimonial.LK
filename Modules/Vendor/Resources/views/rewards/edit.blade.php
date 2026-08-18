{{ Form::open(array('url' => route('vendor.rewards.update', $reward['id']), 'class'=>'text-form', 'method' => 'put', 'onsubmit' => 'return false;')) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Edit Reward</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class='col-lg-10 offset-lg-1'>
        <div class="form-group row">
            <label for="date" class="col-sm-3 col-form-label">Exp. Date <i class="required-star"></i></label>
            <div class="col-sm-9">
                <input type="text" name="date" id="date" class="form-control datepicker" data-date-format="yyyy-mm-dd" value="{{!empty($reward['date'])? $reward['date']: ''}}">
                <span class="invalid-feedback date-error" role="alert"></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="reward_code" class="col-sm-3 col-form-label">Reward ID <i class="required-star"></i></label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="reward_code" name="reward_code" value="{{!empty($reward['reward_code'])? $reward['reward_code']: ''}}">
                <span class="invalid-feedback reward_code-error" role="alert"></span>
            </div>
        </div>
        <div class="form-group row">
            <label for="reward_type" class="col-sm-3 col-form-label">Reward Type <i class="required-star"></i></label>
            <div class="col-sm-9">
                <div class="row">
                    <div class="col-sm-4">
                        <input type="radio" class="reward_type" id="reward_type" name="reward_type" value="discount" @if($reward['reward_type'] == 'discount') checked @endif />
                        <label for="reward_type">Discount</label>
                    </div>
                    <div class="col-sm-4">
                        <input type="radio" class="reward_type" id="reward_type" name="reward_type" value="gift" @if($reward['reward_type'] == 'gift') checked @endif />
                        <label for="reward_type">Gift</label>
                    </div>
                </div>
            </div>
        </div>
        <div id="discount-panel" @if($reward['reward_type'] == 'gift') style="display: none;" @endif>
            <div class="form-group row">
                <label for="discount" class="col-sm-3 col-form-label">Discount (Price/Percentage) <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="discount" name="discount" value="{{!empty($reward['discount'])? $reward['discount']: ''}}">
                    <span class="invalid-feedback discount-error" role="alert"></span>
                </div>
            </div>
        </div>
        <div id="gift-panel" @if($reward['reward_type'] == 'discount') style="display: none;" @endif>
            <div class="form-group row">
                <label for="gift" class="col-sm-3 col-form-label">Gift <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="gift" name="gift" value="{{!empty($reward['gift'])? $reward['gift']: ''}}">
                    <span class="invalid-feedback gift-error" role="alert"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
    {{ Form::submit('Update', array('class' => 'text-form-submit btn btn-primary')) }}
</div>
{{ Form::close() }}

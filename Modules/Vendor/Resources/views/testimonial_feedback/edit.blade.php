{{ Form::open(['url' => route('vendor.testimonial-feedback.update', $testimonial['id']), 'class' => 'text-form', 'method' => 'put', 'onsubmit' => 'return false;']) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Change Status</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="col-lg-10 offset-lg-1">
        <div class="form-group row">
            <label for="status" class="col-sm-4 col-form-label">Status <i class="required-star"></i></label>
            <div class="col-sm-8">
                <select name="status" id="status" class="form-control" data-live-search="true">
                    <option value="pending" @if ($testimonial['status'] == 'pending') selected @endif>Pending</option>
                    <option value="approved" @if ($testimonial['status'] == 'approved') selected @endif>Approved</option>
                    <option value="reject" @if ($testimonial['status'] == 'reject') selected @endif>Reject</option>
                </select>
                <span class="invalid-feedback status-error" role="alert"></span>
            </div>
        </div>
    </div>
    <div id="reject-panel" class='col-lg-10 offset-lg-1'
        @if ($testimonial['status'] != 'reject') style="display: none;" @endif>
        <div class="form-group row">
            <label for="reject_reason" class="col-sm-4 col-form-label">Add Reject Reason </label>
            <div class="col-sm-8">
                <textarea class="form-control" id="reject_reason" name="reject_reason">{{ $testimonial['reject_reason'] }}</textarea>
                <span class="invalid-feedback reject_reason-error" role="alert"></span>
            </div>
        </div>
    </div>
    <div id="star-panel" class='col-lg-10 offset-lg-1' @if ($testimonial['status'] != 'approved') style="display: none;" @endif>
        <div class="form-group row">
            <label for="rating" class="col-sm-4 col-form-label">Add rating<i class="required-star"></i></label>
            <div class="col-sm-8">
                <div class="rate">
                    @for ($i = config('settings.rating-score'); $i > 0; $i--)
                        <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                            @if ($testimonial['rating'] == $i) checked @endif />
                        <label class="star" for="star{{ $i }}" title="{{ config('settings.star-' . $i) }}"
                            data-toggle="tooltip" data-placement="top">{{ $i }} star</label>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
    {{ Form::submit('Save', ['class' => 'text-form-submit btn btn-primary']) }}
</div>
{{ Form::close() }}

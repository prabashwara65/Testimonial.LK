@foreach ($rewards as $reward)
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Details of Reward : ID #{{ $reward->id }}</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="col-lg-10 offset-lg-1">
        <div class="form-group row">
            <label for="start_date" class="col-sm-3 col-form-label">Start Date <i class="required-star"></i></label>
            <div class="col-sm-9">
                <input type="text" name="start_date" id="start_date" class="form-control datepicker" data-date-format="yyyy-mm-dd" value="{{ $reward->pivot->start_date }}" disabled>
            </div>
        </div>
        <div class="form-group row">
            <label for="end_date" class="col-sm-3 col-form-label">End Date <i class="required-star"></i></label>
            <div class="col-sm-9">
                <input type="text" name="end_date" id="end_date" class="form-control datepicker" data-date-format="yyyy-mm-dd" value="{{ $reward->pivot->end_date }}" disabled>
            </div>
        </div>
        <div id="discount-panel">
            <div class="form-group row">
                <label for="discount" class="col-sm-3 col-form-label">@if($reward->reward_type == 'discount') Discount @else Gift @endif <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" name="end_date" id="end_date" class="form-control datepicker" data-date-format="yyyy-mm-dd" value="@if($reward->reward_type == 'discount') {{ $reward->discount }} @else {{ $reward->gift }} @endif" disabled>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<div class="modal-footer">
    {{ $rewards->links() }}

    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
</div>

{{ Form::open(array('url' => route('vendor.branches.store'), 'class'=>'text-form', 'onsubmit' => 'return false;')) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Add New Branch</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="branch_code" class="col-sm-3 col-form-label">Branch ID <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="branch_code" name="branch_code">
                    <span class="invalid-feedback branch_code-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="name" class="col-sm-3 col-form-label">Branch Name <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="name" name="name">
                    <span class="invalid-feedback name-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-sm-3 col-form-label">Email </label>
                <div class="col-sm-9">
                    <input type="email" class="form-control" id="email" name="email">
                    <span class="invalid-feedback email-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="contact_no" class="col-sm-3 col-form-label">Contact No <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="contact_no" name="contact_no">
                    <span class="invalid-feedback contact_no-error" role="alert"></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="address" class="col-sm-3 col-form-label">Address <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="address" name="address">
                    <span class="invalid-feedback address-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="address_line1" class="col-sm-3 col-form-label">Address Line 1 </label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="address_line1" name="address_line1">
                    <span class="invalid-feedback address_line1-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="address_line2" class="col-sm-3 col-form-label">Address Line 2 </label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="address_line2" name="address_line2">
                    <span class="invalid-feedback address_line2-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="region_id" class="col-sm-3 col-form-label">Region <i
                        class="required-star"></i></label>
                <div class="col-sm-9">
                    <select name="region_id" id="region_id" class="form-control load-data-on-change" data-url="{{ route('vendor.branches.load-limit-countries') }}" data-target="#country_id" data-live-search="true">
                        <option hidden></option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->region }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback region_id-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="country_id" class="col-sm-3 col-form-label">Country <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <select name="country_id" id="country_id" class="form-control load-data-on-change" data-url="{{ route('vendor.branches.load-limit-provinces') }}" data-target="#province_id" data-live-search="true">
                    </select>
                    <span class="invalid-feedback country_id-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="province_id" class="col-sm-3 col-form-label">Province <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <select name="province_id" id="province_id" class="form-control load-data-on-change" data-url="{{ route('vendor.branches.load-limit-districts') }}" data-target="#district_id" data-live-search="true">
                    </select>
                    <span class="invalid-feedback province_id-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="district_id" class="col-sm-3 col-form-label">District <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <select name="district_id" id="district_id" class="form-control" data-live-search="true">
                    </select>
                    <span class="invalid-feedback district_id-error" role="alert"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
    {{ Form::submit('Save', array('class' => 'text-form-submit btn btn-primary')) }}
</div>
{{ Form::close() }}

{{ Form::open(array('url' => route('vendor.vendors.store'), 'class'=>'text-form', 'files' => 'true', 'onsubmit' => 'return false;')) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Add New Vendor</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="company_name" class="col-sm-3 col-form-label">Company Name <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="company_name" name="company_name">
                    <span class="invalid-feedback company_name-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="br_no" class="col-sm-3 col-form-label">BR Number <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="br_no" name="br_no">
                    <span class="invalid-feedback br_no-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-sm-3 col-form-label">Email <i class="required-star"></i></label>
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
                <label for="address_line1" class="col-sm-3 col-form-label">Address Line 1 <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="address_line1" name="address_line1">
                    <span class="invalid-feedback address_line1-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="address_line2" class="col-sm-3 col-form-label">Address Line 2 <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="address_line2" name="address_line2">
                    <span class="invalid-feedback address_line2-error" role="alert"></span>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="region_id" class="col-sm-4 col-form-label">Region <i
                                class="required-star"></i></label>
                        <div class="col-sm-8">
                            <select name="region_id" id="region_id" class="form-control load-data-on-change" data-url="{{$loadCountriesUrl}}" data-target="#country_id" data-live-search="true">
                                <option>Select a Region</option>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}">{{ $region->region }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback region_id-error" role="alert"></span>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="form-group row">
                        <label for="country_id" class="col-sm-4 col-form-label">Country <i class="required-star"></i></label>
                        <div class="col-sm-8">
                            <select name="country_id" id="country_id" class="form-control" data-live-search="true">
                            </select>
                            <span class="invalid-feedback country_id-error" role="alert"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="form-group col-sm-6 row">
            <label for="file_size" class="col-sm-5 col-form-label">Choose File Size <span
                    class="required-star"></span></label>
            <div class="col-sm-7">
                <select name="file_size" id="file_size" class="form-control" data-live-search="true">
                    <option value="5">5MB</option>
                    <option value="10">10MB</option>
                    <option value="20">20MB</option>
                </select>
                <span class="invalid-feedback file_size-error" role="alert"></span>
            </div>
        </div>
        <div class="form-group col-sm-6 row">
            <label for="logo" class="col-sm-5 col-form-label">Logo <i class="required-star"></i>
                <br><small>Max:1MB</small></label>
            <div class="col-sm-7">
                <input type="file" accept="image/*" class="form-control-file" id="logo" name="logo">
                <span class="invalid-feedback logo-error" role="alert"></span>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
    {{ Form::submit('Save', array('class' => 'text-form-submit btn btn-primary')) }}
</div>
{{ Form::close() }}
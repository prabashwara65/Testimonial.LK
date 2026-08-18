{{ Form::open(['url' => route('admin.vendors.update', $vendor['id']), 'class' => 'text-form', 'method' => 'put', 'onsubmit' => 'return false;']) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Edit Vendor User</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="emp_id" class="col-sm-3 col-form-label">Employee ID <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="emp_id" name="emp_id"
                        value="{{ !empty($vendor['emp_id']) ? $vendor['emp_id'] : '' }}">
                    <span class="invalid-feedback emp_id-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="name" class="col-sm-3 col-form-label">First Name <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ !empty($vendor['name']) ? $vendor['name'] : '' }}">
                    <span class="invalid-feedback name-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="last_name" class="col-sm-3 col-form-label">Last Name <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="last_name" name="last_name"
                        value="{{ !empty($vendor['last_name']) ? $vendor['last_name'] : '' }}">
                    <span class="invalid-feedback last_name-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="nic" class="col-sm-3 col-form-label">NIC Number <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="nic" name="nic"
                        value="{{ !empty($vendor['nic']) ? $vendor['nic'] : '' }}">
                    <span class="invalid-feedback nic-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-sm-3 col-form-label">Email <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="email" class="form-control" id="email" name="email"
                        value="{{ !empty($vendor['email']) ? $vendor['email'] : '' }}">
                    <span class="invalid-feedback email-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="mobile" class="col-sm-3 col-form-label">Mobile Number <i
                        class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="mobile" name="mobile"
                        value="{{ !empty($vendor['mobile']) ? $vendor['mobile'] : '' }}">
                    <span class="invalid-feedback mobile-error" role="alert"></span>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group row">
                <label for="address" class="col-sm-3 col-form-label">Address <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="address" name="address"
                        value="{{ !empty($vendor['address']) ? $vendor['address'] : '' }}">
                    <span class="invalid-feedback address-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="address_line1" class="col-sm-3 col-form-label">Address Line 1 <i
                        class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="address_line1" name="address_line1"
                        value="{{ !empty($vendor['address_line1']) ? $vendor['address_line1'] : '' }}">
                    <span class="invalid-feedback address_line1-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="address_line2" class="col-sm-3 col-form-label">Address Line 2 <i
                        class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="address_line2" name="address_line2"
                        value="{{ !empty($vendor['address_line2']) ? $vendor['address_line2'] : '' }}">
                    <span class="invalid-feedback address_line2-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="region_id" class="col-sm-3 col-form-label">Region <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <select name="region_id" id="region_id" class="form-control load-data-on-change"
                        data-url="{{ $loadCountriesUrl }}" data-target="#country_id" data-live-search="true">
                        <option>Select a Region</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}"
                                @if ($vendor['region_id'] == $region->id) selected
                            <?php $countries = \App\Models\Country::where('region_id', $vendor['region_id'])->get(); ?> @endif>
                                {{ $region->region }}
                            </option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback region_id-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="country_id" class="col-sm-3 col-form-label">Country <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <select name="country_id" id="country_id" class="form-control" data-live-search="true">
                        <option value="">Select a Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" @if ($vendor['country_id'] == $country->id) selected @endif>
                                {{ $country->country }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback country_id-error" role="alert"></span>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="vendor_company_id" class="col-sm-3 col-form-label">Vendor Company <i
                        class="required-star"></i></label>
                <div class="col-sm-9">
                    <select name="vendor_company_id" id="vendor_company_id" class="form-control"
                        data-live-search="true">
                        @foreach ($vendor_companies as $vendor_company)
                            <option value="{{ $vendor_company->id }}"
                                @if ($vendor['vendor_company_id'] == $vendor_company->id) selected @endif>
                                {{ ucfirst($vendor_company->company_name) }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback vendor_company_id-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="designation" class="col-sm-3 col-form-label">Designation </label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="designation" name="designation"
                        value="{{ !empty($vendor['designation']) ? $vendor['designation'] : '' }}">
                    <span class="invalid-feedback designation-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="department" class="col-sm-3 col-form-label">Department </label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="department" name="department"
                        value="{{ !empty($vendor['department']) ? $vendor['department'] : '' }}">
                    <span class="invalid-feedback department-error" role="alert"></span>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group row">
                <label for="username" class="col-sm-3 col-form-label">User Name <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="username" name="username"
                        value="{{ !empty($vendor['username']) ? $vendor['username'] : '' }}">
                    <span class="invalid-feedback username-error" role="alert"></span>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-9">
                    <div class="checkbox checkbox-circle checkbox-info peers ai-c mB-15">
                        <input type="checkbox" id="update-password" name="update_password" class="peer">
                        <label for="update-password" class=" peers peer-greed js-sb ai-c">
                            <span class="peer peer-greed">Update Password</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group row">
                <label for="password" class="col-sm-3 col-form-label">Password <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="password" class="form-control password-inputs" id="password" name="password"
                        disabled>
                </div>
            </div>

            <div class="form-group row">
                <label for="password_confirmation" class="col-sm-3 col-form-label">Confirm Password <i
                        class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="password" class="form-control password-inputs" id="password_confirmation"
                        name="password_confirmation" disabled>
                    <span class="invalid-feedback password-error" role="alert"></span>
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

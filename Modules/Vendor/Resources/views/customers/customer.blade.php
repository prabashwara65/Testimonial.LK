{{ Form::open(array('url' => '', 'class'=>'text-form')) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">View Customer</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="name" class="col-sm-3 col-form-label">First Name <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="name" name="name" value="{{!empty($user['name'])? $user['name']: ''}}" disabled>
                    <span class="invalid-feedback name-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="last_name" class="col-sm-3 col-form-label">Last Name <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{!empty($user['last_name'])? $user['last_name']: ''}}" disabled>
                    <span class="invalid-feedback last_name-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="nic" class="col-sm-3 col-form-label">NIC Number <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="nic" name="nic" value="{{!empty($user['nic'])? $user['nic']: ''}}" disabled>
                    <span class="invalid-feedback nic-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-sm-3 col-form-label">Email <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="email" class="form-control" id="email" name="email" value="{{!empty($user['email'])? $user['email']: ''}}" disabled>
                    <span class="invalid-feedback email-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="mobile" class="col-sm-3 col-form-label">Mobile Number <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="mobile" name="mobile" value="{{!empty($user['mobile'])? $user['mobile']: ''}}" disabled>
                    <span class="invalid-feedback mobile-error" role="alert"></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="address" class="col-sm-3 col-form-label">Address <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="address" name="address" value="{{!empty($user['address'])? $user['address']: ''}}" disabled>
                    <span class="invalid-feedback address-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="address_line1" class="col-sm-3 col-form-label">Address Line 1 </label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="address_line1" name="address_line1" value="{{!empty($user['address_line1'])? $user['address_line1']: ''}}" disabled>
                    <span class="invalid-feedback address_line1-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="address_line2" class="col-sm-3 col-form-label">Address Line 2 </label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" id="address_line2" name="address_line2" value="{{!empty($user['address_line2'])? $user['address_line2']: ''}}" disabled>
                    <span class="invalid-feedback address_line2-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="region_id" class="col-sm-3 col-form-label">Region <i
                        class="required-star"></i></label>
                <div class="col-sm-9">
                    <select name="region_id" id="region_id" class="form-control load-data-on-change" data-url="{{$loadCountriesUrl}}" data-target="#country_id" data-live-search="true" disabled>
                        <option>Select a Region</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}"
                                    @if($user['region_id'] == $region->id) selected
                            <?php $countries = \App\Models\Country::where('region_id', $user['region_id'])->get(); ?>
                                @endif>{{ $region->region }}
                            </option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback region_id-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="country_id" class="col-sm-3 col-form-label">Country <i class="required-star"></i></label>
                <div class="col-sm-9">
                    <select name="country_id" id="country_id" class="form-control" data-live-search="true" disabled>
                        <option value="">Select a Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" @if($user['country_id'] == $country->id) selected @endif>{{ $country->country }}</option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback country_id-error" role="alert"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
</div>
{{ Form::close() }}

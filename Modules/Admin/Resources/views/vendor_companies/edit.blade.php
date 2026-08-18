{{ Form::open(['url' => route('admin.vendor-companies.update', $vendorCompany['id']), 'class' => 'text-form', 'files' => 'true', 'method' => 'put', 'onsubmit' => 'return false;']) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Edit Vendor Company</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="company_name" class="col-sm-4 col-form-label">Company Name <i
                        class="required-star"></i></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="company_name" name="company_name"
                        value="{{ !empty($vendorCompany['company_name']) ? $vendorCompany['company_name'] : '' }}">
                    <span class="invalid-feedback company_name-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="br_no" class="col-sm-4 col-form-label">BR Number <i class="required-star"></i></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="br_no" name="br_no"
                        value="{{ !empty($vendorCompany['br_no']) ? $vendorCompany['br_no'] : '' }}">
                    <span class="invalid-feedback br_no-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-sm-4 col-form-label">Email <i class="required-star"></i></label>
                <div class="col-sm-8">
                    <input type="email" class="form-control" id="email" name="email"
                        value="{{ !empty($vendorCompany['email']) ? $vendorCompany['email'] : '' }}">
                    <span class="invalid-feedback email-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="contact_no" class="col-sm-4 col-form-label">Contact No <i class="required-star"></i></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="contact_no" name="contact_no"
                        value="{{ !empty($vendorCompany['contact_no']) ? $vendorCompany['contact_no'] : '' }}">
                    <span class="invalid-feedback contact_no-error" role="alert"></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="address" class="col-sm-4 col-form-label">Address <i class="required-star"></i></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="address" name="address"
                        value="{{ !empty($vendorCompany['address']) ? $vendorCompany['address'] : '' }}">
                    <span class="invalid-feedback address-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="address_line1" class="col-sm-4 col-form-label">Address Line 1 </label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="address_line1" name="address_line1"
                        value="{{ !empty($vendorCompany['address_line1']) ? $vendorCompany['address_line1'] : '' }}">
                    <span class="invalid-feedback address_line1-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="address_line2" class="col-sm-4 col-form-label">Address Line 2 </label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="address_line2" name="address_line2"
                        value="{{ !empty($vendorCompany['address_line2']) ? $vendorCompany['address_line2'] : '' }}">
                    <span class="invalid-feedback address_line2-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="region_id" class="col-sm-4 col-form-label">Region <i class="required-star"></i></label>
                <div class="col-sm-8">
                    <select name="region_id" id="region_id" class="form-control load-data-on-change"
                        data-url="{{ $loadCountriesUrl }}" data-target="#country_id" data-live-search="true">
                        <option>Select a Region</option>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}"
                                @if ($vendorCompany['region_id'] == $region->id) selected
                            <?php $countries = \App\Models\Country::where('region_id', $vendorCompany['region_id'])->get(); ?> @endif>
                                {{ $region->region }}
                            </option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback role-error" role="alert"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="country_id" class="col-sm-4 col-form-label">Country <i class="required-star"></i></label>
                <div class="col-sm-8">
                    <select name="country_id" id="country_id" class="form-control" data-live-search="true">
                        <option value="">Select a Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" @if ($vendorCompany['country_id'] == $country->id) selected @endif>
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
                <label for="renewal_start_date" class="col-sm-4 col-form-label">Renewal Start Date <span
                        class="required-star"></span></label>
                <div class="col-sm-8">
                    <input type="text" name="renewal_start_date" id="renewal_start_date"
                        class="form-control datepicker" data-date-format="yyyy-mm-dd"
                        value="{{ !empty($vendorCompany['renewal_start_date']) ? $vendorCompany['renewal_start_date'] : '' }}">
                    <span class="invalid-feedback renewal_start_date-error" role="alert"></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="renewal_charge" class="col-sm-4 col-form-label">Renewal Charge <span
                        class="required-star"></span></label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="renewal_charge" name="renewal_charge"
                        value="{{ !empty($vendorCompany['renewal_charge']) ? $vendorCompany['renewal_charge'] : '' }}">
                    <span class="invalid-feedback renewal_charge-error" role="alert"></span>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="file_size" class="col-sm-4 col-form-label">Choose File Size <span
                        class="required-star"></span></label>
                <div class="col-sm-8">
                    <select name="file_size" id="file_size" class="form-control" data-live-search="true">
                        <option value="5" @if ($vendorCompany['file_size'] == 5) selected @endif>5MB</option>
                        <option value="10" @if ($vendorCompany['file_size'] == 10) selected @endif>10MB</option>
                        <option value="20" @if ($vendorCompany['file_size'] == 20) selected @endif>20MB</option>
                    </select>
                    <span class="invalid-feedback file_size-error" role="alert"></span>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group row">
                <label for="logo" class="col-sm-4 col-form-label">Logo
                    <small class="ml-2">(Max:1MB)</small></label>
                <div class="col-sm-8">
                    <input type="file" accept="image/*" class="form-control-file" id="logo" name="logo">
                    <span class="invalid-feedback logo-error" role="alert"></span>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="limit_regions" class="col-sm-4 col-form-label">Limit Regions <span
                        class="required-star"></span></label>
                <div class="col-sm-5 pr-0">
                    <select name="limit_regions[]" id="limit_regions" class="form-control load-data-on-change"
                        data-url="{{ route('load-countries') }}" data-target="#limit_countries"
                        data-live-search="true" multiple>
                        @foreach ($regions as $region)
                            <option value="{{ $region->id }}"
                                @if ($vendorCompany['limit_regions'] && in_array($region->id, $vendorCompany['limit_regions'])) selected
                            <?php $countries = \App\Models\Country::whereIn('region_id', $vendorCompany['limit_regions'])->get(); ?> @endif>
                                {{ $region->region }}
                            </option>
                        @endforeach
                    </select>
                    <span class="invalid-feedback limit_regions-error" role="alert"></span>
                </div>
                <div class="col-sm-3 pl-0">
                    <button class="btn btn-default" onclick="selectAll('#limit_regions')"><i
                            class="icon-checkbox-checked"></i></button>
                    <button class="btn btn-default" onclick="unSelectAll('#limit_regions')"><i
                            class="icon-checkbox-unchecked"></i></button>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group row">
                <label for="limit_countries" class="col-sm-4 col-form-label">Limit Countries <span
                        class="required-star"></span></label>
                <div class="col-sm-5 pr-0">
                    <select name="limit_countries[]" id="limit_countries" class="form-control load-data-on-change"
                        data-url="{{ route('load-provinces') }}" data-target="#limit_provinces"
                        data-live-search="true" multiple>
                        @isset($countries)
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}"
                                    @if ($vendorCompany['limit_countries'] && in_array($country->id, $vendorCompany['limit_countries'])) selected
                                <?php $provinces = \App\Models\Province::whereIn('country_id', $vendorCompany['limit_countries'])->get(); ?> @endif>
                                    {{ $country->country }}
                                </option>
                            @endforeach
                        @endisset
                    </select>
                    <span class="invalid-feedback limit_countries-error" role="alert"></span>
                </div>
                <div class="col-sm-3 pl-0">
                    <button class="btn btn-default" onclick="selectAll('#limit_countries')"><i
                            class="icon-checkbox-checked"></i></button>
                    <button class="btn btn-default" onclick="unSelectAll('#limit_countries')"><i
                            class="icon-checkbox-unchecked"></i></button>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group row">
                <label for="limit_provinces" class="col-sm-4 col-form-label">Limit Provinces <span
                        class="required-star"></span></label>
                <div class="col-sm-5 pr-0">
                    <select name="limit_provinces[]" id="limit_provinces" class="form-control load-data-on-change"
                        data-url="{{ route('load-districts') }}" data-target="#limit_districts"
                        data-live-search="true" multiple>
                        @isset($provinces)
                            @foreach ($provinces as $province)
                                <option value="{{ $province->id }}"
                                    @if ($vendorCompany['limit_provinces'] && in_array($province->id, $vendorCompany['limit_provinces'])) selected
                                <?php $districts = \App\Models\District::whereIn('province_id', $vendorCompany['limit_provinces'])->get(); ?> @endif>
                                    {{ $province->province }}
                                </option>
                            @endforeach
                        @endisset
                    </select>
                    <span class="invalid-feedback limit_provinces-error" role="alert"></span>
                </div>
                <div class="col-sm-3 pl-0">
                    <button class="btn btn-default" onclick="selectAll('#limit_provinces')"><i
                            class="icon-checkbox-checked"></i></button>
                    <button class="btn btn-default" onclick="unSelectAll('#limit_provinces')"><i
                            class="icon-checkbox-unchecked"></i></button>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group row">
                <label for="limit_districts" class="col-sm-4 col-form-label">Limit Districts <span
                        class="required-star"></span></label>
                <div class="col-sm-5 pr-0">
                    <select name="limit_districts[]" id="limit_districts" class="form-control"
                        data-live-search="true" multiple>
                        @isset($districts)
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}" @if ($vendorCompany['limit_districts'] && in_array($district->id, $vendorCompany['limit_districts'])) selected @endif>
                                    {{ $district->district }}</option>
                            @endforeach
                        @endisset
                    </select>
                    <span class="invalid-feedback limit_districts-error" role="alert"></span>
                </div>
                <div class="col-sm-3 pl-0">
                    <button class="btn btn-default" onclick="selectAll('#limit_districts')"><i
                            class="icon-checkbox-checked"></i></button>
                    <button class="btn btn-default" onclick="unSelectAll('#limit_districts')"><i
                            class="icon-checkbox-unchecked"></i></button>
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

<script>
    function selectAll(select) {
        $(select).find('option').prop('selected', true);
        $(select).trigger('change');
    }

    function unSelectAll(select) {
        $(select).find('option').prop('selected', false);
        $(select).trigger('change');
    }
</script>

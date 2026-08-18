{{ Form::open(array('url' => route('admin.districts.update', $district['id']), 'class'=>'text-form', 'method' => 'put', 'onsubmit' => 'return false;')) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Edit District</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class='col-lg-8 offset-lg-2'>
        <div class="form-group row">
            <label for="region_id" class="col-sm-4 col-form-label">Region <i
                    class="required-star"></i></label>
            <div class="col-sm-8">
                <select name="region_id" id="region_id" class="form-control load-data-on-change" data-url="{{$loadCountriesUrl}}" data-target="#country_id" data-live-search="true">
                    <option>Select a Region</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}"
                                @if($district['region_id'] == $region->id) selected
                        <?php $countries = \App\Models\Country::where('region_id', $district['region_id'])->get(); ?>
                            @endif>{{ $region->region }}
                        </option>
                    @endforeach
                </select>
                <span class="invalid-feedback region_id-error" role="alert"></span>
            </div>
        </div>

        <div class="form-group row">
            <label for="country_id" class="col-sm-4 col-form-label">Country <i class="required-star"></i></label>
            <div class="col-sm-8">
                <select name="country_id" id="country_id" class="form-control load-data-on-change" data-url="{{$loadProvincesUrl}}" data-target="#province_id" data-live-search="true">
                    <option value="">Select a Country</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}" @if($district['country_id'] == $country->id) selected @endif>{{ $country->country }}</option>
                    @endforeach
                </select>
                <span class="invalid-feedback country_id-error" role="alert"></span>
            </div>
        </div>

        <div class="form-group row">
            <label for="province_id" class="col-sm-4 col-form-label">Province <i class="required-star"></i></label>
            <div class="col-sm-8">
                <select name="province_id" id="province_id" class="form-control" data-live-search="true">
                    <option>Select a Province</option>
                    @foreach ($provinces as $province)
                        <option value="{{ $province->id }}" @if($district['province_id'] == $province->id) selected @endif>{{ $province->province }}</option>
                    @endforeach
                </select>
                <span class="invalid-feedback province_id-error" role="alert"></span>
            </div>
        </div>

        <div class="form-group row">
            <label for="district" class="col-md-4 col-form-label">District<i class="required-star"></i></label>
            <div class="col-md-8">
                <input type="text" class="form-control" id="district" name="district" value="{{$district['district']}}">
                <span class="invalid-feedback district-error" role="alert"></span>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
    {{ Form::submit('Save', array('class' => 'text-form-submit btn btn-primary')) }}
</div>
{{ Form::close() }}

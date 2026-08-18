@extends('layouts.frontend')

@section('content')

    {{-- Customer Register Form --}}
    <div class="customer-register container-fluid second-page" id="customerRegister">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-xs-12 text-left ">
                            <div class="row-title">My Profile</div>
                        </div>
                    </div>

                    <div class="form-holder">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{ Form::open(['url' => route('profile.update'), 'class' => 'text-form', 'method' => 'post']) }}
                        <div class="form-row">
                            <div class="col-xs-12 col-sm-6">
                                <label for="name" class="text-color">First Name <sup class="red-color">*</sup></label>
                                <input id="name" name="name" type="text" class="form-control h-inherit"
                                    value="{{ $customer->name }}" required>
                            </div>

                            <div class="col-xs-12 col-sm-6">
                                <label for="last_name" class="text-color">Last Name <sup class="red-color">*</sup></label>
                                <input id="last_name" name="last_name" type="text" class="form-control h-inherit"
                                    value="{{ $customer->last_name }}" required>
                            </div>

                            <div class="col-xs-12 col-sm-6">
                                <label for="nic" class="text-color">NIC <sup class="red-color">*</sup></label>
                                <input id="nic" name="nic" type="text" class="form-control h-inherit"
                                    value="{{ $customer->nic }}" required>
                            </div>

                            <div class="col-xs-12 col-sm-6">
                                <label for="email" class="text-color">Email <sup class="red-color">*</sup></label>
                                <input id="email" name="email" type="email" class="form-control h-inherit"
                                    value="{{ $customer->email }}" required>
                            </div>

                            <div class="col-xs-12 col-sm-6">
                                <label for="mobile" class="text-color">Phone Number <sup class="red-color">*</sup></label>
                                <input id="mobile" name="mobile" type="text" class="form-control h-inherit"
                                    value="{{ $customer->mobile }}" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-xs-12">
                                <label for="address" class="text-color">Address <sup class="red-color">*</sup></label>
                                <input id="address" name="address" type="text" class="form-control h-inherit"
                                    value="{{ $customer->address }}" required>
                            </div>

                            <div class="col-xs-12">
                                <label for="address_line1" class="text-color">Address Line 1</label>
                                <input id="address_line1" name="address_line1" type="text" class="form-control h-inherit"
                                    value="{{ $customer->address_line1 }}" required>
                            </div>

                            <div class="col-xs-12">
                                <label for="address_line2" class="text-color">Address Line 2</label>
                                <input id="address_line2" name="address_line2" type="text" class="form-control h-inherit"
                                    value="{{ $customer->address_line2 }}" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-xs-12 col-sm-6">
                                <label for="region_id" class="text-color">Region <sup class="red-color">*</sup></label>
                                <select name="region_id" id="region_id" class="form-control load-data-on-change"
                                    data-url="{{ $loadCountriesUrl }}" data-target="#country_id">
                                    <option>Select a Region</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}"
                                            @if ($customer['region_id'] == $region->id) selected
                                                    <?php $countries = \App\Models\Country::where('region_id', $customer['region_id'])->get(); ?> @endif>
                                            {{ $region->region }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xs-12 col-sm-6">
                                <label for="country_id" class="text-color">Country <sup class="red-color">*</sup></label>
                                <select name="country_id" id="country_id" class="form-control">
                                    <option value="">Select a Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            @if ($customer['country_id'] == $country->id) selected @endif>{{ $country->country }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-xs-12 mt-60">
                                <p class="m-0 fw-600 text-color">Change Password</p>
                            </div>

                            <div class="col-xs-12 col-sm-6">
                                <label for="password" class="text-color">New Password</label>
                                <input name="password" id="password" type="text" class="form-control h-inherit">
                            </div>

                            <div class="col-xs-12 col-sm-6">
                                <label for="password_confirmation" class="text-color">Confirm Password</label>
                                <input name="password_confirmation" id="password_confirmation" type="text"
                                    class="form-control h-inherit">
                            </div>

                        </div>

                        <div class="form-row">
                            <div class="col-xs-12 mt-20">
                                <div class="submit-holder">
                                    {{ Form::submit('Update', ['class' => 'hbtn hbtn-blue']) }}
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- / Customer Register Form --}}
@endsection

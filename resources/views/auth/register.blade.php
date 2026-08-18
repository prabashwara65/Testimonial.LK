@extends('layouts.frontend')

@section('content')

    {{-- Customer Register Form --}}
    <div class="customer-register container-fluid main-theme-bg" id="customerRegister">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-xs-12 text-left">
                            <div class="row-title">Customer Registration</div>
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

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="form-row">
                                <div class="col-xs-12 col-sm-6">
                                    <label for="name" class="white-color">First Name <sup class="red-color">*</sup></label>
                                    <input id="name" name="name" type="text" class="form-control h-inherit" value="{{ old('name') }}" required>
                                </div>

                                <div class="col-xs-12 col-sm-6">
                                    <label for="last_name" class="white-color">Last Name <sup class="red-color">*</sup></label>
                                    <input id="last_name" name="last_name" type="text" class="form-control h-inherit" value="{{ old('last_name') }}" required>
                                </div>

                                <div class="col-xs-12 col-sm-6">
                                    <label for="nic" class="white-color">NIC <sup class="red-color">*</sup></label>
                                    <input id="nic" name="nic" type="text" class="form-control h-inherit" value="{{ old('nic') }}" required>
                                </div>

                                <div class="col-xs-12 col-sm-6">
                                    <label for="email" class="white-color">Email <sup class="red-color">*</sup></label>
                                    <input id="email" name="email" type="email" class="form-control h-inherit" value="{{ old('email') }}" required>
                                </div>

                                <div class="col-xs-12 col-sm-6">
                                    <label for="mobile" class="white-color">Phone Number</label>
                                    <input id="mobile" name="mobile" type="text" class="form-control h-inherit" value="{{ old('mobile') }}" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-xs-12">
                                    <label for="address" class="white-color">Address</label>
                                    <input id="address" name="address" type="text" class="form-control h-inherit" value="{{ old('address') }}" required>
                                </div>

                                <div class="col-xs-12">
                                    <label for="address_line1" class="white-color">Address Line 1</label>
                                    <input id="address_line1" name="address_line1" type="text" class="form-control h-inherit" value="{{ old('address_line1') }}" required>
                                </div>

                                <div class="col-xs-12">
                                    <label for="address_line2" class="white-color">Address Line 2</label>
                                    <input id="address_line2" name="address_line2" type="text" class="form-control h-inherit" value="{{ old('address_line2') }}" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-xs-12 col-sm-6">
                                    <label for="inputRegion" class="white-color">Region</label>
                                    <select name="region_id" id="region_id" class="form-control load-data-on-change" data-url="{{$loadCountriesUrl}}" data-target="#country_id">
                                        <option>Select a Region</option>
                                        @foreach ($regions as $region)
                                            <option value="{{ $region->id }}">{{ $region->region }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-xs-12 col-sm-6">
                                    <label for="selectCountry" class="white-color">Select Country</label>
                                    <select name="country_id" id="country_id" class="form-control">
                                    </select>
                                </div>

                                <div class="col-xs-12 col-sm-6">
                                    <label for="password" class="white-color">Enter Password <sup class="red-color">*</sup></label>
                                    <input id="password" type="password" name="password" class="form-control h-inherit" required autocomplete="new-password">
                                </div>

                                <div class="col-xs-12 col-sm-6">
                                    <label for="password-confirm" class="white-color">Confirm Password <sup class="red-color">*</sup></label>
                                    <input id="password-confirm" type="password" class="form-control h-inherit" name="password_confirmation" required autocomplete="new-password">
                                </div>

                            </div>

                            <div class="form-row">
                                <div class="col-xs-12 mt-20">
                                    <div class="submit-holder">
                                        <button type="submit" class="hbtn hbtn-blue">Sign Up</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--/ Customer Register Form --}}


{{--<div class="container">--}}
{{--    <div class="row justify-content-center">--}}
{{--        <div class="col-md-8">--}}
{{--            <div class="card">--}}
{{--                <div class="card-header">{{ __('Register') }}</div>--}}

{{--                <div class="card-body">--}}
{{--                    <form method="POST" action="{{ route('register') }}">--}}
{{--                        @csrf--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>--}}

{{--                                @error('name')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">--}}

{{--                                @error('email')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">--}}

{{--                                @error('password')--}}
{{--                                    <span class="invalid-feedback" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row">--}}
{{--                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>--}}

{{--                            <div class="col-md-6">--}}
{{--                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">--}}
{{--                            </div>--}}
{{--                        </div>--}}

{{--                        <div class="form-group row mb-0">--}}
{{--                            <div class="col-md-6 offset-md-4">--}}
{{--                                <button type="submit" class="btn btn-primary">--}}
{{--                                    {{ __('Register') }}--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
@endsection

@extends('layouts.vendor')

@section('content')
    <div class="create-testimonials container-fluid second-page" id="createTestimonials">
        <div class="container">
            <div class="row">
                <section class="col-xs-12">
                    <div class="wizard">
                        @include('vendor::salesrep.response.nav-wizard')

                        <div class="form-holder">
                            <form action="{{ route('response.step-two.post') }}" method="POST" role="form">
                                @csrf
                                <div class="tab-content">
                                    <div class="tab-pane wizard-tab-pane active" role="tabpanel" id="step3">
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="form-row">
                                            <div class="col-xs-12 col-sm-6 col-md-4">
                                                <label for="name" >First Name <sup class="red-color">*</sup></label>
                                                @isset($customer)
                                                    <input id="name" name="name" type="text" class="form-control h-inherit" value="{{ $customer->name }}" readonly>
                                                @else
                                                    <input id="name" name="name" type="text" class="form-control h-inherit" value="{{ old('name') }}">
                                                @endisset
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-4">
                                                <label for="last_name" >Last Name <sup class="red-color">*</sup></label>
                                                @isset($customer)
                                                    <input id="last_name" name="last_name" type="text" class="form-control h-inherit" value="{{ $customer->last_name }}" readonly>
                                                @else
                                                    <input id="last_name" name="last_name" type="text" class="form-control h-inherit" value="{{ old('last_name') }}">
                                                @endisset
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-4">
                                                <label for="nic" >NIC <sup class="red-color">*</sup></label>
                                                @isset($customer)
                                                    <input id="nic" name="nic" type="text" class="form-control h-inherit" value="{{ $customer->nic }}" readonly>
                                                @else
                                                    <input id="nic" name="nic" type="text" class="form-control h-inherit" value="{{ Session::get('nic')['nic'] }}" readonly>
                                                @endisset
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                                <label for="email" >Email <sup class="red-color">*</sup></label>
                                                @isset($customer)
                                                    <input id="email" name="email" type="email" class="form-control h-inherit" value="{{ $customer->email }}" readonly>
                                                @else
                                                    <input id="email" name="email" type="email" class="form-control h-inherit" value="{{ old('email') }}">
                                                @endisset
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                                <label for="mobile" >Mobile <sup class="red-color">*</sup></label>
                                                @isset($customer)
                                                    <input id="mobile" name="mobile" type="text" class="form-control h-inherit" value="{{ $customer->mobile }}" readonly>
                                                @else
                                                    <input id="mobile" name="mobile" type="text" class="form-control h-inherit" value="{{ old('mobile') }}">
                                                @endisset
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-xs-12 col-sm-4">
                                                <label for="address" >Address <sup class="red-color">*</sup></label>
                                                @isset($customer)
                                                    <input id="address" name="address" type="text" class="form-control h-inherit" value="{{ $customer->address }}" readonly>
                                                @else
                                                    <input id="address" name="address" type="text" class="form-control h-inherit" value="{{ old('address') }}">
                                                @endisset
                                            </div>

                                            <div class="col-xs-12 col-sm-4">
                                                <label for="address_line1" >Address Line 1</label>
                                                @isset($customer)
                                                    <input id="address_line1" name="address_line1" type="text" class="form-control h-inherit" value="{{ $customer->address_line1 }}" readonly>
                                                @else
                                                    <input id="address_line1" name="address_line1" type="text" class="form-control h-inherit" value="{{ old('address_line1') }}">
                                                @endisset
                                            </div>

                                            <div class="col-xs-12 col-sm-4">
                                                <label for="address_line2" >Address Line 2</label>
                                                @isset($customer)
                                                    <input id="address_line2" name="address_line2" type="text" class="form-control h-inherit" value="{{ $customer->address_line2 }}" readonly>
                                                @else
                                                    <input id="address_line2" name="address_line2" type="text" class="form-control h-inherit" value="{{ old('address_line2') }}">
                                                @endisset
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                                <label for="region_id" >Region <sup class="red-color">*</sup></label>
                                                @isset($customer)
                                                    <select id="region_id" name="region_id" class="form-control h-inherit load-data-on-change" readonly>
                                                        <option value="{{ $customer->region_id }}">{{ $regions->find($customer->region_id)->region }}</option>
                                                    </select>
                                                @else
                                                    <select id="region_id" name="region_id" class="form-control h-inherit load-data-on-change" data-url="{{$loadCountriesUrl}}" data-target="#country_id" data-live-search="true">
                                                        <option>Select a Region</option>
                                                        @foreach ($regions as $region)
                                                            <option value="{{ $region->id }}">{{ $region->region }}</option>
                                                        @endforeach
                                                    </select>
                                                @endisset
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                                <label for="country_id" >Country <sup class="red-color">*</sup></label>
                                                @isset($customer)
                                                    <select id="country_id" name="country_id" class="form-control h-inherit load-data-on-change" readonly>
                                                        <option value="{{ $customer->country_id }}">{{ $countries->find($customer->country_id)->country }}</option>
                                                    </select>
                                                @else
                                                    <select id="country_id" name="country_id" class="form-control" data-live-search="true">
                                                    </select>
                                                @endisset
                                            </div>
                                        </div>

                                        <div class="col-xs-12">
                                            <ul class="list-inline pull-right mt-20">
                                                <li><button type="submit" class="hbtn hbtn-blue next-step">Save and continue</button></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

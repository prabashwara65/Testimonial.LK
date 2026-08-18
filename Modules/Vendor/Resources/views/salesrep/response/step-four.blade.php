@extends('layouts.vendor')

@section('content')
    <div class="create-testimonials container-fluid second-page" id="createTestimonials">
        <div class="container">
            <div class="row">
                <section class="col-xs-12">
                    <div class="wizard">
                        @include('vendor::salesrep.response.nav-wizard')

                        <div class="form-holder">
                            <form action="{{ route('response.step-four.post') }}" method="POST" role="form">
                                @csrf
                                <div class="tab-content">
                                    <div class="tab-pane wizard-tab-pane active" role="tabpanel" id="step1">
                                        <div class="form-row row">
                                            <div class="col-xs-12 col-sm-6 col-md-4">
                                                <label for="product_id" >Select Product <sup class="red-color">*</sup></label>
                                                <select id="product_id" name="product_id" class="form-control custom-select load-data-on-change" data-url="{{$loadSubproductsUrl}}" data-target="#subproduct_id">
                                                    <option>Select a Product</option>
                                                    @foreach ($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-4">
                                                <label for="subproduct_id" >Select Subproduct <sup class="red-color">*</sup></label>
                                                <select id="subproduct_id" name="subproduct_id" class="form-control custom-select load-data-on-change" data-url="{{$loadCampaignsUrl}}" data-target="#campaign_id">
                                                </select>
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-4">
                                                <label for="campaign_id" >Select Campaigns <sup class="red-color">*</sup></label>
                                                <select id="campaign_id" name="campaign_id" class="form-control custom-select">
                                                </select>
                                            </div>
                                        </div>

                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

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

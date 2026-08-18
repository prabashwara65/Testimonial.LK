@extends('layouts.frontend')

@section('content')
    <div class="create-testimonials container-fluid second-page" id="createTestimonials">
        <div class="container">
            <div class="row">
                <section class="col-xs-12">
                    <div class="wizard">
                        <div class="wizard-inner">
                            <div class="connecting-line"></div>
                            <ul class="nav nav-tabs wizard-tabs" role="tablist">

                                <li role="presentation" class="active">
                                    <a href="#step1" class="d-flex align-center" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1">
                                        <span class="round-tab mr-10">
                                            1
                                        </span>
                                        <span class="f14 fw-500">Select Product</span>
                                    </a>
                                </li>

                                <li role="presentation" class="disabled">
                                    <a href="#step2" class="d-flex align-center" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2">
                                        <span class="round-tab mr-10">
                                            2
                                        </span>
                                        <span class="f14 fw-500">Feedback or Testimonial</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="form-holder">
                            <form action="{{ route('customer-response.step-one.post') }}" method="POST" role="form">
                                @csrf
                                <div class="tab-content">
                                    <div class="tab-pane wizard-tab-pane active" role="tabpanel" id="step1">
                                        <div class="form-row row">
                                            <div class="col-xs-12 col-sm-6 col-md-4">
                                                <label for="vendor_company_id" >Select Company <sup class="red-color">*</sup></label>
                                                <select id="vendor_company_id" name="vendor_company_id" class="form-control load-data-on-change" data-url="{{$loadProductsUrl}}" data-target="#product_id">
                                                    <option>Select a Vendor Company</option>
                                                    @foreach ($vendorCompanies as $vendorCompany)
                                                        <option value="{{ $vendorCompany->id }}">{{ $vendorCompany->company_name }}</option>    
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-4">
                                                <label for="product_id" >Select Product <sup class="red-color">*</sup></label>
                                                <select id="product_id" name="product_id" class="form-control load-data-on-change" data-url="{{$loadSubproductsUrl}}" data-target="#subproduct_id">
                                                </select>
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-4">
                                                <label for="subproduct_id" >Select Subproduct <sup class="red-color">*</sup></label>
                                                <select id="subproduct_id" name="subproduct_id" class="form-control">
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

@extends('layouts.backend')

@section('title', $title)

@section('content')
    <div class="dashboard container-fluid">
        <div class="row">
            <div class="col-md-10">
                <h4 class="c-grey-900 mT-10 mB-30">{{$title}}</h4>
            </div>
        </div>

        <div class="row count">
            <div class="col-lg-4">
                <div class="card bg-blue-400">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="font-weight-semibold mb-0">{{ $total_vendors }}</h3>

                            <div class="list-icons ml-auto">
                                <i class="fa fa-briefcase fa-3x"></i>
                            </div>
                        </div>

                        <div>
                            TOTAL VENDORS
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card bg-teal-400">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="font-weight-semibold mb-0">{{ $total_approved_testimonial }}</h3>

                            <div class="list-icons ml-auto">
                                <i class="fa fa-comment fa-3x"></i>
                            </div>
                        </div>

                        <div>
                            TOTAL APPROVED TESTIMONIALS
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card bg-teal-400">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="font-weight-semibold mb-0">{{ $total_approved_feedback }}</h3>

                            <div class="list-icons ml-auto">
                                <i class="fa fa-comment fa-3x"></i>
                            </div>
                        </div>

                        <div>
                            TOTAL APPROVED FEEDBACKS
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header header-elements-inline">
                        <h5 class="card-title">Testimonials And Feedbacks By Vendor Companies</h5>
                    </div>
                    <div class="card-body" style="">
                        <div class="chart-container">
                            <div style="height: 400px" id="testimonial_feedback_vendor_wise_count_columns">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        {{ Form::open(array('url' => route('admin.dashboard.vendor-wise'), 'class'=>'text-form')) }}
        <div class="form-group row">
            <label for="vendor_company" class="col-md-1 col-form-label">Vendor Company</label>
            <div class="col-md-3">
                    <select name="vendor_company" id="vendor_company" class="form-control" {{ $vendor_companies->isEmpty() ? 'disabled' : '' }}>
                        @if($vendor_companies->isEmpty())
                            <option value="">No active vendor companies</option>
                        @endif
                        @foreach($vendor_companies as $company)
                            <option value="{{ $company->id }}" {{ ($company->id == $vendor_company_id) ? 'selected' : '' }}> {{ $company->company_name }} </option>
                        @endforeach
                    </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="filter-form-submit btn btn-primary" {{ $vendor_companies->isEmpty() ? 'disabled' : '' }}><i class="fa fa-line-chart"></i> Show</button>
            </div>
        </div>
        {{ Form::close() }}

        <div class="row count">
            <div class="col-lg-3">
                <div class="card bg-blue-400">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="font-weight-semibold mb-0">{{ $all_testimonial }}</h3>

                            <div class="list-icons ml-auto">
                                <i class="fa fa-comment fa-3x"></i>
                            </div>
                        </div>

                        <div>
                            ALL TESTIMONIAL COUNT
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card bg-teal-400">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="font-weight-semibold mb-0">{{ $approved_testimonial }}</h3>

                            <div class="list-icons ml-auto">
                                <i class="fa fa-thumbs-up fa-3x"></i>
                            </div>
                        </div>

                        <div>
                            APPROVED TESTIMONIAL COUNT
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card bg-pink-400">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="font-weight-semibold mb-0">{{ $reject_testimonial }}</h3>

                            <div class="list-icons ml-auto">
                                <i class="fa fa-thumbs-down fa-3x"></i>
                            </div>
                        </div>

                        <div>
                            REJECT TESTIMONIAL COUNT
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card bg-warning-400">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="font-weight-semibold mb-0">{{ $pending_testimonial }}</h3>

                            <div class="list-icons ml-auto">
                                <i class="fa fa-hourglass-half fa-3x"></i>
                            </div>
                        </div>

                        <div>
                            PENDING TESTIMONIAL COUNT
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row count">
            <div class="col-lg-3">
                <div class="card bg-blue-400">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="font-weight-semibold mb-0">{{ $all_feedback }}</h3>

                            <div class="list-icons ml-auto">
                                <i class="fa fa-comment fa-3x"></i>
                            </div>
                        </div>

                        <div>
                            ALL FEEDBACK COUNT
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card bg-teal-400">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="font-weight-semibold mb-0">{{ $approved_feedback }}</h3>

                            <div class="list-icons ml-auto">
                                <i class="fa fa-thumbs-up fa-3x"></i>
                            </div>
                        </div>

                        <div>
                            APPROVED FEEDBACK COUNT
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card bg-pink-400">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="font-weight-semibold mb-0">{{ $reject_feedback }}</h3>

                            <div class="list-icons ml-auto">
                                <i class="fa fa-thumbs-down fa-3x"></i>
                            </div>
                        </div>

                        <div>
                            REJECT FEEDBACK COUNT
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card bg-warning-400">
                    <div class="card-body">
                        <div class="d-flex">
                            <h3 class="font-weight-semibold mb-0">{{ $pending_feedback }}</h3>

                            <div class="list-icons ml-auto">
                                <i class="fa fa-hourglass-half fa-3x"></i>
                            </div>
                        </div>

                        <div>
                            PENDING FEEDBACK COUNT
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header header-elements-inline">
						<h5 class="card-title">Approved Testimonial - Category Wise</h5>
					</div>
                    <div class="card-body" style="">
						<div class="chart-container">
							<div style="height: 400px" id="testimonial_count_columns">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header header-elements-inline">
						<h5 class="card-title">Approved Feedbacks - Category Wise</h5>
					</div>
                    <div class="card-body" style="">
						<div class="chart-container">
							<div style="height: 400px" id="feedback_count_columns">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header header-elements-inline">
						<h5 class="card-title">Approved Testimonial Ratings</h5>
					</div>
                    <div class="card-body" style="">
						<div class="chart-container">
							<div style="height: 400px" id="testimonial_rating_columns">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header header-elements-inline">
						<h5 class="card-title">Approved Feedback Ratings</h5>
					</div>
                    <div class="card-body" style="">
						<div class="chart-container">
							<div style="height: 400px" id="feedback_rating_columns">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
<!-- Theme JS files -->

<script src="{{ asset('assets/custom/js/charts/echarts.min.js') }}"></script>

@include('admin::dashboard.charts.testimonialFeedbackVendorWise')
@include('admin::dashboard.charts.testimonialCount')
@include('admin::dashboard.charts.feedbackCount')
@include('admin::dashboard.charts.testimonialRating')
@include('admin::dashboard.charts.feedbackRating')
<!-- /theme JS files -->
@endsection

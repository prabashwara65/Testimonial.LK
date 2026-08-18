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

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="layer w-100 p-20" style="padding-bottom: 0 !important;">
                        <h5 class="title-color">Device Locations</h5>
                        <hr>
                    </div>
                    <div class="card-body">
                        {{ Form::open(array('url' => route('vendor.dashboard.map'), 'class'=>'text-form')) }}
                            <div class="form-group row">
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="row">
                                        <label for="start_date" class="col-md-4 col-form-label">Date From</label>
                                        <div class="col-md-8">
                                            <input type="text" name="start_date" id="start_date" class="form-control datepicker" data-date-format="yyyy-mm-dd" value="{{$start_date}}">
                                            <span class="invalid-feedback form.start_date-error" role="alert"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="row">
                                        <label for="end_date" class="col-md-4 col-form-label">Date To</label>
                                        <div class="col-md-8">
                                            <input type="text" name="end_date" id="end_date" class="form-control datepicker" data-date-format="yyyy-mm-dd" value="{{$end_date}}">
                                            <span class="invalid-feedback form.end_date-error" role="alert"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <button type="submit" class="filter-form-submit btn btn-primary"><i class="fa fa-filter"></i> Show</button>
                                </div>
                            </div>
                        {{ Form::close() }}
                    </div>
                    <div class="card-body" id="table-holder">
                        @include('datatables.table')
                    </div>
                    <div class="card-body">
                        <div id="map" class="m-4" style="width: 100%; height: 750px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @include('datatables.script')

<!-- Chart JS files -->
<script src="{{ asset('assets/custom/js/charts/echarts.min.js') }}"></script>

@include('vendor::dashboard.charts.testimonialCount')
@include('vendor::dashboard.charts.feedbackCount')
@include('vendor::dashboard.charts.testimonialRating')
@include('vendor::dashboard.charts.feedbackRating')
<!-- /Chart JS files -->

<script>
    $(function () {

        var mapboxAccessToken = @json(config('services.mapbox.access_token'));

        // OpenStreetMap Init
        var mymap = L.map('map').setView([7.8976, 80.6337], 8); // var mymap = L.map('map').setView([7.2906, 80.6337], 7);
        if (mapboxAccessToken) {
            L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery &copy; <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 18,
                tileSize: 512,
                zoomOffset: -1,
                id: 'mapbox/streets-v11',
                accessToken: mapboxAccessToken
            }).addTo(mymap);
        } else {
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors',
                maxZoom: 18,
            }).addTo(mymap);
        }
        @foreach($mapdata as $response)

            var marker = L.marker([{{$response->latitude}}, {{$response->longitude}}]).addTo(mymap)
                            .bindPopup('<i class="fa fa-user"></i> <strong> @if(!empty($response->employee->name)){{$response->employee->name}}@endif </strong> @if(!empty($response->employee->emp_id))({{$response->employee->emp_id}})@endif <br> <i class="fa fa-comments"></i> {{ $response->response_type }} @if(!empty($response->geo_address)) <br> <i class="fa fa-clock-o"></i> {{$response->created_at}} <br> <i class="fa fa-map-marker"></i> {{$response->geo_address}} @endif');
        @endforeach
        setTimeout(function(){ mymap.invalidateSize(true); }, 500);

    });

</script>
@endsection

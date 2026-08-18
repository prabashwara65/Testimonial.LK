@extends('layouts.frontend')

@section('content')

    {{-- Dashboard --}}
    <div class="dashboard container-fluid second-page" id="dashboard">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 pl-0">
                    <div class="row-title">Dashboard</div>
                </div>
            </div>

            <div class="row">
                <div class="form-holder">
                    <form role="form">
                        <div class="form-row">
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label for="startDatePicker">Select Start Date</label>
                                    <div class='input-group date' id='startDatePicker'>
                                        <input type='text' class="form-control h-inherit"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <div class="form-group">
                                    <label for="endDatePicker">Select End Date</label>
                                    <div class='input-group date' id='endDatePicker'>
                                        <input type='text' class="form-control h-inherit"/>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <label for="selectCampaign" >Select Campaign</label>
                                <select id="selectCampaign" class="form-control custom-select">
                                    <option>select</option>
                                    <option>select</option>
                                    <option>select</option>
                                    <option>select</option>
                                    <option>select</option>
                                </select>
                            </div>

                            <div class="col-xs-12 col-sm-6 col-md-3">
                                <label for="inputLocation" >Location</label>
                                <input id="inputLocation" type="text" class="form-control h-inherit" disabled>
                            </div>
                        </div>

                        <div class="col-xs-12">
                            <a href="{{ url('/create-customer-testimonials') }}" type="button" class="hbtn hbtn-blue">Collect New</a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="row mt-40">
                <div class="col-xs-12 col-sm-6">
                    <p class="mt-0 mb-10 f20 fw-600 text-color">Target Details</p>

                    <canvas id="targetDetailsChart" class="w-100"></canvas>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <div class="form-row">
                        <div class="col-xs-12">
                            <label for="inputCurrentEarnings" class="fw-700 f20 red-color">Current Earnings</label>
                            <input id="inputCurrentEarnings" type="text" class="form-control h-inherit fw-700 f20" placeholder="Rs 200,000.00" disabled>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-60">
                <div class="col-xs-12 col-sm-6">
                    <p class="mt-0 mb-10 f20 fw-600 text-color">Testimonial Total Count <span class="f24 fw-700 red-color ml-5">7</span></p>

                    <canvas id="testimonialTotalChart" class="w-100"></canvas>
                </div>
                <div class="col-xs-12 col-sm-6">
                    <p class="mt-0 mb-10 f20 fw-600 text-color">Feedback Total Count <span class="f24 fw-700 red-color ml-5">19</span></p>

                    <canvas id="feedbackTotalChart" class="w-100"></canvas>
                </div>
            </div>

            <div class="row mt-60">
                <div class="col-xs-12">
                    <p class="mt-0 mb-10 f20 fw-600 text-color">Testimonial Type and Rating Wise Chart View (Monthly)</p>
                </div>

                <div class="col-xs-12 col-sm-6">
                    <canvas id="testimonialTypeChart" class="w-100"></canvas>
                </div>

                <div class="col-xs-12 col-sm-6">
                    <canvas id="testimonialRatingChart" class="w-100"></canvas>
                </div>
            </div>

            <div class="row mt-60">
                <div class="col-xs-12">
                    <p class="mt-0 mb-10 f20 fw-600 text-color">Feedback Type and Rating Wise Chart View (Monthly)</p>
                </div>

                <div class="col-xs-12 col-sm-6">
                    <canvas id="feedbackTypeChart" class="w-100"></canvas>
                </div>

                <div class="col-xs-12 col-sm-6">
                    <canvas id="feedbackRatingChart" class="w-100"></canvas>
                </div>
            </div>
        </div>
    </div>

@endsection

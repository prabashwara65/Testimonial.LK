@extends('layouts.vendor')

@section('content')

    {{-- Dashboard --}}
    <div class="dashboard container-fluid second-page" id="dashboard">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 pl-0">
                    <div class="row-title">{{ $title }}</div>
                </div>
            </div>

            @if (count($campaigns) > 0)
                
                <div class="row">
                    <div class="form-holder">
                        {{ Form::open(array('url' => route('vendor.salesrep.dashboard.filters'), 'class'=>'text-form')) }}

                        <div class="col-xs-12 col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="startDatePicker">Earning Start Date</label>
                                <div class='input-group date' id='startDatePicker'>
                                    <input type='text' name="start_date" class="form-control h-inherit" value="{{ $start_date }}"/>
                                    <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-3">
                            <div class="form-group">
                                <label for="endDatePicker">Earning End Date</label>
                                <div class='input-group date' id='endDatePicker'>
                                    <input type='text' name="end_date" class="form-control h-inherit" value="{{ $end_date }}"/>
                                    <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-6 col-md-2">
                            <button type="submit" class="hbtn hbtn-blue">Show</button>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="row">
                            <div class="col-xs-12">
                                <label for="inputCurrentEarnings" class="fw-700 f20 red-color">Earnings</label>
                                <input id="inputCurrentEarnings" type="text" class="form-control h-inherit fw-700 f20" placeholder="Rs {{ $earning }}" disabled>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row mt-40">
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-holder">
                            {{ Form::open(array('url' => route('vendor.salesrep.dashboard.filters'), 'class'=>'text-form')) }}
                            <div class="row">
                                <div class="col-xs-12">
                                    <label for="campaign" >Select Campaign</label>
                                    <select name="campaign" id="campaign" class="form-control custom-select" onchange="this.form.submit()">
                                        @foreach($campaigns as $campaign)
                                            <option value="{{ $campaign->id }}" {{ ($campaign->id == $campaign_id) ? 'selected' : '' }}>{{ $campaign->campaign_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <p class="mt-0 mb-10 f20 fw-600 text-color">Target Details</p>

                        <canvas id="targetDetailsChart" class="w-100"></canvas>
                    </div>
                </div>

                <div class="row mt-60">
                    <div class="col-xs-12 col-sm-6">
                        <p class="mt-0 mb-10 f20 fw-600 text-color">Testimonial Total Count <span class="f24 fw-700 red-color ml-5">{{ $testimonial_total_count['total'] }}</span></p>

                        <canvas id="testimonialTotalChart" class="w-100"></canvas>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <p class="mt-0 mb-10 f20 fw-600 text-color">Feedback Total Count <span class="f24 fw-700 red-color ml-5">{{ $feedback_total_count['total'] }}</span></p>

                        <canvas id="feedbackTotalChart" class="w-100"></canvas>
                    </div>
                </div>

                <div class="row mt-60">
                    <div class="col-xs-12">
                        <p class="mt-0 mb-10 f20 fw-600 text-color">Testimonial Type and Rating Wise Chart View</p>
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
                        <p class="mt-0 mb-10 f20 fw-600 text-color">Feedback Type and Rating Wise Chart View</p>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <canvas id="feedbackTypeChart" class="w-100"></canvas>
                    </div>

                    <div class="col-xs-12 col-sm-6">
                        <canvas id="feedbackRatingChart" class="w-100"></canvas>
                    </div>
                </div>
            
            @else

                <div class="row">
                    <div class="col-xs-12 pl-0">
                        <h4>You are not assigned to any campaign yet</h4>
                    </div>
                </div>

            @endif
        </div>
    </div>

@endsection

@if (count($campaigns) > 0)
    @section('script')
        <script>
            //Dashboard Charts

            var targetLabels = [@foreach($target['target_achieved'] as $key => $count) {!! "'" . ucfirst($key) . "'" !!}, @endforeach];
            var targetAchievedData = [@foreach($target['target_achieved'] as $count) {{ $count }}, @endforeach];
            var targetRemainingData = [@foreach($target['target_remaining'] as $count) {{ $count }}, @endforeach];

            new Chart("targetDetailsChart", {
                type: "bar",
                data: {
                    labels: targetLabels,
                    datasets: [{
                        label: 'Target Achieved',
                        data: targetAchievedData,
                        backgroundColor: '#64bd63'
                    },
                    {
                        label: 'Target Remaining',
                        data: targetRemainingData,
                        backgroundColor: '#c2c2c2'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            stacked: true,
                        },
                        y: {
                            stacked: true
                        }
                    }
                }
            });

            var testimonialTotalLabels = ['Approved', 'Rejected', 'Pending'];
            var testimonialTotalData = [{{ $testimonial_total_count['approved'] }}, {{ $testimonial_total_count['reject'] }}, {{ $testimonial_total_count['pending'] }}];
            var testimonialTotalColors = ['#64bd63', '#f21877', '#f24b18'];

            new Chart("testimonialTotalChart", {
                type: "doughnut",
                data: {
                    labels: testimonialTotalLabels,
                    datasets: [{
                        label: 'My First Dataset',
                        data: testimonialTotalData,
                        backgroundColor: testimonialTotalColors,
                        hoverOffset: 4
                    }]
                },
            });

            var feedbackTotalLabels = ['Approved', 'Rejected', 'Pending'];
            var feedbackTotalData = [{{ $feedback_total_count['approved'] }}, {{ $feedback_total_count['reject'] }}, {{ $feedback_total_count['pending'] }}];
            var feedbackTotalColors = ['#64bd63', '#f21877', '#f24b18'];

            new Chart("feedbackTotalChart", {
                type: "doughnut",
                data: {
                    labels: feedbackTotalLabels,
                    datasets: [{
                        label: 'My First Dataset',
                        data: feedbackTotalData,
                        backgroundColor: feedbackTotalColors,
                        hoverOffset: 4
                    }]
                },
            });

            var testimonialTypeLabels = ["Video", "Audio", "Image", "Text", "Questionnaires"];
            var testimonialTypeData = [@foreach($testimonial_type_count as $count) {{ $count }}, @endforeach];
            var testimonialTypeColors = ["#324148"];

            new Chart("testimonialTypeChart", {
                type: "bar",
                data: {
                    labels: testimonialTypeLabels,
                    datasets: [{
                        label: 'Testimonial Type',
                        data: testimonialTypeData,
                        backgroundColor: testimonialTypeColors
                    }]
                }
            });

            var testimonialRatingLabels = [@for($i=$testimonial_rating_count['ratingScore']; $i>0; $i--) '{{ $i }} Star', @endfor];
            var testimonialRatingData = [@foreach($testimonial_rating_count['ratingCount'] as $count) {{ $count }}, @endforeach];
            var testimonialRatingColors = ["#5993fa"];

            new Chart("testimonialRatingChart", {
                type: "bar",
                data: {
                    labels: testimonialRatingLabels,
                    datasets: [{
                        label: 'Testimonial Rating',
                        data: testimonialRatingData,
                        backgroundColor: testimonialRatingColors
                    }]
                }
            });


            var feedbackTypeLabels = ["Video", "Audio", "Image", "Text", "Questionnaires"];
            var feedbackTypeData = [@foreach($feedback_type_count as $count) {{ $count }}, @endforeach];
            var feedbackTypeColors = ["#324148"];

            new Chart("feedbackTypeChart", {
                type: "bar",
                data: {
                    labels: feedbackTypeLabels,
                    datasets: [{
                        label: 'Feedback Type',
                        data: feedbackTypeData,
                        backgroundColor: feedbackTypeColors
                    }]
                }
            });

            var feedbackRatingLabels = [@for($i=$feedback_rating_count['ratingScore']; $i>0; $i--) '{{ $i }} Star', @endfor];
            var feedbackRatingData = [@foreach($feedback_rating_count['ratingCount'] as $count) {{ $count }}, @endforeach];
            var feedbackRatingColors = ["#5993fa"];

            new Chart("feedbackRatingChart", {
                type: "bar",
                data: {
                    labels: feedbackRatingLabels,
                    datasets: [{
                        label: 'Feedback Rating',
                        data: feedbackRatingData,
                        backgroundColor: feedbackRatingColors
                    }]
                }
            });
        </script>
    @endsection
@endif
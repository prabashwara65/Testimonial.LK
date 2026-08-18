@extends('layouts.vendor')

@section('content')
    <div class="create-testimonials container-fluid second-page" id="createTestimonials">
        <div class="container">
            <div class="row">
                <section class="col-xs-12">
                    <div class="wizard">
                        @include('vendor::salesrep.response.nav-wizard')

                        <div class="form-holder">
                            <form action="{{ route('response.step-three.post') }}" method="POST" role="form">
                                @csrf
                                <div class="tab-content">
                                    <div class="tab-pane wizard-tab-pane active" role="tabpanel">

                                        @isset($error)<div class="alert alert-danger" role="alert"><strong>{{ $error }}</strong></div>@endisset

                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-4">
                                                <label for="mobile" >Customer mobile number</label>
                                                <input id="mobile" name="mobile" type="text" class="form-control h-inherit" value="{{ $mobile }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-4">
                                                <label for="otp_code" >Customer will get a OTP by SMS</label>
                                                <input id="otp_code" name="otp_code" type="number" class="form-control h-inherit">
                                            </div>
                                        </div>

                                        <div class="col-xs-12">
                                            <ul class="list-inline pull-right mt-20">
                                                <li><button type="submit" name="submitbutton" value="resend" class="hbtn hbtn-prev prev-step mb-10 mb-sm-0">Resend OTP</button></li>
                                                <li><button type="submit" name="submitbutton" value="submit" class="hbtn hbtn-blue next-step">Save and continue</button></li>
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

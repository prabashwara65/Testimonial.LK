@extends('layouts.vendor')

@section('content')
    <div class="create-testimonials container-fluid second-page" id="createTestimonials">
        <div class="container">
            <div class="row">
                <section class="col-xs-12">
                    <div class="wizard">
                        @include('vendor::salesrep.response.nav-wizard')

                        <div class="form-holder">
                            <form action="{{ route('response.step-one.post') }}" method="POST" role="form">
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
                                            <div class="col-xs-12 col-sm-6 col-md-6">
                                                <label for="nic" >Customer NIC <sup class="red-color">*</sup></label>
                                                <input id="nic" name="nic" type="text" class="form-control h-inherit" value="{{ old('nic') }}">
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

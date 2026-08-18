@extends('layouts.frontend')

@section('content')
    <div class="create-testimonials container-fluid second-page" id="createTestimonials">
        <div class="container">
            <div class="row">
                <section class="col-xs-12">
                    <div class="wizard sales-rep-wizard">
                        <div class="wizard-inner">
                            <div class="connecting-line"></div>
                            <ul class="nav nav-tabs wizard-tabs" role="tablist">

                                <li role="presentation" class="active">
                                    <a href="#step1" class="d-flex align-center" data-toggle="tab" aria-controls="step1" role="tab" title="Step 1">
                                        <span class="round-tab mr-10">
                                            1
                                        </span>
                                        <span class="f14 fw-500">Manage Customer</span>
                                    </a>
                                </li>

                                <li role="presentation" class="disabled">
                                    <a href="#step2" class="d-flex align-center" data-toggle="tab" aria-controls="step2" role="tab" title="Step 2">
                                        <span class="round-tab mr-10">
                                            2
                                        </span>
                                        <span class="f14 fw-500">OTP Verification</span>
                                    </a>
                                </li>

                                <li role="presentation" class="disabled">
                                    <a href="#step3" class="d-flex align-center" data-toggle="tab" aria-controls="step3" role="tab" title="Step 3">
                                        <span class="round-tab mr-10">
                                            3
                                        </span>
                                        <span class="f14 fw-500">Customer Registration</span>
                                    </a>
                                </li>

                                <li role="presentation" class="disabled">
                                    <a href="#step4" class="d-flex align-center" data-toggle="tab" aria-controls="step4" role="tab" title="Step 4">
                                        <span class="round-tab mr-10">
                                            4
                                        </span>
                                        <span class="f14 fw-500">Select Product</span>
                                    </a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a href="#step5" class="d-flex align-center" data-toggle="tab" aria-controls="step5" role="tab" title="Step 5">
                                        <span class="round-tab mr-10">
                                            5
                                        </span>
                                        <span class="f14 fw-500">Feedback or Testimonial</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="form-holder">
                            <form role="form">
                                <div class="tab-content">
                                    <div class="tab-pane wizard-tab-pane active" role="tabpanel" id="step1">

                                        <div class="form-row">
                                            <div class="col-xs-12 col-sm-6 col-md-3">
                                                <label for="selectCampaign" >Select Campaign</label>
                                                <select id="selectCampaign" class="form-control custom-select">
                                                    <option>Select</option>
                                                    <option>Select</option>
                                                    <option>Select</option>
                                                    <option>Select</option>
                                                    <option>Select</option>
                                                </select>
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-3">
                                                <label for="inputPhoneNumberEmail" >Phone Number or Email</label>
                                                <input id="inputPhoneNumberEmail" type="text" class="form-control h-inherit">
                                            </div>
                                        </div>

                                        <div class="col-xs-12">
                                            <ul class="list-inline pull-right mt-20">
                                                <li><button type="button" class="hbtn hbtn-blue next-step">Save and continue</button></li>
                                            </ul>
                                        </div>
                                    </div>


                                    <div class="tab-pane wizard-tab-pane" role="tabpanel" id="step2">

                                        <div class="form-row">
                                            <div class="col-xs-12 col-sm-6 col-md-4">
                                                <label for="inputOTPNumber" >You will get a OTP by SMS or Email</label>
                                                <input id="inputOTPNumber" type="number" class="form-control h-inherit">
                                            </div>
                                        </div>

                                        <div class="col-xs-12">
                                            <ul class="list-inline pull-right mt-20">
                                                <li><button type="button" class="hbtn hbtn-prev prev-step mb-10 mb-sm-0">Previous</button></li>
                                                <li><button type="button" class="hbtn hbtn-blue next-step">Save and continue</button></li>
                                            </ul>
                                        </div>
                                    </div>


                                    <div class="tab-pane wizard-tab-pane" role="tabpanel" id="step3">
                                        <div class="form-row">
                                            <div class="col-xs-12 col-sm-6 col-md-3">
                                                <label for="inputFirstName" >First Name <sup class="red-color">*</sup></label>
                                                <input id="inputFirstName" type="text" class="form-control h-inherit" required>
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-3">
                                                <label for="inputLastName" >Last Name <sup class="red-color">*</sup></label>
                                                <input id="inputLastName" type="text" class="form-control h-inherit" required>
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-3">
                                                <label for="inputNIC" >NIC <sup class="red-color">*</sup></label>
                                                <input id="inputNIC" type="text" class="form-control h-inherit" required>
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-3">
                                                <label for="inputEmail" >Email <sup class="red-color">*</sup></label>
                                                <input id="inputEmail" type="email" class="form-control h-inherit" required>
                                            </div>

                                        </div>
                                        <div class="form-row">
                                            <div class="col-xs-12 col-sm-6">
                                                <label for="inputAddress" >Address</label>
                                                <input id="inputAddress" type="text" class="form-control h-inherit">
                                            </div>

                                            <div class="col-xs-12 col-sm-6">
                                                <label for="inputAddress1" >Address Line 1</label>
                                                <input id="inputAddress1" type="text" class="form-control h-inherit">
                                            </div>
                                        </div>

                                        <div class="form-row">
                                            <div class="col-xs-12 col-sm-6 col-md-3">
                                                <label for="inputPhoneNumber" >Phone Number</label>
                                                <input id="inputPhoneNumber" type="number" class="form-control h-inherit">
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-3">
                                                <label for="inputRegion" >Region</label>
                                                <input id="inputRegion" type="text" class="form-control h-inherit">
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-3">
                                                <label for="selectCountry" >Select Country</label>
                                                <select id="selectCountry" class="form-control custom-select">
                                                    <option>Sri-Lanka</option>
                                                    <option>India</option>
                                                    <option>England</option>
                                                    <option>America</option>
                                                    <option>Japan</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-xs-12">
                                            <ul class="list-inline pull-right mt-20">
                                                <li><button type="button" class="hbtn hbtn-prev prev-step mb-10 mb-sm-0">Previous</button></li>
                                                <li><button type="button" class="hbtn hbtn-blue next-step">Save and continue</button></li>
                                            </ul>
                                        </div>
                                    </div>


                                    <div class="tab-pane wizard-tab-pane" role="tabpanel" id="step4">
                                        <div class="form-row row">
                                            <div class="col-xs-12 col-sm-6 col-md-3">
                                                <label for="selectCompany" >Select Company <sup class="red-color">*</sup></label>
                                                <select id="selectCompany" class="form-control custom-select">
                                                    <option>Company 1</option>
                                                    <option>Company 2</option>
                                                    <option>Company 3</option>
                                                    <option>Company 4</option>
                                                </select>
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-3">
                                                <label for="selectProduct" >Select Product <sup class="red-color">*</sup></label>
                                                <select id="selectProduct" class="form-control custom-select">
                                                    <option>Product 1</option>
                                                    <option>Product 2</option>
                                                    <option>Product 3</option>
                                                    <option>Product 4</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-row row">
                                            <div class="col-xs-12 col-sm-6 col-md-3">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="optionsRadios" id="testimonialRadio" value="option1" checked>
                                                        Testimonial
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-xs-12 col-sm-6 col-md-3">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="optionsRadios" id="feedbackRadio" value="option1" >
                                                        Feedback
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xs-12">
                                            <ul class="list-inline pull-right mt-20">
                                                <li><button type="button" class="hbtn hbtn-prev prev-step mb-10 mb-sm-0">Previous</button></li>
                                                <li><button type="button" class="hbtn hbtn-blue next-step">Save and continue</button></li>
                                            </ul>
                                        </div>
                                    </div>


                                    <div class="tab-pane wizard-tab-pane" role="tabpanel" id="step5">
                                        <div class="col-xs-12">
                                            <!-- Nav tabs -->
                                            <ul class="nav nav-tabs wizard-custom-tabs" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#record" class="d-flex align-center wizard-custom-tab-1" aria-controls="record" role="tab" data-toggle="tab">
                                                        <div class="custom-tab-radio custom-tab-radio-active custom-tab-radio-1 mr-10"></div> Record
                                                    </a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#questionnaire" class="d-flex align-center wizard-custom-tab-2" aria-controls="questionnaire" role="tab" data-toggle="tab">
                                                        <div class="custom-tab-radio custom-tab-radio-2 mr-10"></div> Questionnaire
                                                    </a>
                                                </li>
                                            </ul>

                                            <!-- Tab panes -->
                                            <div class="tab-content mt-20">
                                                <div role="tabpanel" class="tab-pane active" id="record">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-lg-6 mb-20">
                                                            <div class="row">
                                                                <div class="col-xs-2 col-sm-1">
                                                                    <div class="checkbox m-0">
                                                                        <label class="p-0">
                                                                            <input type="checkbox" class="m-0" value="">
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-4 col-sm-2">
                                                                    <p class="f14 fw-400 text-color">Video</p>
                                                                </div>
                                                                <div class="col-xs-6 col-sm-3">
                                                                    <input type="file" class="custom-file-input">
                                                                    <p class="help-block f10 mt-2 mb-0 pl-5"> Max 15MB</p>
                                                                </div>
                                                                <div class="col-xs-4 col-sm-2">
                                                                    <a href="#"><i class="fas fa-video f16 text-color"></i></a>
                                                                </div>
                                                                <div class="col-xs-4 col-sm-2">
                                                                    <a href="#" data-toggle="modal" data-target="#filesViewModal"><i class="fas fa-eye f16 text-color"></i></a>
                                                                </div>
                                                                <div class="col-xs-4 col-sm-2">
                                                                    <a href="#"><i class="fas fa-upload f16 text-color"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-xs-12 col-lg-6 mb-20">
                                                            <div class="row">
                                                                <div class="col-xs-2 col-sm-1">
                                                                    <div class="checkbox m-0">
                                                                        <label class="p-0">
                                                                            <input type="checkbox" class="m-0" value="">
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-4 col-sm-2">
                                                                    <p class="f14 fw-400 text-color">Audio</p>
                                                                </div>
                                                                <div class="col-xs-6 col-sm-3">
                                                                    <input type="file" class="custom-file-input">
                                                                    <p class="help-block f10 mt-2 mb-0 pl-5"> Max 15MB</p>
                                                                </div>
                                                                <div class="col-xs-4 col-sm-2">
                                                                    <a href="#"><i class="fas fa-microphone f16 text-color"></i></a>
                                                                </div>
                                                                <div class="col-xs-4 col-sm-2">
                                                                    <a href="#" data-toggle="modal" data-target="#filesViewModal"><i class="fas fa-eye f16 text-color"></i></a>
                                                                </div>
                                                                <div class="col-xs-4 col-sm-2">
                                                                    <a href="#"><i class="fas fa-upload f16 text-color"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-xs-12 col-lg-6 mb-20">
                                                            <div class="row">
                                                                <div class="col-xs-2 col-sm-1">
                                                                    <div class="checkbox m-0">
                                                                        <label class="p-0">
                                                                            <input type="checkbox" class="m-0" value="">
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-4 col-sm-2">
                                                                    <p class="f14 fw-400 text-color">Image</p>
                                                                </div>
                                                                <div class="col-xs-6 col-sm-3">
                                                                    <input type="file" class="custom-file-input">
                                                                    <p class="help-block f10 mt-2 mb-0 pl-5"> Max 15MB</p>
                                                                </div>
                                                                <div class="col-xs-4 col-sm-2">
                                                                    <a href="#"><i class="fas fa-camera f16 text-color"></i></a>
                                                                </div>
                                                                <div class="col-xs-4 col-sm-2">
                                                                    <a href="#" data-toggle="modal" data-target="#filesViewModal"><i class="fas fa-eye f16 text-color"></i></a>
                                                                </div>
                                                                <div class="col-xs-4 col-sm-2">
                                                                    <a href="#"><i class="fas fa-upload f16 text-color"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-xs-12 col-lg-6 mb-20">
                                                            <div class="row">
                                                                <div class="col-xs-2 col-sm-1">
                                                                    <div class="checkbox m-0">
                                                                        <label class="p-0">
                                                                            <input type="checkbox" class="m-0" value="">
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                                <div class="col-xs-4 col-sm-2">
                                                                    <p class="f14 fw-400 text-color">Text</p>
                                                                </div>
                                                                <div class="col-xs-6 col-sm-3">
                                                                    <input type="file" class="custom-file-input">
                                                                    <p class="help-block f10 mt-2 mb-0 pl-5"> Max 15MB</p>
                                                                </div>
                                                                <div class="col-xs-4 col-sm-2">

                                                                </div>
                                                                <div class="col-xs-4 col-sm-2">
                                                                    <a href="#" data-toggle="modal" data-target="#filesViewModal"><i class="fas fa-eye f16 text-color"></i></a>
                                                                </div>
                                                                <div class="col-xs-4 col-sm-2">
                                                                    <a href="#"><i class="fas fa-upload f16 text-color"></i></a>
                                                                </div>
                                                            </div>

                                                            <div class="row">
                                                                <div class="col-sm-3"></div>
                                                                <div class="col-xs-12 col-sm-9">
                                                                    <div class="form-group mt-10 mb-0">
                                                                        <textarea class="form-control" rows="1" placeholder="Text here..."></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div role="tabpanel" class="tab-pane" id="questionnaire">
                                                    <div class="col-xs-12 p-0 questionnaire-questions">
                                                        <p class="m-0 f16 fw-600 text-color">Question 1</p>

                                                        <div class="answers">
                                                            <div class="radio">
                                                                <label>
                                                                    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1">
                                                                    Answers 1
                                                                </label>
                                                            </div>
                                                            <div class="radio">
                                                                <label>
                                                                    <input type="radio" name="optionsRadios" id="optionsRadios2" value="option2">
                                                                    Answers 2
                                                                </label>
                                                            </div>
                                                            <div class="radio">
                                                                <label>
                                                                    <input type="radio" name="optionsRadios" id="optionsRadios3" value="option3">
                                                                    Answers 3
                                                                </label>
                                                            </div>

                                                            <div class="radio">
                                                                <label>
                                                                    <input type="radio" name="optionsRadios" id="optionsRadios4" value="option4">
                                                                    Answers 4
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 p-0 questionnaire-questions mt-20">
                                                        <p class="m-0 f16 fw-600 text-color">Question 2</p>

                                                        <div class="answers">
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" value="">
                                                                    Answer 1
                                                                </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" value="">
                                                                    Answer 2
                                                                </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" value="">
                                                                    Answer 3
                                                                </label>
                                                            </div>
                                                            <div class="checkbox">
                                                                <label>
                                                                    <input type="checkbox" value="">
                                                                    Answer 4
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <ul class="list-inline pull-right mt-20">
                                                <li><button type="button" class="hbtn hbtn-prev prev-step mb-10 mb-sm-0">Previous</button></li>
                                                <li><button type="button" class="hbtn hbtn-blue" data-toggle="modal" data-target="#confirmUploadFileModal">Submit</button></li>
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

    {{--Upload Files View Modal--}}
    <div class="modal fade" id="filesViewModal" tabindex="-1" role="dialog" aria-labelledby="filesViewModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        {{--Image--}}
                        <div class="col-xs-12">
                            <img class="w-100 br-4px" src="https://cdn.pixabay.com/photo/2015/04/23/22/00/tree-736885__480.jpg" alt="uploaded-img">
                        </div>
                        {{--/Image--}}

                        {{--Video--}}
                        <div class="col-xs-12">
                            <video class="w-100 h-100 br-4px" controls playsinline>
                                <source src="https://www.learningcontainer.com/wp-content/uploads/2020/05/sample-mp4-file.mp4" type="video/mp4">
                                <source src="https://www.learningcontainer.com/wp-content/uploads/2020/05/sample-mp4-file.mp4" type="video/ogg">
                            </video>
                        </div>
                        {{--/Video--}}

                        {{--Audio--}}
                        <div class="col-xs-12">
                            <audio class="w-100" controls>
                                <source src="https://samplelib.com/lib/preview/mp3/sample-3s.mp3" type="audio/ogg">
                                <source src="https://samplelib.com/lib/preview/mp3/sample-3s.mp3" type="audio/mpeg">
                            </audio>
                        </div>
                        {{--/Audio--}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="hbtn hbtn-prev" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{--Confirm Upload Files Modal--}}
    <div class="modal fade" id="confirmUploadFileModal" tabindex="-1" role="dialog" aria-labelledby="confirmUploadFileModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 pt-20 pb-20">
                            <p class="m-0 f16 fw-400 text-color">"Are you sure you want to upload the details?"</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="hbtn hbtn-prev" data-dismiss="modal">No</button>
                    <button type="button" class="hbtn hbtn-blue" data-toggle="modal" data-target="#thankyouUploadFileModal" data-dismiss="modal">Yes</button>
                </div>
            </div>
        </div>
    </div>

    {{--Thank You Upload Files Modal--}}
    <div class="modal fade" id="thankyouUploadFileModal" tabindex="-1" role="dialog" aria-labelledby="thankyouUploadFileModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12 pt-20 pb-20">
                            <p class="m-0 f16 fw-400 text-color">Thank you for uploading your valuable feedback!</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="hbtn hbtn-blue" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

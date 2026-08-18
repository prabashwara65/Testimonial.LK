@extends('layouts.frontend')

@section('content')
    <div class="my-testimonials container-fluid second-page" id="myTestimonials">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-left pl-0">
                    <div class="row-title">History</div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 mt-20">
                    <div class="table-responsive">
                        <table class="table table-condensed table-hover table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Company Name</th>
                                    <th scope="col">Product</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>10/15/2021</th>
                                    <th>Testimonial</th>
                                    <th>Pizza Hut</th>
                                    <th>Pizza| Cheese Pizza</th>
                                    <th class="d-flex justify-center"><a href="#" class="table-view-btn" data-toggle="modal" data-target="#customerRecordAnswerModal"><i class="fas fa-eye"></i></a></th>
                                </tr>
                                <tr>
                                    <th>10/15/2021</th>
                                    <th>Feedback</th>
                                    <th>Pizza Hut</th>
                                    <th>Pizza| Cheese Pizza</th>
                                    <th class="d-flex justify-center"><a href="#" class="table-view-btn" data-toggle="modal" data-target="#customerQuestionnaireAnswerModal"><i class="fas fa-eye"></i></a></th>
                                </tr>
                                <tr>
                                    <th>10/15/2021</th>
                                    <th>Feedback</th>
                                    <th>Pizza Hut</th>
                                    <th>Pizza| Cheese Pizza</th>
                                    <th class="d-flex justify-center"><a href="#" class="table-view-btn"><i class="fas fa-eye"></i></a></th>
                                </tr>
                                <tr>
                                    <th>10/15/2021</th>
                                    <th>Feedback</th>
                                    <th>Pizza Hut</th>
                                    <th>Pizza| Cheese Pizza</th>
                                    <th class="d-flex justify-center"><a href="#" class="table-view-btn"><i class="fas fa-eye"></i></a></th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Record Answer Modal--}}
    <div class="modal fade" id="customerRecordAnswerModal" tabindex="-1" role="dialog" aria-labelledby="customerRecordAnswerModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title text-color" id="customerRecordAnswerModal">Details of Testimonial</h4>
                </div>

                <div class="modal-body">
                    <div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-600 text-color">Date</p>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-400 text-color">2021-12-22</p>
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-600 text-color">Type</p>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-400 text-color">Testimonial</p>
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-600 text-color">Company Name</p>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-400 text-color">Pizza Hut</p>
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-600 text-color">Product</p>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-400 text-color">Cheese Pizza</p>
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-600 text-color">Input Source</p>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-400 text-color">Web</p>
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-600 text-color">Status</p>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-700 orange-color">Pending</p>
                                <p class="m-0 f14 fw-700 green-color">Approved</p>
                                <p class="m-0 f14 fw-700 red-color">Rejected</p>
                            </div>
                        </div>

                        <div class="row mt-10">
                            <div class="col-xs-6">
                                <p class="m-0 f14 fw-600 red-color">Rejected</p>
                            </div>
                            <div class="col-xs-6">
                                <p class="m-0 f14 fw-700 text-color">Reject Reason</p>
                            </div>
                        </div>

                        <div class="row mt-10">
                            <div class="col-xs-6">
                                <p class="m-0 f14 fw-600 text-color">Location</p>
                            </div>
                            <div class="col-xs-6">
                                <a href="#" class="m-0 f14 fw-400 text-color">Location</a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-20 pt-20 bt-1px">

                        <div class="row">
                            <div class="col-xs-12 mb-20">
                                <h4 class="m-0 text-color">Answers</h4>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-xs-4">
                                <p class="m-0 f14 fw-600 text-color">Questioner</p>
                            </div>
                            <div class="col-xs-4 d-flex justify-center">
                                <a href="#" data-toggle="modal" data-target="#customerTestimonialFeedbackAssetModal"><span><i class="f26 text-color fas fa-eye"></i></span></a>
                            </div>
                            <div class="col-xs-4 d-flex justify-center">
                                <a href="#"><span><i class="f26 text-color fas fa-download"></i></span></a>
                            </div>
                        </div>

                        <div class="row mt-20">
                            <div class="col-xs-4">
                                <p class="m-0 f14 fw-600 text-color">Video</p>
                            </div>
                            <div class="col-xs-4 d-flex justify-center">
                                <a href="#" data-toggle="modal" data-target="#customerTestimonialFeedbackAssetModal"><span><i class="f26 text-color fas fa-play-circle"></i></span></a>
                            </div>
                            <div class="col-xs-4 d-flex justify-center">
                                <a href="#"><span><i class="f26 text-color fas fa-download"></i></span></a>
                            </div>
                        </div>

                        <div class="row mt-20">
                            <div class="col-xs-4">
                                <p class="m-0 f14 fw-600 text-color">Audio</p>
                            </div>
                            <div class="col-xs-4 d-flex justify-center">
                                <a href="#" data-toggle="modal" data-target="#customerTestimonialFeedbackAssetModal"><span><i class="f26 text-color fas fa-play-circle"></i></span></a>
                            </div>
                            <div class="col-xs-4 d-flex justify-center">
                                <a href="#"><span><i class="f26 text-color fas fa-download"></i></span></a>
                            </div>
                        </div>

                        <div class="row mt-20">
                            <div class="col-xs-4">
                                <p class="m-0 f14 fw-600 text-color">Image</p>
                            </div>
                            <div class="col-xs-4 d-flex justify-center">
                                <a href="#" data-toggle="modal" data-target="#customerTestimonialFeedbackAssetModal"><span><i class="f26 text-color fas fa-eye"></i></span></a>
                            </div>
                            <div class="col-xs-4 d-flex justify-center">
                                <a href="#"><span><i class="f26 text-color fas fa-download"></i></span></a>
                            </div>
                        </div>

                        <div class="row mt-20">
                            <div class="col-xs-4">
                                <p class="m-0 f14 fw-600 text-color">Text</p>
                            </div>
                            <div class="col-xs-4 d-flex justify-center">
                                <a href="#" data-toggle="modal" data-target="#customerTestimonialFeedbackAssetModal"><span><i class="f26 text-color fas fa-eye"></i></span></a>
                            </div>
                            <div class="col-xs-4 d-flex justify-center">
                                <a href="#"><span><i class="f26 text-color fas fa-download"></i></span></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="hbtn hbtn-prev" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>

    {{--Testimonial Feedback Asset Modal--}}
    <div class="modal fade" id="customerTestimonialFeedbackAssetModal" tabindex="-1" role="dialog" aria-labelledby="customerTestimonialFeedbackAssetModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">

                        {{--Questioner--}}
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-12 questionnaire-questions">
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

                                <div class="col-xs-12 questionnaire-questions mt-20">
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
                        {{--/Questioner--}}


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

                        {{--Text--}}
                        <div class="col-xs-12">
                            <p class="m-0 f16 fw-400 text-color">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus dolor dolores facere inventore maxime modi nam necessitatibus nulla porro, quidem rem sit, voluptatem voluptatibus.</p>
                        </div>
                        {{--/Text--}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="hbtn hbtn-prev" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

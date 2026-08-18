@extends('layouts.frontend')

@section('content')
    <div class="my-testimonials container-fluid second-page" id="myTestimonials">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 text-left pl-0">
                    <div class="row-title">Testimonials and Feedback Collection</div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="form-holder">
                        <form role="form">
                            <div class="form-row row">
                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <label for="selectType" >Select Type</label>
                                    <select id="selectType" class="form-control custom-select">
                                        <option>Testimonial</option>
                                        <option>Feedback</option>
                                    </select>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <label for="selectProductName" >Select Product Name</label>
                                    <select id="selectProductName" class="form-control custom-select">
                                        <option>Select</option>
                                        <option>Select</option>
                                    </select>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <label for="selectSubProductName" >Select Sub Product Name</label>
                                    <select id="selectSubProductName" class="form-control custom-select">
                                        <option>Select</option>
                                        <option>Select</option>
                                    </select>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <label for="selectStatus" >Select Status</label>
                                    <select id="selectStatus" class="form-control custom-select">
                                        <option>Select</option>
                                        <option>Select</option>
                                    </select>
                                </div>

                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <label for="selectRating" >Select Rating</label>
                                    <select id="selectRating" class="form-control custom-select">
                                        <option>Select</option>
                                        <option>Select</option>
                                    </select>
                                </div>

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

                                <div class="col-xs-12 col-sm-6 col-md-3">
                                    <label class="transparent">Transparent Label</label>
                                    <div>
                                        <button type="button" class="hbtn hbtn-blue">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12 mt-20">
                    <div class="table-responsive">
                        <table id="testimonialFeedbackCollection" class="table table-condensed table-hover table-striped table-bordered w-100">
                            <thead class="thead-dark">
                            <tr>
                                <th>Date</th>
                                <th>NIC</th>
                                <th>Customer Name</th>
                                <th>Type</th>
                                <th>Campaign</th>
                                <th>Product Name</th>
                                <th>Sub Product Name</th>
                                <th>Rating</th>
                                <th>Rewards</th>
                                <th>Input Source</th>
                                <th>Status</th>
                                <th>Details</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>18/10/2021</td>
                                <td>912345678V</td>
                                <td>Dhakshika Gamage</td>
                                <td>Testimonial</td>
                                <td>ABC</td>
                                <td>Pizza</td>
                                <td>Cheese pizza</td>
                                <td>
                                    <div class="d-flex align-center">
                                        <span><i class="fas fa-star"></i></span>
                                        <span><i class="fas fa-star"></i></span>
                                        <span><i class="fas fa-star"></i></span>
                                        <span><i class="fas fa-star"></i></span>
                                    </div>
                                </td>
                                <td>Gift remark</td>
                                <td>Web</td>
                                <td>Approved</td>
                                <td class="d-flex justify-center"><a href="#" class="table-view-btn" data-toggle="modal" data-target="#recordAnswerModal"><i class="fas fa-eye"></i></a></td>
                            </tr>

                            <tr>
                                <td>18/10/2021</td>
                                <td>912345678V</td>
                                <td>Dhakshika Gamage</td>
                                <td>Feedback</td>
                                <td>ABC</td>
                                <td>Pizza</td>
                                <td>Cheese pizza</td>
                                <td>
                                    <div class="d-flex align-center">
                                        <span><i class="fas fa-star"></i></span>
                                        <span><i class="fas fa-star"></i></span>
                                        <span><i class="fas fa-star"></i></span>
                                        <span><i class="fas fa-star"></i></span>
                                    </div>
                                </td>
                                <td>Gift remark</td>
                                <td>Web</td>
                                <td>Approved</td>
                                <td class="d-flex justify-center"><a href="#" class="table-view-btn" data-toggle="modal" data-target="#questionnaireAnswerModal"><i class="fas fa-eye"></i></a></td>
                            </tr>
                            </tbody>
                            <tfoot class="thead-dark">
                            <tr>
                                <th>Date</th>
                                <th>NIC</th>
                                <th>Customer Name</th>
                                <th>Type</th>
                                <th>Campaign</th>
                                <th>Product Name</th>
                                <th>Sub Product Name</th>
                                <th>Rating</th>
                                <th>Rewards</th>
                                <th>Input Source</th>
                                <th>Status</th>
                                <th>Details</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--Record Answer Modal--}}
    <div class="modal fade" id="recordAnswerModal" tabindex="-1" role="dialog" aria-labelledby="recordAnswerModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title text-color" id="recordAnswerModalLabel">Record Answer</h4>
                </div>

                <div class="modal-body">
                    <div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-600 text-color">Customer Name</p>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-400 text-color">Dhakshika Gamage</p>
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-600 text-color">Address</p>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-400 text-color">Borupana road, Rathmalana</p>
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-600 text-color">Phone Number</p>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-400 text-color">0771234567</p>
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-600 text-color">Email</p>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-400 text-color">dhakshi@gmail.com</p>
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-600 text-color">Region</p>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-400 text-color">Colombo</p>
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-600 text-color">Country</p>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-400 text-color">Sri Lanka</p>
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
                                <p class="m-0 f14 fw-600 text-color">Video</p>
                            </div>
                            <div class="col-xs-4 d-flex justify-center">
                                <a href="#" data-toggle="modal" data-target="#testimonialFeedbackAssetModal"><span><i class="f26 text-color fas fa-play-circle"></i></span></a>
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
                                <a href="#" data-toggle="modal" data-target="#testimonialFeedbackAssetModal"><span><i class="f26 text-color fas fa-play-circle"></i></span></a>
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
                                <a href="#" data-toggle="modal" data-target="#testimonialFeedbackAssetModal"><span><i class="f26 text-color fas fa-eye"></i></span></a>
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
                                <a href="#" data-toggle="modal" data-target="#testimonialFeedbackAssetModal"><span><i class="f26 text-color fas fa-eye"></i></span></a>
                            </div>
                            <div class="col-xs-4 d-flex justify-center">
                                <a href="#"><span><i class="f26 text-color fas fa-download"></i></span></a>
                            </div>
                        </div>
                    </div>

                    <div class="mt-20 pt-20 bt-1px">
                        <div class="row">
                            <div class="col-xs-6">
                                <p class="m-0 f14 fw-600 text-color">Status</p>
                            </div>
                            <div class="col-xs-6">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="hbtn hbtn-prev" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>

    {{--Testimonial Feedback Asset Modal--}}
    <div class="modal fade" id="testimonialFeedbackAssetModal" tabindex="-1" role="dialog" aria-labelledby="testimonialFeedbackAssetModalLabel">
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

    {{--Questionier Modal--}}
    <div class="modal fade" id="questionnaireAnswerModal" tabindex="-1" role="dialog" aria-labelledby="questionnaireAnswerModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title text-color" id="questionnaireAnswerModalLabel">Questionnaire</h4>
                </div>

                <div class="modal-body">
                    <div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-600 text-color">Customer Name</p>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-400 text-color">Dhakshika Gamage</p>
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-600 text-color">Address</p>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-400 text-color">Borupana road, Rathmalana</p>
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-600 text-color">Phone Number</p>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-400 text-color">0771234567</p>
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-600 text-color">Email</p>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-400 text-color">dhakshi@gmail.com</p>
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-600 text-color">Region</p>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-400 text-color">Colombo</p>
                            </div>
                        </div>
                        <div class="row mt-10">
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-600 text-color">Country</p>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="m-0 f14 fw-400 text-color">Sri Lanka</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-20 pt-20 bt-1px">
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

                    <div class="mt-20 pt-20 bt-1px">
                        <div class="row">
                            <div class="col-xs-6">
                                <p class="m-0 f14 fw-600 text-color">Status</p>
                            </div>
                            <div class="col-xs-6">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="hbtn hbtn-prev" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
@endsection

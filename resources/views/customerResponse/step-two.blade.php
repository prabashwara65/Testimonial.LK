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

                                <li role="presentation" class="">
                                    <a href="{{ route('customer-response.step-one') }}" class="d-flex align-center"
                                       data-toggle="tab" aria-controls="step1" role="tab" title="Step 1">
                                        <span class="round-tab mr-10">
                                            1
                                        </span>
                                        <span class="f14 fw-500">Select Product</span>
                                    </a>
                                </li>

                                <li role="presentation" class="active">
                                    <a href="#step2" class="d-flex align-center" data-toggle="tab" aria-controls="step2"
                                       role="tab" title="Step 2">
                                        <span class="round-tab mr-10">
                                            2
                                        </span>
                                        <span class="f14 fw-500">Feedback or Testimonial</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="form-holder">
                            <form id="responseForm">
                                <div class="tab-content">
                                    <div class="tab-pane wizard-tab-pane active" role="tabpanel" id="step2">
                                        <div class="col-xs-12">
                                            <div class="form-row row">
                                                <div class="col-xs-12 col-sm-6 col-md-3">
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="type" id="type" value="1" checked>
                                                            Testimonial
                                                        </label>
                                                    </div>
                                                </div>

                                                <div class="col-xs-12 col-sm-6 col-md-3">
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="type" id="type" value="2">
                                                            Feedback
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Tab panes -->
                                            <div class="tab-content mt-20">
                                                <div role="tabpanel" class="tab-pane active" id="record">
                                                    <div class="row">
                                                        <div class="col-xs-12 col-md-10 mb-20">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                    <p class="f14 fw-400 text-color">Video</p>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="file" name="video" id="video"
                                                                           class="custom-file-input">
                                                                    <p class="help-block f10 mt-2 mb-0 pl-5"> Max
                                                                        15MB</p>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <a href="#" class="recordButton"
                                                                       data-url="{{route('customer-response.video-record')}}"><i
                                                                            class="fas fa-video f16 text-color"></i></a>
                                                                </div>
                                                                {{--
                                                                <div class="col-md-1">
                                                                    <a href="#" data-toggle="modal" data-target="#filesViewModal"><i class="fas fa-eye f16 text-color"></i></a>
                                                                </div>
                                                                <div class="col-md-1">
                                                                     <a href="#"><i class="fas fa-upload f16 text-color"></i></a>
                                                                </div>
                                                                --}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-xs-12 col-md-10 mb-20">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                    <p class="f14 fw-400 text-color">Audio</p>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="file" name="audio" id="audio"
                                                                           class="custom-file-input">
                                                                    <p class="help-block f10 mt-2 mb-0 pl-5"> Max
                                                                        15MB</p>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <a href="#" class="recordButton"
                                                                       data-url="{{route('customer-response.audio-record')}}"><i
                                                                            class="fas fa-microphone f16 text-color"></i></a>
                                                                </div>
                                                                {{--
                                                                <div class="col-md-1">
                                                                    <a href="#" data-toggle="modal" data-target="#filesViewModal"><i class="fas fa-eye f16 text-color"></i></a>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <a href="#"><i class="fas fa-upload f16 text-color"></i></a>
                                                                </div>
                                                                --}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-xs-12 col-md-10 mb-20">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                    <p class="f14 fw-400 text-color">Image</p>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="file" name="image" id="image"
                                                                           class="custom-file-input">
                                                                    <p class="help-block f10 mt-2 mb-0 pl-5"> Max
                                                                        15MB</p>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <a href="#" class="recordButton"
                                                                       data-url="{{route('customer-response.image-record')}}"><i
                                                                            class="fas fa-camera f16 text-color"></i></a>
                                                                </div>
                                                                {{--
                                                                <div class="col-md-1">
                                                                    <a href="#" data-toggle="modal" data-target="#filesViewModal"><i class="fas fa-eye f16 text-color"></i></a>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <a href="#"><i class="fas fa-upload f16 text-color"></i></a>
                                                                </div>
                                                                --}}
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-xs-12 col-md-10 mb-20">
                                                            <div class="row">
                                                                <div class="col-md-1">
                                                                    <p class="f14 fw-400 text-color">Text</p>
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <input type="file" name="text" id="text"
                                                                           class="custom-file-input">
                                                                    <p class="help-block f10 mt-2 mb-0 pl-5"> Max
                                                                        15MB</p>
                                                                </div>
                                                                <div class="col-md-8">
                                                                    <textarea class="form-control" name="textarea"
                                                                              placeholder="Text here..."></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <progress id="progressBar" value="0" max="100" style="width:300px; display: none"></progress>
                                            <div id="status" style="display: none"></div>
                                            <p id="loadedTotal" style="display: none"></p>

                                            <ul class="list-inline pull-right mt-20">
                                                <li><a href="{{ route('customer-response.step-one') }}">
                                                        <button type="button"
                                                                class="hbtn hbtn-prev prev-step mb-10 mb-sm-0">Previous
                                                        </button>
                                                    </a></li>
                                                <li>
                                                    <input type="button" class="hbtn hbtn-blue" value="Submit" onclick="uploadFile()">
                                                </li>
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

@section('script')
    <script>
        $('.xl-modal-holder').on('hidden.bs.modal', function () {
            $('.modal-content').html('');
        });
    </script>

    <script>
        $("textarea").each(function () {
            this.setAttribute("style", "height:" + (this.scrollHeight) + "px;overflow-y:hidden;");
        }).on("input", function () {
            this.style.height = "auto";
            this.style.height = (this.scrollHeight) + "px";
        });
    </script>

    <script>
        let loadedTotal = $("#loadedTotal");
        let progressBar = $("#progressBar");
        let status = $("#status");

        function uploadFile() {
            var formdata = new FormData($("#responseForm")[0]);

            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", completeHandler, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.addEventListener("abort", abortHandler, false);
            ajax.open("POST", "{{ route('customer-response.step-two.post') }}");
            ajax.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            ajax.send(formdata);
        }

        function progressHandler(event) {
            loadedTotal.show();
            loadedTotal.html("Uploaded " + event.loaded + " bytes of " + event.total);

            var percent = (event.loaded / event.total) * 100;

            progressBar.show();
            progressBar.val(Math.round(percent));

            status.show();
            status.html("<h3>" + Math.round(percent) + "% uploaded... please wait</h3>");
        }

        function completeHandler(event) {
            let message = "";

            if (event.target.status == 422) {
                message = '<div class="alert alert-danger"><ul>';
                $.each(JSON.parse(event.target.responseText), function (index, value) {
                    $.each(value, function (index, value) {
                        message += '<li>' + value + '</li>';
                    });
                });
                message += '</ul></div>';
            } else if(event.target.status == 200) {
                message = '<div class="alert alert-success">' + event.target.responseText + '</div>';
                window.location.href = "{{ route('history') }}";
            }

            loadedTotal.hide();
            progressBar.hide();
            progressBar.val(0);

            status.html(message);
        }

        function errorHandler(event) {
            status.html("Upload Failed");
        }

        function abortHandler(event) {
            status.html("Upload Aborted");
        }
    </script>
@endsection

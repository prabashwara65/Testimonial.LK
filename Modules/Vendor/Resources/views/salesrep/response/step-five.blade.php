@extends('layouts.vendor')

@section('content')
    <div class="create-testimonials container-fluid second-page" id="createTestimonials">
        <div class="container">
            <div class="row">
                <section class="col-xs-12">
                    <div class="wizard">
                        @include('vendor::salesrep.response.nav-wizard')

                        <div class="form-holder">
                            <div class="tab-content">
                                <div class="tab-pane wizard-tab-pane active" role="tabpanel" id="step2">
                                    <div class="col-xs-12">
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs wizard-custom-tabs" role="tablist">
                                            @if ($responseType != 'Questionnaire')
                                                <li role="presentation">
                                                    <a href="#record" class="d-flex align-center wizard-custom-tab-1"
                                                        aria-controls="record" role="tab" data-toggle="tab">
                                                        <div class="custom-tab-radio custom-tab-radio-1 mr-10"></div>
                                                        Record
                                                    </a>
                                                </li>
                                            @endif
                                            @if ($responseType != 'Record')
                                                <li role="presentation">
                                                    <a href="#questionnaire" class="d-flex align-center wizard-custom-tab-2"
                                                        aria-controls="questionnaire" role="tab" data-toggle="tab">
                                                        <div class="custom-tab-radio custom-tab-radio-2 mr-10"></div>
                                                        Questionnaire
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>

                                        <!-- Tab panes -->
                                        <div class="tab-content mt-20">

                                            @if ($responseType != 'Questionnaire')
                                                <div role="tabpanel" class="tab-pane" id="record">
                                                    <form id="recordForm">
                                                        @csrf
                                                        <div class="form-row row">
                                                            <div class="col-xs-12 col-sm-6 col-md-3">
                                                                <div class="radio">
                                                                    <label>
                                                                        <input type="radio" name="type" id="type"
                                                                            value="1" checked>
                                                                        Testimonial
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-3">
                                                                <div class="radio">
                                                                    <label>
                                                                        <input type="radio" name="type" id="type"
                                                                            value="2">
                                                                        Feedback
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if ($target->video_type != 0)
                                                            <div class="row">
                                                                <div class="col-xs-12 col-md-10 mb-20">
                                                                    <div class="row">
                                                                        <div class="col-md-1">
                                                                            <p class="f14 fw-400 text-color">Video</p>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="file" name="video"
                                                                                id="video" class="custom-file-input">
                                                                            <p class="help-block f10 mt-2 mb-0 pl-5">
                                                                                Max 15MB</p>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            @if ($target->video_type != 1)
                                                                                <a href="#" class="recordButton"
                                                                                    data-url="{{ route('response.video-record') }}"><i
                                                                                        class="fas fa-video f16 text-color"></i></a>
                                                                            @endif
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
                                                        @endif

                                                        @if ($target->audio_type != 0)
                                                            <div class="row">
                                                                <div class="col-xs-12 col-md-10 mb-20">
                                                                    <div class="row">
                                                                        <div class="col-md-1">
                                                                            <p class="f14 fw-400 text-color">Audio</p>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="file" name="audio"
                                                                                id="audio" class="custom-file-input">
                                                                            <p class="help-block f10 mt-2 mb-0 pl-5">
                                                                                Max 15MB</p>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            @if ($target->audio_type != 1)
                                                                                <a href="#" class="recordButton"
                                                                                    data-url="{{ route('response.audio-record') }}"><i
                                                                                        class="fas fa-microphone f16 text-color"></i></a>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if ($target->image_type != 0)
                                                            <div class="row">
                                                                <div class="col-xs-12 col-md-10 mb-20">
                                                                    <div class="row">
                                                                        <div class="col-md-1">
                                                                            <p class="f14 fw-400 text-color">Image</p>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            <input type="file" name="image"
                                                                                id="image" class="custom-file-input">
                                                                            <p class="help-block f10 mt-2 mb-0 pl-5">
                                                                                Max 15MB</p>
                                                                        </div>
                                                                        <div class="col-md-1">
                                                                            @if ($target->image_type != 1)
                                                                                <a href="#" class="recordButton"
                                                                                    data-url="{{ route('response.image-record') }}"><i
                                                                                        class="fas fa-camera f16 text-color"></i></a>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @if ($target->text_type != 0)
                                                            <div class="row">
                                                                <div class="col-xs-12 col-md-10 mb-20">
                                                                    <div class="row">
                                                                        <div class="col-md-1">
                                                                            <p class="f14 fw-400 text-color">Text</p>
                                                                        </div>
                                                                        <div class="col-md-3">
                                                                            @if ($target->text_type != 2)
                                                                                <input type="file" name="text"
                                                                                    id="text"
                                                                                    class="custom-file-input">
                                                                                <p class="help-block f10 mt-2 mb-0 pl-5">
                                                                                    Max 15MB</p>
                                                                            @endif
                                                                        </div>
                                                                        <div class="col-md-8">
                                                                            @if ($target->text_type != 1)
                                                                                <textarea class="form-control" name="textarea" placeholder="Text here..."></textarea>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <input type="hidden" name="submitbutton" value="record">
                                                        <div class="col-xs-12">
                                                            <progress id="progressBar" value="0" max="100"
                                                                style="width:300px; display: none"></progress>
                                                            <div id="status" style="display: none"></div>
                                                            <p id="loadedTotal" style="display: none"></p>

                                                            <ul class="list-inline pull-right mt-20">
                                                                <li><a href="{{ route('response.step-three') }}">
                                                                        <button type="button"
                                                                            class="hbtn hbtn-prev prev-step mb-10 mb-sm-0">
                                                                            Previous
                                                                        </button>
                                                                    </a></li>
                                                                <li>
                                                                    <input type="button" class="hbtn hbtn-blue"
                                                                        value="Submit" onclick="uploadFile()">
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif

                                            @if ($responseType != 'Record')
                                                <div role="tabpanel" class="tab-pane" id="questionnaire">
                                                    <form action="{{ route('response.step-five.post') }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="form-row row">
                                                            <div class="col-xs-12 col-sm-6 col-md-3">
                                                                <div class="radio">
                                                                    <label>
                                                                        <input type="radio" name="type"
                                                                            id="type" value="1" checked>
                                                                        Testimonial
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <div class="col-xs-12 col-sm-6 col-md-3">
                                                                <div class="radio">
                                                                    <label>
                                                                        <input type="radio" name="type"
                                                                            id="type" value="2">
                                                                        Feedback
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @foreach ($questionnaire->questions as $question)
                                                            <div class="col-xs-12 mb-20 questionnaire-questions">
                                                                <p class="m-0 f16 fw-600 text-color">
                                                                    {{ $question->question }} {!! $question->required_needed == 1 ? '<sup class="red-color">*</sup>' : '' !!}</p>

                                                                <div class="answers">
                                                                    @if ($question->type_id == 1)
                                                                        <input type="text"
                                                                            name="answer[{{ $question->id }}]"
                                                                            id="{{ $question->id }}"
                                                                            {{ $question->required_needed == 1 ? 'required' : '' }}>
                                                                    @elseif($question->type_id == 2)
                                                                        @foreach ($question->answers as $answer)
                                                                            <div class="radio">
                                                                                <label>
                                                                                    <input type="radio"
                                                                                        name="answer[{{ $question->id }}]"
                                                                                        value="{{ $answer->value }}"
                                                                                        {{ $question->required_needed == 1 ? 'required' : '' }}>
                                                                                    {{ $answer->value }}
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    @elseif($question->type_id == 3)
                                                                        @foreach ($question->answers as $key => $answer)
                                                                            <div class="checkbox">
                                                                                <label>
                                                                                    <input type="checkbox"
                                                                                        name="answer[{{ $question->id }}][{{ $key }}]"
                                                                                        value="{{ $answer->value }}">
                                                                                    {{ $answer->value }}
                                                                                </label>
                                                                            </div>
                                                                        @endforeach
                                                                    @elseif($question->type_id == 4)
                                                                        <div class="rate">
                                                                            @foreach ($question->answers as $answer)
                                                                                @for ($i = $answer->value; $i > 0; $i--)
                                                                                    <input type="radio"
                                                                                        id="star{{ $i }}"
                                                                                        name="answer[{{ $question->id }}]"
                                                                                        value="{{ $i }}"
                                                                                        {{ $question->required_needed == 1 ? 'required' : '' }} />
                                                                                    <label class="star"
                                                                                        for="star{{ $i }}"
                                                                                        title="{{ config('settings.star-' . $i) }}"
                                                                                        data-toggle="tooltip"
                                                                                        data-placement="top">{{ $i }}
                                                                                        star</label>
                                                                                @endfor
                                                                            @endforeach
                                                                        </div>
                                                                    @elseif($question->type_id == 5)
                                                                        <input type="number"
                                                                            name="answer[{{ $question->id }}]"
                                                                            id="{{ $question->id }}"
                                                                            {{ $question->required_needed == 1 ? 'required' : '' }}>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                        <div class="col-xs-12">
                                                            <ul class="list-inline pull-right mt-20">
                                                                <li><a href="{{ route('response.step-three') }}">
                                                                        <button type="button"
                                                                            class="hbtn hbtn-prev prev-step mb-10 mb-sm-0">
                                                                            Previous
                                                                        </button>
                                                                    </a></li>
                                                                <li>
                                                                    <button type="submit" name="submitbutton"
                                                                        value="questionnaire" class="hbtn hbtn-blue"
                                                                        data-toggle="modal"
                                                                        data-target="#confirmUploadFileModal">Submit
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.xl-modal-holder').on('hidden.bs.modal', function() {
            $('.modal-content').html('');
        });
    </script>

    <script>
        $("textarea").each(function() {
            this.setAttribute("style", "height:" + (this.scrollHeight) + "px;min-height: 100px;overflow-y:hidden;");
        }).on("input", function() {
            this.style.height = "auto";
            this.style.height = (this.scrollHeight) + "px";
        });
    </script>

    <script>
        let loadedTotal = $("#loadedTotal");
        let progressBar = $("#progressBar");
        let status = $("#status");

        function uploadFile() {
            var formdata = new FormData($("#recordForm")[0]);

            var ajax = new XMLHttpRequest();
            ajax.upload.addEventListener("progress", progressHandler, false);
            ajax.addEventListener("load", completeHandler, false);
            ajax.addEventListener("error", errorHandler, false);
            ajax.addEventListener("abort", abortHandler, false);
            ajax.open("POST", "{{ route('response.step-five.post') }}");
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
                $.each(JSON.parse(event.target.responseText), function(index, value) {
                    $.each(value, function(index, value) {
                        message += '<li>' + value + '</li>';
                    });
                });
                message += '</ul></div>';
            } else if (event.target.status == 200) {
                message = '<div class="alert alert-success">' + event.target.responseText + '</div>';
                window.location.href = "{{ route('collection') }}";
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

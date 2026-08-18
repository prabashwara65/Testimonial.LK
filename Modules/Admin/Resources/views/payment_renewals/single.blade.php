@if(isset($records))
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">RECORD TESTIMONIAL</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="record card">
                <div class="card-body call-center-bg-gray">
                    <div class="row">
                        @forelse ($records as $record)
                            @if ($record->video)
                                <div class="col-md-6">
                                    <span class="details-label">Video</span>
                                </div>
                                <div class="col-md-3">
                                    <a href="#" onclick="videoToggle()"><i class="c-deep-purple-500 ti-control-play"></i></a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{asset('storage/records/videos/'.$record->video)}}" download><i class="c-deep-purple-500 ti-download"></i></a>
                                </div>
                            @endif
                            @if ($record->audio)
                                <div class="col-md-6">
                                    <span class="details-label">Audio</span>
                                </div>
                                <div class="col-md-3">
                                    <a href="#" onclick="audioToggle()"><i class="c-deep-purple-500 ti-control-play"></i></a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{asset('storage/records/audios/'.$record->audio)}}" download><i class="c-deep-purple-500 ti-download"></i></a>
                                </div>
                            @endif
                            @if ($record->image)
                                <div class="col-md-6">
                                    <span class="details-label">Image</span>
                                </div>
                                <div class="col-md-3">
                                    <a href="#" onclick="imageToggle()"><i class="c-deep-purple-500 ti-eye"></i></a>
                                </div>
                                <div class="col-md-3">
                                    <a href="{{asset('storage/records/images/'.$record->image)}}" download><i class="c-deep-purple-500 ti-download"></i></a>
                                </div>
                            @endif
                            @if ($record->text)
                                <div class="col-md-6">
                                    <span class="details-label">Text</span>
                                </div>
                                <div class="col-md-3">
                                    <a href="#" onclick="textToggle()"><i class="c-deep-purple-500 ti-eye"></i></a>
                                </div>
                                <div class="col-md-3">
                                    <a href="#"><i class="c-deep-purple-500 ti-download"></i></a>
                                </div>
                            @endif
                        @empty

                        @endforelse
                    </div>
                </div>
            </div>
            <div id="videoPanel" class="modal-content" style="display: none">
                <div class="modal-header">
                    <h4 class="modal-title c-grey-900 ">Video Panel</h4>
                    <button type="button" class="close" onclick="videoToggle()">&times;</button>
                </div>
                <div class="overflow-hidden w-100" style="height: 320px">
                    <video height="100%" width="100%" controls playsinline>
                        <source src="{{asset('storage/'.$record->video)}}" type="video/mp4">
                        <source src="{{asset('storage/'.$record->video)}}" type="video/webm">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </div>
            <div id="audioPanel" class="modal-content" style="display: none">
                <div class="modal-header">
                    <h4 class="modal-title c-grey-900 ">Audio Panel</h4>
                    <button type="button" class="close" onclick="audioToggle()">&times;</button>
                </div>
                <audio style="width: 100%" controls>
                    <source src="{{asset('storage/records/audios/'.$record->audio)}}" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
            </div>
            <div id="imagePanel" class="modal-content" style="display: none">
                <div class="modal-header">
                    <h4 class="modal-title c-grey-900 ">Image Panel</h4>
                    <button type="button" class="close" onclick="imageToggle()">&times;</button>
                </div>
                <img style="width: 100%" src="{{asset('storage/records/images/'.$record->image)}}">
            </div>
            <div id="textPanel" class="modal-content" style="display: none">
                <div class="modal-header">
                    <h4 class="modal-title c-grey-900 ">Text Panel</h4>
                    <button type="button" class="close" onclick="textToggle()">&times;</button>
                </div>
                <div class="card-body call-center-bg-gray">
                    <p>{{ $record->text }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">

    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>


@elseif(isset($questionnaires))


<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">QUESTIONNAIRE TESTIMONIAL</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="row">
        <div class='col-md-10 offset-md-1'>
            @forelse ($questionnaires as $response)
                <div class="form-group row">
                    <label for="name" class="col-sm-12 col-form-label">Q : {{ $response->question->question }}</label>
                    <p class="col-sm-12 p-6" style="background-color: #eee">
                        A : {{ $response->answer }}
                    </p>
                </div>
            @empty

            @endforelse
        </div>
    </div>
</div>
<div class="modal-footer">

    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
@endif

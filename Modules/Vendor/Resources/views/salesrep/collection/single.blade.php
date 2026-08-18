@if(isset($record))
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
                        @if ($record->video)
                            <div class="col-md-6">
                                <span class="details-label">Video</span>
                            </div>
                            <div class="col-md-3">
                                <a href="#" onclick="videoToggle()"><i class="f26 text-color fas fa-play-circle"></i></a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{asset('storage/'.$record->video)}}" download><i class="f26 text-color fas fa-download"></i></a>
                            </div>
                        @endif
                        @if ($record->audio)
                            <div class="col-md-6">
                                <span class="details-label">Audio</span>
                            </div>
                            <div class="col-md-3">
                                <a href="#" onclick="audioToggle()"><i class="f26 text-color fas fa-play-circle"></i></a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{asset('storage/'.$record->audio)}}" download><i class="f26 text-color fas fa-download"></i></a>
                            </div>
                        @endif
                        @if ($record->image)
                            <div class="col-md-6">
                                <span class="details-label">Image</span>
                            </div>
                            <div class="col-md-3">
                                <a href="#" onclick="imageToggle()"><i class="f26 text-color fas fa-eye"></i></a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{asset('storage/'.$record->image)}}" download><i class="f26 text-color fas fa-download"></i></a>
                            </div>
                        @endif
                        @if ($record->text)
                            <div class="col-md-6">
                                <span class="details-label">Text</span>
                            </div>
                            <div class="col-md-3">
                                <a href="#" onclick="textToggle()"><i class="f26 text-color fas fa-eye"></i></a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{asset('storage/'.$record->text)}}" download><i class="f26 text-color fas fa-download"></i></a>
                            </div>
                        @endif
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
                    <source src="{{asset('storage/'.$record->audio)}}" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
            </div>
            <div id="imagePanel" class="modal-content" style="display: none">
                <div class="modal-header">
                    <h4 class="modal-title c-grey-900 ">Image Panel</h4>
                    <button type="button" class="close" onclick="imageToggle()">&times;</button>
                </div>
                <img style="width: 100%" src="{{asset('storage/'.$record->image)}}">
            </div>
            <div id="textPanel" class="modal-content" style="display: none">
                <div class="modal-header">
                    <h4 class="modal-title c-grey-900 ">Text Panel</h4>
                    <button type="button" class="close" onclick="textToggle()">&times;</button>
                </div>
                <div class="card-body call-center-bg-gray">
                    @php
                        $extension = pathinfo(asset('storage/'.$record->text), PATHINFO_EXTENSION);
                        if($extension == 'txt') {
                            $textContent = '<p>'.Storage::disk('local')->get('public/'.$record->text).'</p>';
                        } else {
                            $textContent = '<div class="alert alert-warning">This file type couldn\'t be open in browser. Please download.</div>';
                        }
                    @endphp
                    <div class="p-6">
                        {!! $textContent !!}
                    </div>
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
            @forelse ($questionnaires as $question)
                <div class="form-group row">
                    <label for="name" class="col-sm-12 col-form-label">Q : {{ $question->question->question }}</label>
                    <p class="col-sm-12 p-6" style="background-color: #eee">
                        @if($question->question->type_id != 4)
                            A : {{ $question->answer }}
                        @else
                            A : @for($i = 0; $i < $question->answer; $i++) ★ @endfor
                        @endif
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

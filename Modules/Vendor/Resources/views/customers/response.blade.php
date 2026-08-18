@foreach ($responses as $response)
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Details of @if($response->type == 1) Testimonial @else Feedback @endif : ID #{{ $response->id }}</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="card-body call-center-bg-gray mb-4">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Date :</strong></label>
                            <label class="col-sm-8 col-form-label">{{ $response->created_at }}</label>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Status :</strong></label>
                            <label class="col-sm-8 col-form-label">{{ ucfirst($response->status) }}</label>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Product :</strong></label>
                            <label class="col-sm-8 col-form-label">{{ $response->product->product_name }}</label>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Subproduct :</strong></label>
                            <label class="col-sm-8 col-form-label">{{ $response->subproduct->subproduct_name }}</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Input Source :</strong></label>
                            <label class="col-sm-8 col-form-label">{{ $response->input_source }}</label>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Sales rep :</strong></label>
                            <label class="col-sm-8 col-form-label">{{ ($response->emp_id) ? $response->employee->name . ' ' . $response->employee->last_name : '' }}</label>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Location :</strong></label>
                            <label class="col-sm-8 col-form-label">@if($response->geo_address) <a target="_blank" href="http://maps.google.com/maps?z=18&q={{$response->latitude}},{{$response->longitude}}"> {{ $response->geo_address }} </a> @endif</label>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Rating :</strong></label>
                            <label class="col-sm-8 col-form-label">
                                @for($i = 0; $i < $response->rating; $i++) ★ @endfor
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            @if($response->response_type == 'Record')
            <div class="record card">
                <div class="card-body call-center-bg-gray">
                    <div class="row">
                        @if ($response->responseRecord->video)
                            <div class="col-md-6">
                                <span class="details-label">Video</span>
                            </div>
                            <div class="col-md-3">
                                <a href="#" onclick="videoToggle()"><i class="c-deep-purple-500 ti-control-play"></i></a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{asset('storage/'.$response->responseRecord->video)}}" download><i class="c-deep-purple-500 ti-download"></i></a>
                            </div>
                        @endif
                        @if ($response->responseRecord->audio)
                            <div class="col-md-6">
                                <span class="details-label">Audio</span>
                            </div>
                            <div class="col-md-3">
                                <a href="#" onclick="audioToggle()"><i class="c-deep-purple-500 ti-control-play"></i></a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{asset('storage/'.$response->responseRecord->audio)}}" download><i class="c-deep-purple-500 ti-download"></i></a>
                            </div>
                        @endif
                        @if ($response->responseRecord->image)
                            <div class="col-md-6">
                                <span class="details-label">Image</span>
                            </div>
                            <div class="col-md-3">
                                <a href="#" onclick="imageToggle()"><i class="c-deep-purple-500 ti-eye"></i></a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{asset('storage/'.$response->responseRecord->image)}}" download><i class="c-deep-purple-500 ti-download"></i></a>
                            </div>
                        @endif
                        @if ($response->responseRecord->text)
                            <div class="col-md-6">
                                <span class="details-label">Text</span>
                            </div>
                            <div class="col-md-3">
                                <a href="#" onclick="textToggle()"><i class="c-deep-purple-500 ti-eye"></i></a>
                            </div>
                            <div class="col-md-3">
                                <a href="{{asset('storage/'.$response->responseRecord->text)}}" download><i class="c-deep-purple-500 ti-download"></i></a>
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
                        <source src="{{asset('storage/'.$response->responseRecord->video)}}" type="video/mp4">
                        <source src="{{asset('storage/'.$response->responseRecord->video)}}" type="video/webm">
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
                    <source src="{{asset('storage/'.$response->responseRecord->audio)}}" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
            </div>
            <div id="imagePanel" class="modal-content" style="display: none">
                <div class="modal-header">
                    <h4 class="modal-title c-grey-900 ">Image Panel</h4>
                    <button type="button" class="close" onclick="imageToggle()">&times;</button>
                </div>
                <img style="width: 100%" src="{{asset('storage/'.$response->responseRecord->image)}}">
            </div>
            <div id="textPanel" class="modal-content" style="display: none">
                <div class="modal-header">
                    <h4 class="modal-title c-grey-900 ">Text Panel</h4>
                    <button type="button" class="close" onclick="textToggle()">&times;</button>
                </div>
                <div class="card-body call-center-bg-gray">
                    @php
                        $extension = pathinfo(asset('storage/'.$response->responseRecord->text), PATHINFO_EXTENSION);
                        if($extension == 'txt') {
                            $textContent = '<p>'.Storage::disk('local')->get('public/'.$response->responseRecord->text).'</p>';
                        } else {
                            $textContent = '<div class="alert alert-warning">This file type couldn\'t be open in browser. Please download.</div>';
                        }
                    @endphp
                    <div class="p-6">
                        {!! $textContent !!}
                    </div>
                </div>
            </div>

            @elseif($response->response_type == 'Questionnaire')

            @foreach ($response->responseQuestions as $response)
                <div class="form-group row">
                    <label for="name" class="col-sm-12 col-form-label">Q : {{ $response->question->question }}</label>
                    <p class="col-sm-12 p-6" style="background-color: #eee">
                        @if($response->question->type_id != 4)
                            A : {{ $response->answer }}
                        @else
                            A : @for($i = 0; $i < $response->answer; $i++) ★ @endfor
                        @endif
                    </p>
                </div>
            @endforeach
            @endif
        </div>
    </div>
</div>
@endforeach

<div class="modal-footer">
    {{ $responses->links() }}

    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
</div>

{{ Form::open(['url' => route('vendor.targets.update', $target['id']), 'class' => 'text-form', 'method' => 'put', 'onsubmit' => 'return false;']) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Edit Target</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="target_name" class="col-md-4 col-form-label">Target Name <i
                        class="required-star"></i></label>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="target_name" name="target_name"
                        value="{{ $target['target_name'] }}">
                    <span class="invalid-feedback target_name-error" role="alert"></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="target_type" class="col-md-4 col-form-label">Target Type <i
                        class="required-star"></i></label>
                <div class="col-md-8">
                    <select name="target_type" id="target_type" class="form-control">
                        <option value="1" @if ($target['target_type'] == 1) selected @endif>Common Target</option>
                        <option value="2" @if ($target['target_type'] == 2) selected @endif>Special Target</option>
                    </select>
                    <span class="invalid-feedback target_type-error" role="alert"></span>
                </div>
            </div>
        </div>
    </div>

    <div id="common" @if ($target['target_type'] == 2) style="display: none;" @endif>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group row">
                    <label for="target" class="col-md-4 col-form-label">Target <i class="required-star"></i></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="target" name="target"
                            value="{{ $target['target'] }}">
                        <span class="invalid-feedback target-error" role="alert"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="special" @if ($target['target_type'] == 1) style="display: none;" @endif>
        <div class="row">
            <div class="row col-sm-8 ml-5">
                <div class="col-sm-4">
                    <input type="radio" id="video_type" name="video_type" value="0"
                        @if ($target['video_type'] == 0) checked @endif />
                    <label for="video_type">Video Not Allowed</label>
                </div>
                <div class="col-sm-3">
                    <input type="radio" id="video_type" name="video_type" value="1"
                        @if ($target['video_type'] == 1) checked @endif />
                    <label for="video_type">Upload</label>
                </div>
                <div class="col-sm-2">
                    <input type="radio" id="video_type" name="video_type" value="2"
                        @if ($target['video_type'] == 2) checked @endif />
                    <label for="video_type">Live</label>
                </div>
                <div class="col-sm-2">
                    <input type="radio" id="video_type" name="video_type" value="3"
                        @if ($target['video_type'] == 3) checked @endif />
                    <label for="video_type">Both</label>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="text" class="form-control" id="video" name="video"
                        value="{{ $target['video'] }}" @if ($target['video_type'] == 0) readonly @endif>
                    <span class="invalid-feedback video-error" role="alert"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="row col-sm-8 ml-5">
                <div class="col-sm-4">
                    <input type="radio" id="audio_type" name="audio_type" value="0"
                        @if ($target['audio_type'] == 0) checked @endif />
                    <label for="audio_type">Audio Not Allowed</label>
                </div>
                <div class="col-sm-3">
                    <input type="radio" id="audio_type" name="audio_type" value="1"
                        @if ($target['audio_type'] == 1) checked @endif />
                    <label for="audio_type">Upload</label>
                </div>
                <div class="col-sm-2">
                    <input type="radio" id="audio_type" name="audio_type" value="2"
                        @if ($target['audio_type'] == 2) checked @endif />
                    <label for="audio_type">Live</label>
                </div>
                <div class="col-sm-2">
                    <input type="radio" id="audio_type" name="audio_type" value="3"
                        @if ($target['audio_type'] == 3) checked @endif />
                    <label for="audio_type">Both</label>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="text" class="form-control" id="audio" name="audio"
                        value="{{ $target['audio'] }}" @if ($target['audio_type'] == 0) readonly @endif>
                    <span class="invalid-feedback audio-error" role="alert"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="row col-sm-8 ml-5">
                <div class="col-sm-4">
                    <input type="radio" id="image_type" name="image_type" value="0"
                        @if ($target['image_type'] == 0) checked @endif />
                    <label for="image_type">Image Not Allowed</label>
                </div>
                <div class="col-sm-3">
                    <input type="radio" id="image_type" name="image_type" value="1"
                        @if ($target['image_type'] == 1) checked @endif />
                    <label for="image_type">Upload</label>
                </div>
                <div class="col-sm-2">
                    <input type="radio" id="image_type" name="image_type" value="2"
                        @if ($target['image_type'] == 2) checked @endif />
                    <label for="image_type">Live</label>
                </div>
                <div class="col-sm-2">
                    <input type="radio" id="image_type" name="image_type" value="3"
                        @if ($target['image_type'] == 3) checked @endif />
                    <label for="image_type">Both</label>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="text" class="form-control" id="image" name="image"
                        value="{{ $target['image'] }}" @if ($target['image_type'] == 0) readonly @endif>
                    <span class="invalid-feedback image-error" role="alert"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="row col-sm-8 ml-5">
                <div class="col-sm-4">
                    <input type="radio" id="text_type" name="text_type" value="0"
                        @if ($target['text_type'] == 0) checked @endif />
                    <label for="text_type">Text Not Allowed</label>
                </div>
                <div class="col-sm-3">
                    <input type="radio" id="text_type" name="text_type" value="1"
                        @if ($target['text_type'] == 1) checked @endif />
                    <label for="text_type">Upload</label>
                </div>
                <div class="col-sm-2">
                    <input type="radio" id="text_type" name="text_type" value="2"
                        @if ($target['text_type'] == 2) checked @endif />
                    <label for="text_type">Live</label>
                </div>
                <div class="col-sm-2">
                    <input type="radio" id="text_type" name="text_type" value="3"
                        @if ($target['text_type'] == 3) checked @endif />
                    <label for="text_type">Both</label>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="text" class="form-control" id="text" name="text"
                        value="{{ $target['text'] }}" @if ($target['text_type'] == 0) readonly @endif>
                    <span class="invalid-feedback text-error" role="alert"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
    {{ Form::submit('Save', ['class' => 'text-form-submit btn btn-primary']) }}
</div>
{{ Form::close() }}

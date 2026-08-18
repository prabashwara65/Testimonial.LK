{{ Form::open(array('url' => route('vendor.targets.store'), 'class'=>'text-form', 'onsubmit' => 'return false;')) }}
<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">Add New Target</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="target_name" class="col-md-4 col-form-label">Target Name <i class="required-star"></i></label>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="target_name" name="target_name">
                    <span class="invalid-feedback target_name-error" role="alert"></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="form-group row">
                <label for="target_type" class="col-md-4 col-form-label">Target Type <i class="required-star"></i></label>
                <div class="col-md-8">
                    <select name="target_type" id="target_type" class="form-control">
                        <option value="1">Common Target</option>
                        <option value="2">Special Target</option>
                    </select>
                    <span class="invalid-feedback target_type-error" role="alert"></span>
                </div>
            </div>
        </div>
    </div>

    <div id="common">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group row">
                    <label for="target" class="col-md-4 col-form-label">Target <i class="required-star"></i></label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="target" name="target">
                        <span class="invalid-feedback target-error" role="alert"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="special" style="display: none;">
        <div class="row">
            <div class="row col-sm-8 ml-5">
                <div class="col-sm-4">
                    <input type="radio" id="video_type" name="video_type" value="0" checked />
                    <label for="video_type">Video Not Allowed</label>
                </div>
                <div class="col-sm-3">
                    <input type="radio" id="video_type" name="video_type" value="1"/>
                    <label for="video_type">Upload</label>
                </div>
                <div class="col-sm-2">
                    <input type="radio" id="video_type" name="video_type" value="2"/>
                    <label for="video_type">Live</label>
                </div>
                <div class="col-sm-2">
                    <input type="radio" id="video_type" name="video_type" value="3"/>
                    <label for="video_type">Both</label>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="text" class="form-control" id="video" name="video" readonly>
                    <span class="invalid-feedback video-error" role="alert"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="row col-sm-8 ml-5">
                <div class="col-sm-4">
                    <input type="radio" id="audio_type" name="audio_type" value="0" checked />
                    <label for="audio_type">Audio Not Allowed</label>
                </div>
                <div class="col-sm-3">
                    <input type="radio" id="audio_type" name="audio_type" value="1"/>
                    <label for="audio_type">Upload</label>
                </div>
                <div class="col-sm-2">
                    <input type="radio" id="audio_type" name="audio_type" value="2"/>
                    <label for="audio_type">Live</label>
                </div>
                <div class="col-sm-2">
                    <input type="radio" id="audio_type" name="audio_type" value="3"/>
                    <label for="audio_type">Both</label>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="text" class="form-control" id="audio" name="audio" readonly>
                    <span class="invalid-feedback audio-error" role="alert"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="row col-sm-8 ml-5">
                <div class="col-sm-4">
                    <input type="radio" id="image_type" name="image_type" value="0" checked />
                    <label for="image_type">Image Not Allowed</label>
                </div>
                <div class="col-sm-3">
                    <input type="radio" id="image_type" name="image_type" value="1"/>
                    <label for="image_type">Upload</label>
                </div>
                <div class="col-sm-2">
                    <input type="radio" id="image_type" name="image_type" value="2"/>
                    <label for="image_type">Live</label>
                </div>
                <div class="col-sm-2">
                    <input type="radio" id="image_type" name="image_type" value="3"/>
                    <label for="image_type">Both</label>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="text" class="form-control" id="image" name="image" readonly>
                    <span class="invalid-feedback image-error" role="alert"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="row col-sm-8 ml-5">
                <div class="col-sm-4">
                    <input type="radio" id="text_type" name="text_type" value="0" checked />
                    <label for="text_type">Text Not Allowed</label>
                </div>
                <div class="col-sm-3">
                    <input type="radio" id="text_type" name="text_type" value="1"/>
                    <label for="text_type">Upload</label>
                </div>
                <div class="col-sm-2">
                    <input type="radio" id="text_type" name="text_type" value="2"/>
                    <label for="text_type">Live</label>
                </div>
                <div class="col-sm-2">
                    <input type="radio" id="text_type" name="text_type" value="3"/>
                    <label for="text_type">Both</label>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="text" class="form-control" id="text" name="text" readonly>
                    <span class="invalid-feedback text-error" role="alert"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default cancel-btn-color" data-dismiss="modal">Cancel</button>
    {{ Form::submit('Save', array('class' => 'text-form-submit btn btn-primary')) }}
</div>
{{ Form::close() }}

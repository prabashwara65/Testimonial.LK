<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">IMAGE CAPTURE</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div id="livePanel">
        <video id="live" width="100%" autoplay></video>
        <div id="controls">
            <button id="capture" class="btn view-button btn-lg"><i class="fas fa-camera f16 text-color"></i> Capture</button>
        </div>
    </div>
    <div id="playbackPanel" style="display: none">
        <canvas id="canvas" width="480" height="360"></canvas>
        <div id="controls">
            <button id="recapture" class="btn view-button btn-lg mb-2"><i class="fas fa-backward f16 text-color"></i> Recapture</button>
            <a id="downloadLink" download="YourFileName.jpg">
                <button class="btn view-button btn-lg mb-2"><i class="fas fa-download f16 text-color"></i> Download Image</button>
            </a>
            <button id="upload" class="btn view-button btn-lg mb-2" data-dismiss="modal"><i class="fas fa-upload f16 text-color"></i> Upload This</button>
        </div>
    </div>

    <script src="{{ asset('assets/custom/js/image_recorder.js') }}"></script>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>

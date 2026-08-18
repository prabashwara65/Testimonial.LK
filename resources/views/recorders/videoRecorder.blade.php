<div class="modal-header">
    <h4 class="modal-title c-grey-900 ">VIDEO RECORDER</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <div id="livePanel">
        <video id="live" width="100%" controls autoplay playsinline muted></video>
        <div id="controls">
            <button id="rec" class="btn view-button btn-lg mb-2" onclick="onBtnRecordClicked()"><i class="fas fa-circle f16 text-color"></i> Record</button>
            <button id="pauseRes" class="btn view-button btn-lg mb-2" onclick="onPauseResumeClicked()" disabled><i class="fas fa-pause f16 text-color"></i> Pause</button>
            <button id="stop" class="btn view-button btn-lg mb-2" onclick="onBtnStopClicked()" disabled><i class="fas fa-stop f16 text-color"></i> Stop</button>
        </div>

        <div id="countdown" class="align-items-center">
            <div class="countdownContent">
                <span class="blink blink-dot"></span>
                <p id="hours"></p>
                <span class="divider" id="second-divider">:</span>
                <p id="minutes"></p>
                <span class="divider">:</span>
                <p id="seconds"></p>
            </div>
        </div>
    </div>
    <div id="playbackPanel" style="display: none">
        <video id="playback" width="100%" controls autoplay></video>
        <div id="controls">
            <button id="recapture" class="recapture-btn btn view-button btn-lg mb-2" onclick="onBtnRecaptureClicked()"><i class="fas fa-backward f16 text-color"></i> Recapture</button>
            <a id="downloadLink" download="mediarecorder.webm" name="mediarecorder.webm" href>
                <button class="btn view-button btn-lg mb-2"><i class="fas fa-download f16 text-color"></i> Download Video</button>
            </a>
            <button id="recapture" class="btn view-button btn-lg mb-2" onclick="onBtnUploadClicked()" data-dismiss="modal"><i class="fas fa-upload f16 text-color"></i> Upload This</button>
        </div>
    </div>

    <script src="{{ asset('assets/custom/js/video_recorder.js') }}"></script>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>

{{-- Countdown --}}
<script>
    var counter = 0;
    var isPaused = true;

    $("#resume, #countdown").hide();
    $("#days, #hours, #first-divider, #second-divider").hide();
    $("#rec").show();

    var t = window.setInterval(function() {
        if (!isPaused) {
            counter++;
            var s = counter;
            convertSeconds(Math.floor(s));
        }
    }, 1000);

    // Button Click Events
    $("#rec").click(function () {
        startClock();
        $('#countdown').show();
    });

    function handler1() {
        pauseClock();
        $(this).one("click", handler2);
        $('.blink').removeClass('blink-dot');
    }

    function handler2() {
        resumeClock();
        $(this).one("click", handler1);
        $('.blink').addClass('blink-dot');
    }
    $("#pauseRes").one("click", handler1);


    $("#stop").click(function () {
        $("#countdown").removeClass('d-flex');
        $("#countdown").css("display", "none");
    });

    $(".recapture-btn").click(function () {
       resetClock();
    });

    function startClock() {
        isPaused = false;
    }
    function pauseClock() {
        isPaused = true;
    }
    function resumeClock() { isPaused = false; }
    function resetClock() {
        counter = 0;
        $("#days").html("00");
        $("#hours").html("00");
        $("#minutes").html("00");
        $("#seconds").html("00");
    }
    function stopClock() {
        resetClock();
        isPaused = true;
    }

    function convertSeconds(s) {
        var days = Math.floor(s / 86400)
        var hours = Math.floor((s % 86400) / 3600);
        var minutes = Math.floor(((s % 86400) % 3600) / 60);
        var seconds = ((s % 86400) % 3600) % 60;

        if (days		< 10) {days 	 = "0" + days}
        if (hours 	< 10) {hours 	 = "0" + hours;}
        if (minutes < 10) {minutes = "0" + minutes;}
        if (seconds < 10) {seconds = "0" + seconds;}

        $("#days").html(days);
        $("#hours").html(hours);
        $("#minutes").html(minutes);
        $("#seconds").html(seconds);

        if (days == 0 && hours == 0) {
            $("#days, #hours").hide();
            $("#first-divider, #second-divider").hide();
        } else if (days == 0) {
            $("#days").hide();
            $("#hours").show();
            $("#second-divider").show();
        } else {
            $("p, .divider").show();
        }
    }
</script>
{{-- Countdown --}}

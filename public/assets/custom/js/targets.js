$(document).on('change', '#target_type', function () {
    if ($(this).val() == '1') {
        $("#common").show();
        $("#special").hide();
    } else {
        $("#common").hide();
        $("#special").show();
    }
});

$(document).on('change', 'input[type=radio][name=video_type]', function(){
    if (this.value == '0') {
        $("#video").attr('readonly', true);
        $("#video").val('');
    } else {
        $("#video").attr('readonly', false);
    }
});

$(document).on('change', 'input[type=radio][name=audio_type]', function(){
    if (this.value == '0') {
        $("#audio").attr('readonly', true);
        $("#audio").val('');
    } else {
        $("#audio").attr('readonly', false);
    }
});

$(document).on('change', 'input[type=radio][name=image_type]', function(){
    if (this.value == '0') {
        $("#image").attr('readonly', true);
        $("#image").val('');
    } else {
        $("#image").attr('readonly', false);
    }
});

$(document).on('change', 'input[type=radio][name=text_type]', function(){
    if (this.value == '0') {
        $("#text").attr('readonly', true);
        $("#text").val('');
    } else {
        $("#text").attr('readonly', false);
    }
});
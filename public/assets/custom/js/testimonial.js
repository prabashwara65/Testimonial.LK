function videoToggle() {
    $(".record").slideToggle();
    $("#videoPanel").slideToggle();
}

function audioToggle() {
    $(".record").slideToggle();
    $("#audioPanel").slideToggle();
}

function imageToggle() {
    $(".record").slideToggle();
    $("#imagePanel").slideToggle();
}

function textToggle() {
    $(".record").slideToggle();
    $("#textPanel").slideToggle();
}

//Status Select
$(document).on('change', '#status', function(e) {
    if (this.value == 'reject') {
        $("#reject-panel").slideDown();
        $("#star-panel").slideUp();
    }
    else if (this.value == 'approved') {
        $("#reject-panel").slideUp();
        $("#star-panel").slideDown();
    } else {
        $("#reject-panel").slideUp();
        $("#star-panel").slideUp();
    }
});

//Next Button
$(document).on("click", '.page-link', function(e){
    e.preventDefault();
    $.ajax({
        url:$(this).attr('href'),
        type:'GET',
        data: [],
        success:function(formData){
            $('.xl-modal-holder .modal-content').html(formData.data); 
        },
    });
});

//Reward Radio Button
$(document).on("click", '.reward_type', function(e){
    if($(this).attr('value') == 'discount') {
        $("#discount-panel").show();
        $("#gift-panel").hide();
        $("#gift").val('');
    }
    else {
        $("#gift-panel").show();
        $("#discount-panel").hide();
        $("#discount").val('');
    }
});

$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});
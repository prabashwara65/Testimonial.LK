$(document).on('click', '.add-new-btn', function () {
    $("#loader").removeClass("fadeOut");
   // alert('aa');

    var count = $(".campaign-count-holder").val();
    var url = $(this).data('url') + "/" + (++count);
    var post = ajax(url, [], 'get');

    var addbtn = $(this);
    var campaing = $("#campaign_type").val();

    post.done(function (response) {
        $("#loader").addClass("fadeOut");

        if (response.status == 'success') {
            $(".campaign-count-holder").val(count);
            var $data = $(response.data); // create new DOM elements, and keep a reference to them

            $(".campaign-holder").append($data);

            if(campaing == 'Single') {
                addbtn.hide();
                $("#subproduct_id" + count).removeAttr('multiple');
            }

        } else {
            notifications('error', response.message, response.data, response.redirect, response.notifyType);
        }
    });
})

$(document).on('click', '.remove-question-btn', function () {
    var count = $(this).data('count');
    $("#campaign" + count).remove();
    $(".add-new-btn").show();
});

$(document).on('click', '.add-answer-btn', function () {
    var template = $(this).parent().siblings('div').find('.answer').eq(0).clone();
    var questionId = $(this).data('question');
    template.find('.remove-answer-btn-wrapper').each(function () {
        $(this).removeClass('d-none');
    });
    template.find('input').each(function () {
        $(this).val('');
        $(this).attr('name', 'questions[' + questionId + '][answers][]');
    });
    var answerWrapper = $(this).parent().siblings('.answers-wrapper').append(template);
});

$(document).on('click', '.remove-answer-btn', function () {
    $(this).parent().parent('.answer').remove();
});

$(document).on('change', '#question_type', function () {
    var value = $(this).val();
    var count = $(this).data('count');

    $(".star-count-section-" + count).hide();
    $(".answer-section-" + count).hide();

    switch (value) {
        case '1':
            $(".answer-section-" + count).hide();
            break;
        case '2':
        case '3':
            $(".answer-section-" + count).show();
            break;
        case '4':
            $(".star-count-section-" + count).show();
    }
});


$(function () {
    if ($('#type').val() == 'BA') {
        $(".verify_needed_row").hide();
    }


    $(document).on('click', '.add-question-btn', function(){
        $("#loader").removeClass("fadeOut");

        var count = $(".question-count-holder").val();
        var url = $(this).data('url') + "/" + (++count);
        var post = ajax(url, [], 'get');
        post.done(function (response) {
            $("#loader").addClass("fadeOut");

            if (response.status == 'success') {
                $(".question-count-holder").val(count);
                var $data = $(response.data); // create new DOM elements, and keep a reference to them
                if ($("#type").val() == 'BA') {
                    $data.find(".verify_needed_row").hide();
                }

                $(".question-holder").append($data);

            } else {
                notifications('error', response.message, response.data, response.redirect, response.notifyType);
            }
        });
    })

    $(document).on('click', '.remove-question-btn', function(){
        var count = $(this).data('count');
        $("#question"+count).remove();
    });

    $(document).on('change', '#type', function(){
        if ($(this).val() == 'BA') {
            $(".verify_needed_row").hide();
            $(".survey_row").show();
        } else {
            $(".verify_needed_row").show();
            $(".survey_row").hide();
        }
    });

    $(document).on('click', '.add-answer-btn', function(){
        var template = $(this).parent().siblings('div').find('.answer').eq(0).clone();
        var questionId = $(this).data('question');
        template.find('.remove-answer-btn-wrapper').each(function() {
            $(this).removeClass('d-none');
        });
        /*template.find('input').each(function() {
            $(this).val('');
            $(this).attr('name', 'questions['+questionId+'][answers][]');
        });*/
        template.find('input[type=text]').each(function() {
            $(this).val('');
            $(this).attr('name', 'questions['+questionId+'][answers][]');
        });
        template.find('input[type=number]').each(function() {
            $(this).val('');
            $(this).attr('name', 'questions['+questionId+'][subQuestion][]');
        });

        var answerWrapper = $(this).parent().siblings('.answers-wrapper').append(template);
    });

    $(document).on('click', '.remove-answer-btn', function(){
        $(this).parent().parent('.answer').remove();
    });

    $(document).on('change', '#question_type', function(){
        var value = $(this).val();
        var count = $(this).data('count');

        $(".star-count-section-"+count).hide();
        $(".answer-section-"+count).hide();

        switch (value) {
            case '1':
                $(".answer-section-"+count).hide();
                break;
            case '2':
            case '3':
                $(".answer-section-"+count).show();
                $(".subQuestion-section-"+count).show();
                break;
            case '4':
                $(".star-count-section-"+count).show();
        }
    });

    $(document).on('click', '.survey_question', function(){

        var count = $(this).data('questions_count');

        if ($("#survey_question"+count).is(':checked')) {
            $("#required_needed"+count).prop( "checked", true );
            $("#required_needed"+count).attr('disabled', 'disabled');
        }else{
            $("#required_needed"+count).prop( "checked", false );
            $("#required_needed"+count).removeAttr('disabled');
        }
    });

});
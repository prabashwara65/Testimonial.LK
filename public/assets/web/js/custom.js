
//How It Works Slider
$('.howItWorksSlider').slick({
    arrows: true,
    dots: false,
    infinite: false,
    speed: 300,
    slidesToShow: 2,
    slidesToScroll: 1,
    responsive: [
        {
            breakpoint: 1024,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1,
                infinite: true,
                dots: false
            }
        },
        {
            breakpoint: 600,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
        }
    ]
});

//Re Captcha
function get_action(form)
{
    var v = grecaptcha.getResponse();
    if(v.length == 0)
    {
        document.getElementById('captcha').innerHTML="You can't leave Captcha Code empty";
        return false;
    }
    else
    {
        document.getElementById('captcha').innerHTML="Captcha completed";
        return true;
    }
}

$(function(){
    function rescaleCaptcha(){
        var width = $('.g-recaptcha').parent().width();
        var scale;
        if (width < 302) {
            scale = width / 302;
        } else{
            scale = 1.0;
        }

        $('.g-recaptcha').css('transform', 'scale(' + scale + ')');
        $('.g-recaptcha').css('-webkit-transform', 'scale(' + scale + ')');
        $('.g-recaptcha').css('transform-origin', '0 0');
        $('.g-recaptcha').css('-webkit-transform-origin', '0 0');
    }

    rescaleCaptcha();
    $( window ).resize(function() { rescaleCaptcha(); });

});

$(document).ready(function () {
    //Initialize tooltips
    $('.wizard-tabs > li a[title]').tooltip();

    //Wizard
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

        var $target = $(e.target);

        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step").click(function (e) {

        var $active = $('.wizard .wizard-tabs li.active');
        $active.next().removeClass('disabled');
        nextTab($active);

    });
    $(".prev-step").click(function (e) {

        var $active = $('.wizard .wizard-tabs li.active');
        prevTab($active);

    });
});

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}

//Custom Tab Radios
$('.wizard-custom-tab-1').click(function () {
   $('.custom-tab-radio-1').addClass('custom-tab-radio-active');
   $('.custom-tab-radio-2').removeClass('custom-tab-radio-active');
});

$('.wizard-custom-tab-2').click(function () {
    $('.custom-tab-radio-2').addClass('custom-tab-radio-active');
    $('.custom-tab-radio-1').removeClass('custom-tab-radio-active');
});

//Dashboard Datepicker
$(function () {
    $('#startDatePicker').datetimepicker({
        format:'YYYY-MM-DD',
    });
    $('#endDatePicker').datetimepicker({
        format:'YYYY-MM-DD',
    });
});




//Data Table
$(document).ready(function() {
    $('#testimonialFeedbackCollection').DataTable();
} );
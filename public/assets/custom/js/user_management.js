$(function () {
    $(document).on('change', '#update-password', function(){
        if ($(this).is(':checked')) {
            $(".password-inputs").attr('disabled', false);
        }  else {
            $(".password-inputs").attr('disabled', true);
        }
    })
});
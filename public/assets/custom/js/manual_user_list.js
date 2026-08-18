
//File Download Forms
$('.dynamic-report-form').on('click', '.form-submit', function (e) {
    e.preventDefault();
    $(".invalid-feedback").text("");

    //$('#content-holder').html('<div class="row"><div class="col-md-12 text-center">Please wait! Generating Report...</div></div>');

    var url = $(this).closest('form').attr('action');
    var data = $(this).closest('form').serialize();

    $("#loader").removeClass("fadeOut");
    var post = ajax(url, data, 'GET');
    post.done(function (response) {

        let csvContent = "data:text/csv;charset=utf-8,";
        response.data.forEach(function (rowArray) {
            let row = rowArray.join(",");
            csvContent += row + "\r\n";
        });
        var encodedUri = encodeURI(csvContent);
        var link = document.createElement("a");
        link.setAttribute("href", encodedUri);
        link.setAttribute("download", moment().format('YYYY-MM-DD_') + "Active_user_list.csv");
        document.body.appendChild(link); // Required for FF

        $("#loader").addClass("fadeOut");
        link.click();

        notifications('success', response.message, response.data, response.redirect, response.notifyType);

    });
});





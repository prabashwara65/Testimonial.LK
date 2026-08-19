// Converts object with "name" and "value" keys
// into object with "name" key having "value" as value
// See http://stackoverflow.com/a/12399106/3549014 for more details
$.fn.serializeObject = function(){
    var obj = {};

    $.each( this.serializeArray(), function(i,o){
        var n = o.name, v = o.value;

        obj[n] = obj[n] === undefined ? v
            : $.isArray( obj[n] ) ? obj[n].concat( v )
                : [ obj[n], v ];
    });

    return obj;
};

$(function () {
    $(document).on('keydown', '.loginForm input', function(event) {
        if (event.which === 13) {
            event.preventDefault();
            $(this).closest('form').trigger('submit');
        }
    });

    // this will show popup notifications for actions results on page load
    if( $('#flash-message-container').length )
    {
        var notyHolder = $('#flash-message-container');
        new Noty({
            theme: 'metroui',
            type: notyHolder.data('type'),
            text: "<b style='text-transform: capitalize'>"+notyHolder.data('type')+"</b><br>"+notyHolder.text(),
        }).show();
    }

    $(document).on("keypress", "form:not(.login)", function(event) {
        if (event.which === 13 && !$(event.target).is('textarea')) {
            event.preventDefault();
        }
    });

    $('.datepicker').datepicker();
    $('.month-datepicker').datepicker({
        maxViewMode : 2,
        minViewMode : 1
    });

    init_select2();

    // call center genuine call checkbox clicked toggle visibility of caller questionnaire holder
    $(document).on("click", ".genuine-call-checkbox", function(event) {
        if ($('#genuine_call').is(':checked')) {
            $('.caller-questionnaire-holder').show();
        } else {
            $('.caller-questionnaire-holder').hide();
        }
    });

    window.customTable = $('#custom-dataTable').DataTable( {
        "processing": true,
        "order": [],
        "scrollX": true,
        "serverSide": true,
        dom:"<'row'<'col-sm-4'l><'col-sm-4'><'col-sm-4'fB>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-5'i><'col-sm-7'p>>",
        "aoColumnDefs": [
            { bSortable: false, aTargets: ['no-sort'] } // Disable sorting on columns marked as so
        ],
        // "ajax": $('.get-data-holder').data('url'),
        "ajax": {
            dataType: "JSON",
            type: "GET",
            url: $('.get-data-holder').data('url'),
            data: function(d){
                d.form = $('.filter-form').serializeObject();
            },
            async: true,
            "dataSrc": function ( json ) {
                //Make your callback here.
                $(".invalid-feedback").text("");
                if (json.status != undefined && json.status == "error") {
                    if (json.message.includes("permission")) {
                        json.redirect = json.data;
                    }
                    notifications('error', json.message, json.data, json.redirect, json.notifyType);
                    return [];
                } else if (json.status == "warning") {
                    notifications('warning', json.message, json.data, json.redirect, json.notifyType);
                    return [];
                } else {
                    return json.data;
                }
            }
        },
        "buttons": getDataTableButtons()
    });

    $(document).on("click", ".filter-form .filter-form-submit", function(e) {
        e.preventDefault();
        if (typeof customFilterHandler == 'function') {
            customFilterHandler();
        }
        window.customTable.draw();
    } );

    // Duty Toggle Form
    $('.duty-toggle-form .duty-toggle-button').on('change', function (e) {
        e.preventDefault();

        var url = $('.duty-toggle-form').attr('action')
        var data = $('.duty-toggle-form').serialize();

        var n = new Noty({
            text: 'Toggle Online/Offline status?',
            theme: 'metroui',
            modal: true,
            layout: "topCenter",
            buttons: [
                Noty.button('Continue', 'btn btn-success', function () {
                    n.close();
                    submit_duty_toggle_request(url, data);
                }, {id: 'button1', 'data-status': 'ok'}),

                Noty.button('Cancel', 'btn btn-error', function () {
                    var elem = $('.duty-toggle-button');
                    elem.prop("checked", !elem.prop("checked"));
                    n.close();
                })
            ]
        });
        n.show();
    });


    //Modal Forms Submit
    $('.modal, .filter-form-holder').on('click', '.text-form .text-form-submit', function (e) {
        e.preventDefault();
        $("#loader").removeClass("fadeOut");
        $(".invalid-feedback").text("");
        var url = $(this).closest('form').attr('action');
        var data = $(this).closest('form').serialize();


        var hasFileUpload = undefined;
        if($(this).closest('form').attr('enctype') == 'multipart/form-data') {
            hasFileUpload = true;
            var form = $(this).closest('form')[0];
            data = new FormData(form);
        }

        var post = ajax(url, data, 'POST', hasFileUpload);
        post.done(function (response) {
            $("#loader").addClass("fadeOut");
            if (response.status == 'success') {
                $('.modal').modal('hide');
                if($('#calendar').length > 0){
                    $('#calendar').fullCalendar('destroy');
                }
                if (typeof customModalFormSubmitCallback == 'function') {
                    customModalFormSubmitCallback(response);
                }
                if (window.dataTable != undefined) {
                    window.dataTable.draw(false);
                }
                if (window.customTable != undefined) {
                    window.customTable.draw(false);
                }
                if (window.customTableSecondary != undefined) {
                    window.customTableSecondary.draw(false);
                }

                if (response.data.call_count != undefined) {
                    $('#caller_count_holder').text(response.data.call_count);
                }
                notifications('success', response.message, response.data, response.redirect, response.notifyType);
            } else if(response.status == 'warning') {
                notifications('warning', response.message, response.data, response.redirect, response.notifyType);
            } else {
                notifications('error', response.message, response.data, response.redirect, response.notifyType);
            }
        });
    });

    //Add New Form
    $('.add-new-form .add-new-button').on('click', function (e) {
        e.preventDefault();
        var url = $('.add-new-form').attr('action');
        var data = $('.add-new-form').serialize();
        var holder = 'modal-holder';
        var post = ajax(url, data);
        post.done(function (response) {
            if (response.status == 'success') {
                console.log('response.modalSize');
                if (response.modalSize != undefined && response.modalSize == 'xl') {
                    holder = 'xl-modal-holder';
                }
                notifications('success', response.message, response.data, response.redirect, response.notifyType, holder);
            } else {
                notifications('error', response.message, response.data, response.redirect, response.notifyType);
            }
        });
    });

    //Edit Form
    $('#table-holder').on('click', '.edit-form .edit-button', function (e) {
        e.preventDefault();
        //var url = $('.edit-form').attr('action');
        var url = $(this).parents('.edit-form').attr('action');

        //keep other post values in a data-post attribute as a json array and combine to passing data object
        var json_post_values = $(this).data('post'); //get from json array
        if (json_post_values != null && json_post_values != undefined) {
            var data = $.extend({}, data, json_post_values);
        }

        var holder = 'modal-holder';
        var post = ajax(url, data, 'get');
        post.done(function (response) {
            if (response.status == 'success') {
                if (response.modalSize != undefined && response.modalSize == 'xl') {
                    holder = 'xl-modal-holder';
                }
                notifications('success', response.message, response.data, response.redirect, response.notifyType, holder);
            } else if(response.status == 'warning') {
                notifications('warning', response.message, response.data, response.redirect, response.notifyType);
            } else {
                notifications('error', response.message, response.data, response.redirect, response.notifyType);
            }
        });
    });

    //Delete Form
    $('#table-holder').on('click', '.delete-form .delete-button', function (e) {
        e.preventDefault();

        var url = $(this).closest('form').attr('action');
        var data = $(this).closest('form').serialize();

        var n = new Noty({
            text: 'Are you sure you want to continue?',
            theme: 'metroui',
            modal: true,
            layout: "topCenter",
            buttons: [
                Noty.button('Delete', 'btn btn-danger', function () {
                    n.close();
                    submit_delete_request(url, data);
                }, {id: 'button1', 'data-status': 'ok'}),

                Noty.button('Cancel', 'btn btn-error', function () {
                    n.close();
                })
            ]
        });
        n.show();
    });

    //File Download Forms
    $('.file-download-form').on('click', '.form-submit', function (e) {
        e.preventDefault();
        $(".invalid-feedback").text("");

        $('#content-holder').html('<div class="row"><div class="col-md-12 text-center">Please wait! Generating Payslips...</div></div>');

        var url = $(this).closest('form').attr('action');
        var data = $(this).closest('form').serialize();

        $("#loader").removeClass("fadeOut");
        var post = ajax(url, data, 'GET');
        post.done(function (response) {
            $("#loader").addClass("fadeOut");
            if (response.status == 'success') {
                $('#content-holder').html('');
                $('#content-holder').append('<div class="row"><div class="col-md-12 text-center"><a href="'+response.data.file+'" download="download"><button class="btn btn-lg btn-success"><i class="fa fa-download"></i> Download Payslip(s)</button></a></div></div>');

                notifications('success', response.message, response.data, response.redirect, response.notifyType);
            } else if(response.status == 'warning') {
                notifications('warning', response.message, response.data, response.redirect, response.notifyType);
            } else {
                notifications('error', response.message, response.data, response.redirect, response.notifyType);
            }
        });
    });

    //Show More Details
    $('#table-holder').on('click', '.view-form .view-button', function (e) {
        e.preventDefault();
        var url = $(this).closest('form').attr('action');
        data = [];
        var holder = 'modal-holder';
        var post = ajax(url, data, 'GET');
        post.done(function (response) {
            if (response.status == 'success') {
                if (response.modalSize != undefined && response.modalSize == 'xl') {
                    holder = 'xl-modal-holder';
                }
                notifications('success', response.message, response.data, response.redirect, response.notifyType, holder);
            } else {
                notifications('error', response.message, response.data, response.redirect, response.notifyType);
            }
        });
    });

    // AJAX Link Submit
    $('#table-holder').on('click', '.ajax-link', function (e) {
        e.preventDefault();
        var url = $(this).attr('href');
        data = [];
        var holder = 'modal-holder';
        var post = ajax(url, data, 'GET');
        post.done(function (response) {
            if (response.status == 'success') {
                if (response.modalSize != undefined && response.modalSize == 'xl') {
                    holder = 'xl-modal-holder';
                }
                notifications('success', response.message, response.data, response.redirect, response.notifyType, holder);
            } else {
                notifications('error', response.message, response.data, response.redirect, response.notifyType);
            }
        });
    });

    // updates a dependant dropdown based on the value selected in the parent drop down
    // use "data-target" and "data-url" attributes to define the data retrieve path and which dependant drop down to update
    // To update another dependant drop down at the same time use
    // "data-second-url" and "data-second-target" to define values as above
    $(document).on('change', '.load-data-on-change', function () {
        // remove placeholder option
        $(this).children('option:not([value])').remove();

        var url = $(this).data('url');
        if(url != null || url != undefined){
            var selected_id = $(this).val();
            var target = $(this).data('target');
            loadDropdownOnParentChange(url, selected_id, target);
        }

        if (typeof $(this).data('second-url') !== 'undefined') {
            var secondUrl = $(this).data('second-url');
            if(secondUrl != null || secondUrl != undefined){
                var selected_id = $(this).val();
                var target = $(this).data('second-target');
                loadDropdownOnParentChange(secondUrl , selected_id, target);
            }
        }

        if (typeof $(this).data('third-url') !== 'undefined') {
            var secondUrl = $(this).data('third-url');
            if(secondUrl != null || secondUrl != undefined){
                var selected_id = $(this).val();
                var target = $(this).data('third-target');
                loadDropdownOnParentChange(secondUrl , selected_id, target);
            }
        }
    });
});

function getDataTableButtons() {
    var buttons = [];

    if ($('#custom-dataTable').hasClass('with-export')) {
        var exportButton = {
            text: 'Export CSV',
            className : 'btn excel-btn',
            action: function (e, dt, node, config) {
                $.ajax({
                    "url": $('.get-data-holder').data('url')+'?export=true&' + $.param(dt.ajax.params()),
                    "data": dt.ajax.params(),
                    "success": function (res, status, xhr) {
                        let csvContent = "data:text/csv;charset=utf-8,";
                        res.data.forEach(function(rowArray){
                            let row = rowArray.join(",");
                            csvContent += row + "\r\n";
                        });
                        var encodedUri = encodeURI(csvContent);
                        var link = document.createElement("a");
                        link.setAttribute("href", encodedUri);
                        link.setAttribute("download", moment().format('YYYY-MM-DD_H:m:s')+" "+$('title').text()+" Export.csv");
                        document.body.appendChild(link); // Required for FF

                        link.click();
                        /*var csvData = new Blob([res.data], {type: 'text/csv;charset=utf-8;'});
                        var csvURL = window.URL.createObjectURL(csvData);
                        var tempLink = document.createElement('a');
                        tempLink.href = csvURL;
                        tempLink.setAttribute('download', 'export.csv');
                        tempLink.click();*/
                    }
                });
            }
        };

        buttons.push(exportButton);
    }

    return buttons;
}

function submit_duty_toggle_request(url, data) {
    var post = ajax(url, data, 'POST');
    post.done(function (response) {
        if (response.status == 'success') {
            notifications('success', response.message, response.data, response.redirect, response.notifyType);
        } else {
            notifications('error', response.message, response.data, response.redirect, response.notifyType);
        }
    });
}

function loadDropdownOnParentChange(url, selected_id, target) {
    $("#loader").removeClass("fadeOut");

    var data = {'selected_id' : selected_id};
    var target = target; // $(this).data('target');
    var post = ajax(url, data, 'post');
    post.done(function (response) {
        $("#loader").addClass("fadeOut");

        if (response.status == 'success') {
            if (response.notifyType == 'value') {
                $(target).val(response.data);
            } else {
                $(target).html(response.data);
                $('select').selectpicker('refresh');
            }
        } else {
            notifications('error', response.message, response.data, response.redirect, response.notifyType);
        }
    });
}

function init_select2() {
    $('select').selectpicker({
        placeholder: 'Nothing selected',
        dropdownAutoWidth : true
    });
}

function submit_delete_request(url, data) {
    $("#loader").removeClass("fadeOut");
    var post = ajax(url, data, 'DELETE');
    post.done(function (response) {
        $("#loader").addClass("fadeOut");
        if (response.status == 'success') {
            window.customTable.draw(false);
            window.dataTable.draw(false);
            notifications('success', response.message, response.data, response.redirect, response.notifyType);
        } else if(response.status == 'warning') {
            notifications('warning', response.message, response.data, response.redirect, response.notifyType);
        } else {
            notifications('error', response.message, response.data, response.redirect, response.notifyType);
        }
    });
}

function getTableData() {
    var elem = $('.get-data-holder');
    $('#'+elem.data('holder')).html("<p class=\"text-center\"><br><i class=\"fa fa-spinner text-center fa-3x fa-spin\"></i></p>");
    var url = elem.data('url');
    var holder = elem.data('holder');
    var post = ajax(url, [], 'get');
    post.done(function (response) {
        if (response.status == 'success') {
            notifications('success', response.message, response.data, response.redirect, response.notifyType, holder);
        } else {
            if (response.notifyType != 'view') {
                permissionDeniedMessage();
            }
            notifications('error', response.message, response.data, response.redirect, response.notifyType);
        }
    });
}

function permissionDeniedMessage() {
    var message = $(".permission-denied-message-container").html();
    $("#table-holder").html(message);
}

function notifications(type, msg, data, redirect, notifyType, holder = '', custom_obj = '') {
    if (type == 'success') {
        if (notifyType === 'modal') {
            $('.' + holder + ' .modal-content').html(data);
            $('.tag-input').tagsinput();
            init_select2();
            json_beautify();
            $('.datepicker').datepicker();
            $('.month-datepicker').datepicker({
                maxViewMode : 2,
                minViewMode : 1
            });
            $('.' + holder).modal('show');
        } else if (notifyType === 'view') {
            $('#' + holder).html(data);
            $("#" + holder+" #custom-dataTable").DataTable({
                "order": []
            });

        } else {
            if (msg != undefined && msg != '') {
                new Noty({
                    theme: 'metroui',
                    type: 'success',
                    timeout: 5000,
                    text: "<b style='text-transform: capitalize'>Success!</b><br>"+msg,
                }).show();
            }
        }
    } else if (type == 'warning') {
        if (notifyType === 'message') {
            if (msg != undefined && msg != '') {
                new Noty({
                    theme: 'metroui',
                    type: 'warning',
                    text: "<b style='text-transform: capitalize'>Warning!</b><br>"+msg,
                }).show();
            }
        }
    } else {
        if (notifyType === 'message') {
            if (msg != undefined && msg != '') {
                new Noty({
                    theme: 'metroui',
                    type: 'error',
                    text: "<b style='text-transform: capitalize'>Error!</b><br>"+msg,
                }).show();
            }
        }
        if (notifyType === 'validation') {
            $.each(data, function (index, value) {
                index = index.replace(/\./g, '\\.');
                var selector = '.' + index + "-error";
                console.log(selector);
                if (Array.isArray(value) && value[0] != '') {
                    errorText = "";
                    $.each(value, function (index, value) {
                        if(index != 0) { errorText += "<br>" }
                        errorText += value;
                    });

                    $(selector).html(errorText);
                    $(selector).removeClass('success-msg');
                    $(selector).addClass('error-msg');
                }
            });
        }
    }

    if (redirect === 'self') {
        if(type == 'success'){
            setTimeout(function () {
                location.reload(true);
            }, 1);
        }else{
            setTimeout(function () {
                location.reload(true);
            }, 2000);
        }

    } else if (redirect === '' || redirect == undefined) {
        setTimeout(function () {
            $('.notifi-succss').animate({
                width: '0%',
                opacity: 0
            });
            $('.notifi-error').animate({
                width: '0%',
                opacity: 0
            });
        }, 5000);
    } else {
        setTimeout(function () {
            window.location.href = redirect;
        }, 1);
    }
}

function json_beautify() {
    $('.json-beautify').each(function (index, value){
        var textedJson = JSON.stringify(JSON.parse($(this).text()), undefined, 4);
        $(this).text(textedJson);
    });
}

function ajax(url, data, method, hasFileUpload) {
    var processData = true;
    var contentType = "application/x-www-form-urlencoded; charset=UTF-8";
    if (hasFileUpload !== undefined) {
        processData = false;
        contentType = false;
    }

    return $.ajax({
        'dataType': 'json',
        'type': method,
        'url': url,
        'data': data,
        'processData': processData,
        'contentType': contentType,
        beforeSend: function(xhr, type) {
            if (!type.crossDomain) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            }
        }
    });
}

function changeUserStatus(_this, id, url) {
    var status = $(_this).prop('checked') == true ? 1 : 0;
    let _token = $('meta[name="csrf-token"]').attr('content');

    $.ajax({
        url: url,
        type: 'post',
        data: {
            _token: _token,
            id: id,
            status: status
        },
        success: function (response) {
            if (response.status == 'success') {
                notifications('success', response.message, response.data, response.redirect, response.notifyType);
            } else {
                notifications('error', response.message, response.data, response.redirect, response.notifyType);
            }
        }
    });
}

//Double Side Table Scrollbar
$(document).ready(function() {
    $(function(){
        $(".dataTables_scrollHead").scroll(function(){
            $(".dataTables_scrollBody")
                .scrollLeft($(".dataTables_scrollHead").scrollLeft());
        });
        $(".dataTables_scrollBody").scroll(function(){
            $(".dataTables_scrollHead")
                .scrollLeft($(".dataTables_scrollBody").scrollLeft());
        });
    });
});

//Record Video Modal
$(document).on('click', '.recordButton', function (e) {

    var url = $(this).data('url');
    var holder = 'modal-holder';

    $.get(url).done(function (response) {
        if (response.status == 'success') {
            if (response.modalSize != undefined && response.modalSize == 'xl') {
                holder = 'xl-modal-holder';
            }
            notifications('success', response.message, response.data, response.redirect, response.notifyType, holder);
        } else if(response.status == 'warning') {
            notifications('warning', response.message, response.data, response.redirect, response.notifyType);
        } else {
            notifications('error', response.message, response.data, response.redirect, response.notifyType);
        }
    });
});

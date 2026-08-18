$(function () {
    $(document).on("click",".schedule-remove-btn", removeSchedule)
});

function drawCalendar(schedule) {
    var calendarEl = document.getElementById('calendar-holder');

    window.scheduleCalendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'dayGrid', 'interaction', 'bootstrap', 'moment' ],
        themeSystem: 'bootstrap',
        height: 500,
        selectable: true,
        events: schedule,
        selectOverlap: false,
        selectAllow: function(selectInfo) {
            return moment().diff(selectInfo.start, 'days') <= 0
        },
        select: function(info) {
            loadAddEventForm(info.startStr, info.endStr)
        },
        eventClick: function(info) {
            loadEditEventForm(info.event.id)
        }
    });

    window.scheduleCalendar.render();
}

function customFilterHandler() {
    var elem = $('.get-data-holder');
    $(".invalid-feedback").text("");
    $('#calendar-holder').html("<p class=\"text-center\"><br><i class=\"fa fa-spinner text-center fa-3x fa-spin\"></i></p>");
    var url = elem.data('url');
    var holder = 'kpi-holder';
    var data = {
        "user_id": $('#user').val(),
    };
    var post = ajax(url, data, 'get');
    post.done(function (response) {
        if (response.status == 'success') {
            $('#calendar-holder').html("");
            drawCalendar(response.data.schedule)
        } else {
            if (response.notifyType != 'view') {
                permissionDeniedMessage();
            }
            $('#calendar-holder').html('<h5 class="text-center">Failed to load data!</h5>');
            notifications('error', response.message, response.data, response.redirect, response.notifyType);
        }
    });
}

function loadAddEventForm(start, end) {
    var data = {
        "user_id": $('#user').val(),
        "start": start,
        "end": end,
    };
    var url = addFormUrl;
    var holder = 'modal-holder';
    var post = ajax(url, data, 'get');
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

function loadEditEventForm(id) {
    var data = {};
    var url = editFormUrl+'/'+id+"/edit";
    var holder = 'modal-holder';
    var post = ajax(url, data, 'get');
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

function removeSchedule() {
    var confirmation = confirm("Remove this event?");
    if (confirmation)  {
        var id = $(this).data('id');
        sendScheduleDeleteRequest(id)
    }
}

function sendScheduleDeleteRequest(id) {
    var data = {};
    var url = editFormUrl+'/'+id;
    var post = ajax(url, data, 'delete');
    post.done(function (response) {
        if (response.status == 'success') {
            customModalFormSubmitCallback(response);
            $('.modal').modal('hide');
            notifications('success', response.message, response.data, response.redirect, response.notifyType);
        } else {
            if (response.notifyType != 'view') {
                permissionDeniedMessage();
            }
            notifications('error', response.message, response.data, response.redirect, response.notifyType);
        }
    });
}

function customModalFormSubmitCallback(response) {
    console.log(response.data.schedule);
    var sources = window.scheduleCalendar.getEventSources();
    console.log(sources);
    var scheduleSource = sources[0];
    scheduleSource.remove();
    window.scheduleCalendar.addEventSource( response.data.schedule )
}
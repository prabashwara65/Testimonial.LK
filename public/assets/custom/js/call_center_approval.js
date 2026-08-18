$(function () {
    $('#custom-dataTable-with-selection tbody').on('click', 'tr', function () {
        var id = parseInt(this.id);
        var index = $.inArray(id, selected);

        if (index === -1) {
            selected.push(id);
        } else {
            selected.splice(index, 1);
        }

        $(this).toggleClass('selected');
    });
});

// https://datatables.net/examples/server_side/select_rows.html
var selected = [];

window.customTableSecondary2 = $('#custom-dataTable-with-selection').DataTable({
    "processing": true,
    "order": [],
    "scrollX": true,
    "serverSide": true,
    dom: "<'row'<'col-sm-4'l><'col-sm-2'><'col-sm-6'fB>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    "aoColumnDefs": [
        {bSortable: false, aTargets: ['no-sort']} // Disable sorting on columns marked as so
    ],
    "rowCallback": function (row, data) {
        if ($.inArray(data.DT_RowId, selected) !== -1) {
            //console.log(data.DT_RowId);
            $(row).addClass('selected');
        }
    },
    "ajax": {
        dataType: "JSON",
        type: "GET",
        url: $('.get-data-holder').data('url'),
        data: function (d) {
            d.form = $('.filter-form').serializeObject();
        },
        async: true,
        "dataSrc": function (json) {
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
    "buttons": getDataTableWithSelectionButtons()
});

$(document).on("click", ".filter-form.filter-form-secondary .filter-form-submit", function (e) {
    e.preventDefault();
    window.customTableSecondary2.draw();
});

function getDataTableWithSelectionButtons() {
    var buttons = [
        {
            text: 'Approval',
            className: 'btn btn-info',
            action: function () {
                if (selected.length === 0) {
                    // load the all page rows ids

                    var table = $('#custom-dataTable-with-selection').DataTable();
                    var ids = table.rows({page: 'all'}).ids().toArray();

                    //console.log( ' a ' + ids);
                    var data = {
                        "selected": ids,
                        "type": 2,
                    };
                    var r = confirm("Are you sure the want to Approval this page data");

                } else {
                    var data = {
                        "selected": selected,
                        "type": 2,
                    };
                    var r = confirm("Are you sure the want to Approval selected data");
                }

                if (r == true) {
                    var post = ajax('call-center-approval/multiple-approval', data, 'post');
                    post.done(function (response) {
                        if (response.status == 'success') {
                            notifications('success', response.message, response.data, response.redirect, response.notifyType);
                            location.reload();
                        } else {
                            notifications('error', response.message, response.data, response.redirect, response.notifyType);
                        }
                    });
                } else {
                    //notifications('warning', 'Select the rows Multiple Approval', '', '', 'message');
                }
            }
        },
    ];

    if ($('#custom-dataTable-with-selection').hasClass('with-export')) {
        var exportButton = {
            text: 'Export CSV',
            className: 'btn excel-btn',
            action: function (e, dt, node, config) {
                $.ajax({
                    "url": $('#table-holder').data('url') + '?export=true&' + $.param(dt.ajax.params()),
                    "data": dt.ajax.params(),
                    "success": function (res, status, xhr) {
                        let csvContent = "data:text/csv;charset=utf-8,";
                        res.data.forEach(function (rowArray) {
                            let row = rowArray.join(",");
                            csvContent += row + "\r\n";
                        });
                        var encodedUri = encodeURI(csvContent);
                        var link = document.createElement("a");
                        link.setAttribute("href", encodedUri);
                        link.setAttribute("download", moment().format('YYYY-MM-DD_H:m:s') + " " + $('title').text() + " Export.csv");
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

$(document).on('click', '#table-holder-secondary .view-form .view-button', function (e) {
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





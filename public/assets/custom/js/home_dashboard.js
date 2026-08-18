
window.customTableSecondary = $('#custom-dataTable-with-selection').DataTable( {
    "processing": true,
    //"bPaginate": false,
    "bLengthChange": false,
    "searching": false,
    "order": [],
    "scrollX": true,
    "serverSide": true,
    dom:"<'row'<'col-sm-4'l><'col-sm-5'><'col-sm-3'fB>>" +
    "<'row'<'col-sm-12'tr>>" +
    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    "aoColumnDefs": [
        { bSortable: false, aTargets: ['no-sort'] } // Disable sorting on columns marked as so
    ],
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
    "buttons": getDataTableWithSelectionButtons()
});

function getDataTableWithSelectionButtons() {
    var buttons = [];

    if ($('#custom-dataTable-with-selection').hasClass('with-export')) {
        var exportButton = {
            text: 'Export CSV',
            className : 'btn excel-btn',
            action: function (e, dt, node, config) {
                $.ajax({
                    "url": $('.get-data-holder').data('url')+'?export=true&' + $.param(dt.ajax.params()),
                    //"url": $('#table-holder').data('url')+'?export=true&' + $.param(dt.ajax.params()),
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

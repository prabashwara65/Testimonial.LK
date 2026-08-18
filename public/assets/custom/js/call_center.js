window.customTableSecondary = $('#custom-dataTable-secondary').DataTable( {
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
        url: $('#table-holder-secondary').data('url'),
        data: function(d){
            d.form = $('.filter-form-secondary').serializeObject();
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
    "buttons": getSecondaryDataTableButtons()
});

$(document).on("click", ".filter-form.filter-form-secondary .filter-form-submit", function(e) {
    e.preventDefault();
    window.customTableSecondary.draw();
} );

function getSecondaryDataTableButtons() {
    var buttons = [];

    if ($('#custom-dataTable-secondary').hasClass('with-export')) {
        var exportButton = {
            text: 'Export CSV',
            className : 'btn excel-btn',
            action: function (e, dt, node, config) {
                $.ajax({
                    "url": $('#table-holder-secondary').data('url')+'?export=true&' + $.param(dt.ajax.params()),
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

/**
 * CALLER NIC VALIDATE
 * update by : gemunu
 * update at : 2020-11-26
 */

$(document).on('click', '#verify_nic', function (e) {
    e.preventDefault();

    nic_verification();
});

/**
 * update by : gemunu
 * update at : 2021-01-11
 * some issue of verify and after not clear issue fix with key up action
 */

$(document).on('keyup', '#caller_custom_nic', function (e) {
    e.preventDefault();
    nic_verification();
});

function nic_verification() {

    var ba_custom_nic = $('#ba_custom_nic').val().toLowerCase();
    var caller_custom_nic = $('#caller_custom_nic').val().toLowerCase();

    $('.nic_match-error').html('');

    if (caller_custom_nic.length == 0) {
        $('.caller_custom_nic_two-error').html('<b style="color: #0a6ebd"> Enter Valid NIC </b>');


    } else if (caller_custom_nic.length == 10 || caller_custom_nic.length == 12) {

        // GET AGE
        var ageValidate = getAge(caller_custom_nic);
        //console.log(ageValidate);

        if (ageValidate) {
            var ageMsg = "age validated";
            $('#age_validate').val('Yes');
        } else {
            var ageMsg = "<b>Age is not valid </b>";
            $('#age_validate').val('No');
        }

        // COMPARE THE BA CUSTOMER NIC AND CALLER CUSTOMER NIC
        if (ba_custom_nic.search(caller_custom_nic) !== -1 && ageValidate) {

            $('.caller_custom_nic_two-error').html('<span style="color: #2ceb1b"><b> NIC Matched & ' + ageMsg + ' </b></span>');
            $('#correct_nic_div').css('display', 'none');
            $('#nic_match').val('Yes');

        } else {
            var ba_nic = ba_custom_nic.split("");

            var caller_nic = caller_custom_nic.split("");
            var differa = array_diff(ba_nic, caller_nic);

            $('.caller_custom_nic_two-error').html('<span style="color: red"> NIC is not matched & ' + ageMsg + ' <br> <b>  Mismatch : ' + differa + '</b> </span>');

            $('#correct_nic_div').css('display', 'block');
            $('#nic_match').val('No');
        }

    } else if(caller_custom_nic.length === 3 && caller_custom_nic === "n/a"){
        $('.caller_custom_nic_two-error').html('');
        $('#nic_match').val('-');

    } else {
        $('.caller_custom_nic_two-error').html('<span style="color: red">Invalid NIC </span>');
        $('#nic_match').val('');
    }
}

/**
 * update by : gemunu
 * update at : 2020-12-03
 * clear button action
 */
$(document).on('click', '#clear_nic', function (e) {
    e.preventDefault();

    $('.caller_custom_nic-error').html('');
    $('.caller_custom_nic_two-error').html('');
    $('.nic_match-error').html('');

    $('#correct_nic_div').css('display', 'none');

    $('#caller_custom_nic').val('');
    $('#age_validate').val('');
    $('#nic_match').val('');
});


/**
 * COMPARE THE BA CUSTOMER NIC AND CALLER CUSTOMER NIC
 * update by : gemunu
 * update at : 2020-11-26
 * @param array1
 * @param array2
 * @returns {*}
 */
function array_diff(array1, array2) {

    if (array1.length == array2.length) {
        var dif = [];

        for (var i = 0; i < array2.length; i++) {
            //console.log(array2[i], array1[i]);
            if (array1[i] != array2[i]) dif.push(array1[i]);
        }
        return dif;

    } else {
        return " Two Type Of NIC";
    }
}

/**
 * Calculate the valid age
 * update by : gemunu
 * update at : 2020-11-26
 * @param NICNo
 * @returns {*}
 */
function getAge(NICNo) {

    // Year
    if (NICNo.length == 10) {
        yearDob = "19" + NICNo.substr(0, 2);
        dayText = parseInt(NICNo.substr(2, 3));
    } else {
        yearDob = NICNo.substr(0, 4);
        dayText = parseInt(NICNo.substr(4, 3));
    }

    // Gender
    if (dayText > 500) {
        gender = "Female";
        dayText = dayText - 500;
    } else {
        gender = "Male";
    }

    // Day Digit Validation
    if (dayText < 1 && dayText > 366) {
        return "Invalid NIC NO";

    } else {

        //Month
        if (dayText > 335) {
            day = dayText - 335;
            month = "12";
        }
        else if (dayText > 305) {
            day = dayText - 305;
            month = "11";
        }
        else if (dayText > 274) {
            day = dayText - 274;
            month = "10";
        }
        else if (dayText > 244) {
            day = dayText - 244;
            month = "09";
        }
        else if (dayText > 213) {
            day = dayText - 213;
            month = "08";
        }
        else if (dayText > 182) {
            day = dayText - 182;
            month = "07";
        }
        else if (dayText > 152) {
            day = dayText - 152;
            month = "06";
        }
        else if (dayText > 121) {
            day = dayText - 121;
            month = "05";
        }
        else if (dayText > 91) {
            day = dayText - 91;
            month = "04";
        }
        else if (dayText > 60) {
            day = dayText - 60;
            month = "03";
        }
        else if (dayText < 32) {
            month = "01";
            day = dayText;
        }
        else if (dayText > 31) {
            day = dayText - 31;
            month = "02";
        }
    }

    var now = new Date();

    var yearNow = now.getFullYear();
    var monthNow = now.getMonth()+1;

    yearAge = yearNow - yearDob;
    monthDob = month;

    if (monthNow >= monthDob)
        var monthAge = monthNow - monthDob;
    else {
        yearAge--;
        var monthAge = 12 + monthNow -monthDob;
    }

    //console.log(yearDob, dayText);
    //console.log(' yearAge ' + yearAge + ' monthAge ' + monthAge);

    if(yearAge > 21){
       return true;

    }else if(yearAge === 21 && monthAge >= 7) {
        return true;

    }else{
        return false;
    }
}

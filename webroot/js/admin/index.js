// resizeable modal MAP
$('#gpsMapModal .modal-content').resizable({
    //alsoResize: ".modal-dialog",
    minHeight: 400,
    minWidth: 300
});

// resizeable modal Report (path:element/admin/popup_event_admin)
$('#eventModal .modal-content').resizable({
    //alsoResize: ".modal-dialog",
    minHeight: 730,
    minWidth: 550
});

// resizeable modal Face Image (path:element/admin/popup_face_admin)
$('#faceModal .modal-content').resizable({
    //alsoResize: ".modal-dialog",
    minHeight: 450,
    minWidth: 400
});

$('#gpsMapModal').on('show.bs.modal', function() {
    $(this).find('.modal-body').css({
        'max-height': '100%'
    });
});

$('.modal-content').on('resize', function() {
    $('#GPSCONTENT').height($(this).height() - 200)
        // console.log()
})

$(document).on('click', '.img-zoom', function() {
    $('#venoboxImage').attr('src', $(this).attr('src'))
    $('#viewImageModal').modal()
})

var rad = function(x) {
    return x * Math.PI / 180;
};

var load_table = -1

var map;

var sStaffID = ''
var sCustomerID = ''
var col
var dir

var lat = 0
var long = 0
var slat = 0
var slong = 0
var table = $('#serverDataTable').DataTable({
    "dom": '<"pull-left"fi>tp',
    "processing": true,
    "serverSide": true,
    "pageLength": PAGE_LIMIT_SPECIFIC,
    "order": [
        [2, 'desc']
    ],
    "ajax": {
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'ajax/getAllLongLatByDate',
        type: 'POST',
        data: function(d) {
            let date_from = $("#datepicker_date").val()
            let date_to = $("#datepicker_date_to").val()
            d.customerIds = sCustomerID
            d.staffIds = sStaffID
            d.date_from = date_from;
            d.date_to = date_to;
            d.auth = $('#Auth').val()
        }
    },
    "columns": [{
            "data": null,
            "sortable": false,
            "sClass": "text-right",
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
            "data": "StaffID",
            "sClass": "Staff-ID text-center",
            render: function(data, type, row) {
                var distance = (row.distance.toString().indexOf(".") > 0) ? commafy(Number(row.distance.toString().split(".")[0])) + "." + row.distance.toString().split(".")[1] : row.distance
                return "" +
                    row.StaffID +
                    ''
                    // '<span class="tooltip-distance">StaffID: ' + row.StaffID + ' - Total distances: ' + distance + ' km</span>'
            }
        },
        {
            "data": "StaffName",
            render: function(data, type, row) {
                return "" +
                    "<a href=\"javascript:void(0)\" class=\"open-staff\" data-staff=\"" + row.StaffID + "\">\n" +
                    "\t" + row.StaffName + "\n" +
                    "</a>"
            }
        },
        {
            "data": "checkin",
            "sClass": "text-center checkin",
            render: function(data, type, row) {
                var timein = moment(row.timein).format("HH:mm")
                var time_request = row.alerttime
                var flag_valid = ""
                if (timein > time_request) {
                    flag_valid = '<input type="hidden" class="flag-late" value="late" />'
                } else {
                    flag_valid = '<input type="hidden" class="flag-late" value="" />'
                }
                return flag_valid + row.checkin
            }
        },
        { "data": "checkout", "sClass": "text-center" },
        {
            "data": "CustomerName",
            render: function(data, type, row) {
                return ""
            }
        },
        {
            data: "ID",
            "sClass": "text-center",
            orderable: false,
            render: function(data, type, row) {
                return ""
            }
        },
        {
            data: 'ID',
            "sClass": "text-right",
            orderable: false,
            render: function(data, type, row) {
                return ""
            }
        },
        // {
        //     data: "ID",
        //     "sClass": "text-center",
        //     orderable: false,
        //     render: function(data, type, row) {
        //         return ""
        //     }
        // },
        {
            data: "ID",
            "sClass": "text-center",
            orderable: false,
            render: function(data, type, row) {
                return "" +
                    "<a href=\"#\" data-toggle=\"modal\" \n" +
                    "\tdata-checkin=\"" + removeNull(row.imgcheckin) + "\" \n" +
                    "\tdata-checkout=\"" + removeNull(row.imgcheckout) + "\" \n" +
                    "\tclass=\"open-Face\">\n" +
                    "\t<i class=\"far fa-smile-beam\"></i>\n" +
                    "</a>";
            }
        },
    ],
    "drawCallback": function(settings) {
        if (load_table % 10 == 0) { //10
            var api = this.api();
            var lst_events = api.ajax.json().data;
            MapModule.initGoogleMap(lst_events)
            MapModule.addLocationsToMap(lst_events)
        }

        load_table++

        $('tr .flag-late').each(function() {
            if ($(this).val() == "late") {
                $(this).closest("tr").addClass("hl-row")
            }
        })

        // calcualte distance of staffs
        // var staffs = api.ajax.json().sortedStaffs;
        // calDistanceStaff(staffs)

    }
});

// function calDistanceStaff(data){
//     $.each(data, function(index, value){
//         var totalDistance = 0
//         for(var i = 0; i < value.length-1; i++){
//             totalDistance += Number(getDistance({'lat': value[i].CheckinLocation.split(",")[0], 'lng': value[i].CheckinLocation.split(",")[1]},
//                                                 {'lat': value[i+1].CheckinLocation.split(",")[0], 'lng': value[i+1].CheckinLocation.split(",")[1]})
//                                     .replaceAll(",", ""))

//         }
//         totalDistance = (totalDistance / 1000).toFixed(2)

//         // find staffid in table
//         $('#serverDataTable .Staff-ID').each(function(){
//             if($(this).html() == index){
//                 $(this).append('<span class="tooltip-distance">StaffID: '+index+' - Total distances: '+totalDistance+' km</span>')
//             }
//         })
//     })
// }

let gps_events = []
var centerDefault = 10
jQuery(document).ready(function() {
    // staff
    $(".sStaffID").select2({
        allowClear: true,
        placeholder: 'Please choose staff',
        tags: true
    });
    $(".sStaffID").on("select2:select", function(e) {
        sStaffID = e.params.data.id;
        $('#filterSchedule').click()
    });
    $(".sStaffID").on("select2:clear", function(e) {
        sStaffID = ''
        $('#filterSchedule').click()
    });

    // customer
    // $(".sCustomerID").select2({
    //     allowClear: true,
    //     placeholder: 'Please choose customer',
    //     tags: true
    // });
    // $(".sCustomerID").on("select2:select", function(e) {
    //     sCustomerID = e.params.data.id;
    //     $('#filterSchedule').click()
    // });
    // $(".sCustomerID").on("select2:clear", function(e) {
    //     sCustomerID = ''
    //     $('#filterSchedule').click()
    // });

    // TIMEPICKER
    $('#timepicker_alert')
        .datetimepicker({ format: 'HH:mm' })
        .keypress(function(event) { event.preventDefault(); })
        .keydown(false)

    // date range picker
    setRangeDatepicker.rangeDay('#datepicker_date', '#datepicker_date_to')

    $('#submitAlert').on('click', function(e) {
        e.preventDefault()


        var valid = validateFormSendMail()

        if (valid == false) return false

        $(this).attr("disabled", true)

        $.ajax({
            headers: { 'X-CSRF-TOKEN': __csrfToken },
            url: __baseUrl + 'admin/schedule/setSendMail',
            type: 'POST',
            data: {
                'alert': $("#timepicker_alert").val(),
                'mail_1': $("#mail1").val(),
                'mail_2': $("#mail2").val()
            },
            success: function(response) {
                $(this).attr("disabled", false)
                if (response.success == 1) {
                    $('#noteMailModal').modal()
                    $('#filterSchedule').click()
                } else {
                    swal({
                        title: "Submited failed!",
                        text: "Please reload page and try again. Thank you! ",
                        icon: "error"
                    })
                    console.log(response)
                }
            },
            error: function(response) {
                $(this).attr("disabled", false)
                console.log(response)
            }
        })

        $(this).attr("disabled", false)
        return false

    })
    $(document).on('click', '#filterSchedule', function() {
        load_table = 0
        showMapByStaff()
        var date_from = $("#datepicker_date").val()
        var date_to = $("#datepicker_date_to").val()
        if (date_from == moment().format('YYYY/MM/DD') && date_to == moment().format('YYYY/MM/DD')) {
            autoReload = setInterval(function() {
                showMapByStaff()
            }, 30000)
        } else {
            clearInterval(autoReload)
        }
    })

    $(document).on('click', '.open-staff', function() {
        $.ajax({
            headers: { 'X-CSRF-TOKEN': __csrfToken },
            url: __baseUrl + 'admin/schedule/getStaff',
            type: 'POST',
            data: {
                'staffID': $(this).attr('data-staff')
            },
            success: function(res) {
                if (res.success == 1) {
                    var data = res.data
                    $('#modalInfoStaff #StaffID').html(data.StaffID)
                    $('#modalInfoStaff #Name').html(data.Name)
                    $('#modalInfoStaff #Position').html(data.Position)
                    if (data.Image && data.Image != "") {
                        var img = '<img src="' + __baseUrl + "files/StaffImage/" + data.Image + '" class="img-zoom" style="width:150px">'
                        $('#modalInfoStaff #InfoArea').html(img)

                    }
                    // $('#modalInfoStaff #InfoArea').html(data.AreaName)
                    // $('#modalInfoStaff #InfoTitle').html(data.Title)
                    // $('#modalInfoStaff #InfoRegion').html(data.RegionName)
                    $('#modalInfoStaff').modal('show')
                }
            }
        })
    })

    $(document).on('click', '.open-customer', function() {
        $.ajax({
            headers: { 'X-CSRF-TOKEN': __csrfToken },
            url: __baseUrl + 'admin/schedule/getCustomer',
            type: 'POST',
            data: {
                'customerID': $(this).attr('data-customer')
            },
            success: function(res) {
                if (res.success == 1) {
                    var data = res.data
                    $('#modalInfoCustomer #CustomerID').html(data.CustomerID)
                    $('#modalInfoCustomer #Name').html(data.Name)
                    $('#modalInfoCustomer #AreaName').html(data.AreaName)
                    $('#modalInfoCustomer #Address').html(data.Address)
                    $('#modalInfoCustomer #TaxCode').html(data.TaxCode)
                    $('#modalInfoCustomer #Latitude').html(data.Latitude)
                    $('#modalInfoCustomer #Longitude').html(data.Longitude)
                    var impleDate = (data.ImplementDate) ? moment(data.ImplementDate).format('YYYY/MM/DD') : ""
                    $('#modalInfoCustomer #ImplementDate').html(impleDate)
                    $('#modalInfoCustomer #PositionNo').html(data.PositionNo)
                    $('#modalInfoCustomer').modal('show')
                }
            }
        })
    })

    $(document).on("click", ".open-AddBookDialog", function() {
        $("#StaffID").html($(this).attr('data-StaffID'));
        $("#StaffName").html($(this).attr('data-StaffName'));
        $("#date").html($(this).attr('data-date'));
        // $("#time").html(  $(this).attr('data-ftime') );
        $("#CustomerID").html($(this).attr('data-CustomerID'));
        $("#CustomerName").html($(this).attr('data-CustomerName'));
        var report_id = $(this).attr('data-id')

        $.ajax({
            headers: { 'X-CSRF-TOKEN': __csrfToken },
            url: __baseUrl + 'admin/schedule/getReport',
            type: 'POST',
            data: {
                'id': report_id,
                'timecardID': $(this).attr('data-timecardid')
            },
            success: function(res) {
                const data = res.report
                if (data != null) {
                    $("#time").html(data.ftime);
                    setFormReport(res)
                    appendImages(report_id, res.images)
                } else {
                    $('#time').html(res.timecard.ftime)
                    $("textarea-report").val('');
                    $('.content-report').css('display', 'none')
                    $('.form-report').css('display', 'none')
                }

            }
        })

        $("#Picture").attr("data-checkIn", $(this).attr('data-imgcheckin'))
        $("#Picture").attr("data-checkOut", $(this).attr('data-imgcheckout'))

        setTimeout(function() {
            $('#eventModal').modal()
        }, 500)
    });

    $(document).on("click", "#Picture", function() {
        let img_in = $(this).attr('data-checkIn');
        let img_out = $(this).attr('data-checkOut');
        if (img_in) {
            img_in = '<img src="' + __baseUrl + img_in + '" width="150px">'
        }
        if (img_out) {
            img_out = '<img src="' + __baseUrl + img_out + '" width="150px">'
        }
        $("#checkIn").html(img_in);
        $("#checkOut").html(img_out);

        $('#faceModeClose').hide()
        $('#faceModeBack').show()
        $('#eventModal').modal('hide')

        $('#faceModal').modal()
    });

    $(document).on("click", "#faceModeBack", function() {
        $('#faceModal').modal('hide')
        $('#eventModal').modal()
    });

    $(document).on("click", "#close-report", function() {
        $('#eventModal').modal('hide')
    });

    $(document).on("click", ".open-Face", function() {
        let img_in = $(this).attr('data-checkIn');
        let img_out = $(this).attr('data-checkOut');
        if (img_in) {
            img_in = '<img src="' + __baseUrl + img_in + '" width="150px">'
        }
        if (img_out) {
            img_out = '<img src="' + __baseUrl + img_out + '" width="150px">'
        }
        $("#checkIn").html(img_in);
        $("#checkOut").html(img_out);


        $('#faceModeClose').show()
        $('#faceModeBack').hide()
        $('#faceModal').modal()
    });

    $(document).on("click", "#faceModeClose", function() {
        $('#faceModal').modal('hide')
    });

    $(document).on("click", ".open-GPS", function() {
        //do something
        gps_events = {
            long: $(this).attr("data-long"),
            lat: $(this).attr("data-lat"),
            CustomerName: $(this).attr("data-CustomerName"),
            slong: $(this).attr("data-slong"),
            slat: $(this).attr("data-slat"),
            StaffName: $(this).attr("data-StaffName")
        }
        GPSCheckinModule.initGoogleMap(gps_events)
        GPSCheckinModule.addLocationsToMap(gps_events)
        $("#gpsMapModal").modal()
    });

    $('#GPSCONTENT').on("wheel", function(evt) {
        // console.log(evt.originalEvent.deltaY);
        // var i = 10
        if (evt.originalEvent.deltaY > 0) {
            // GPSCheckinModule.initGoogleMap(gps_events, --centerDefault)
            // GPSCheckinModule.addLocationsToMap(gps_events)
            mapCheckin.setZoom(--centerDefault)
        } else {
            // GPSCheckinModule.initGoogleMap(gps_events, ++centerDefault)
            // GPSCheckinModule.addLocationsToMap(gps_events)
            mapCheckin.setZoom(++centerDefault)
        }
    });


    $("#close-report").click(function() {
        //do something
        $("#largeModal").modal('hide')
    });

})



var setRangeDatepicker = (function() {
    var rangeDay = function(dateFrom, dateTo, startDate = 0) {
        // var tdate = new Date();
        // var ddate = new Date(tdate.setDate(tdate.getDate() - 30));
        jQuery(dateFrom).datepicker({
                format: "yyyy/mm/dd",
                startDate: startDate,
                autoclose: true,
                todayHighlight: true,

            })
            .on("change", function() {
                jQuery(dateTo).datepicker("destroy");
                jQuery(dateTo).datepicker({
                    format: "yyyy/mm/dd",
                    startDate: jQuery(dateFrom).val(),
                    autoclose: true,
                    todayHighlight: true,
                })
                if (jQuery(dateTo).val() < jQuery(dateFrom).val()) {
                    jQuery(dateTo).val(jQuery(dateFrom).val())
                }
            })
            .on('click', function() {
                jQuery(dateFrom).datepicker('update', jQuery(dateFrom).val())
            })
            .on('changeDate', function() {
                $('#filterSchedule').click()
            });
        jQuery(dateTo).datepicker({
                format: "yyyy/mm/dd",
                startDate: startDate,
                autoclose: true,
                todayHighlight: true,
            })
            .on("change", function() {
                jQuery(dateFrom).datepicker("destroy");
                jQuery(dateFrom).datepicker({
                    format: "yyyy/mm/dd",
                    startDate: startDate,
                    endDate: jQuery(dateTo).val(),
                    autoclose: true,
                    todayHighlight: true,
                })
                if (jQuery(dateTo).val() < jQuery(dateFrom).val()) {
                    jQuery(dateFrom).val(jQuery(dateTo).val())
                }
            })
            .on('click', function() {
                jQuery(dateTo).datepicker('update', jQuery(dateTo).val())
            })
            .on('changeDate', function() {
                $('#filterSchedule').click()
            });
    }

    var validDay = function(dateFrom, dateTo) {
        var from = jQuery(dateFrom).val()
        var to = jQuery(dateTo).val()
        if (from == "" || to == "") {
            swal('Please input date.', {
                buttons: {
                    cancel: "OK",
                },
            });
            return false
        } else if (to < from) {
            swal('Date to cannot set before date from.', {
                buttons: {
                    cancel: "OK",
                },
            });
            jQuery(dateTo).datepicker('clearDates')
            jQuery(dateTo).val("");
            return false
        } else return true
    }

    return {
        rangeDay: rangeDay,
        validDay: validDay
    }
})();

function validateFormSendMail() {
    // ==> validate mail
    var valid_mail = 0
    var filled_mail = 0
        // mail 1
        // var valid_mail1 = ValidateEmail()
    var mail1 = $("#mail1").val()
    if (mail1 != "") {
        ++filled_mail
        if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(mail1)) {
            ++valid_mail
        } else {
            alert("You have entered input Email1 an invalid email address!")
            return (false)
        }
    }

    var mail2 = $("#mail2").val()
    if (mail2 != "") {
        ++filled_mail
        if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(mail2)) {
            ++valid_mail
        } else {
            alert("You have entered input Email1 an invalid email address!")
            return (false)
        }
    }

    if (filled_mail == 0) {
        alert("Please filled email!")
        return false
    }

    // if (valid_mail1) {++valid_mail }
    // // mail 2
    // var valid_mail2 = ValidateEmail($("#mail1").val())
    // if (valid_mail2) {++valid_mail }
    // ==> validate alert
    var alert_time = $("#timepicker_alert").val()

    if (alert_time == "") {
        alert("Please filled alert time !")
        return false
    }

    return true
}

function ValidateEmail(mail) {
    if (mail != "") {
        if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(mail)) {
            return (true)
        }
        alert("You have entered an invalid email address!")
        return (false)
    }
}

function getDistance(p1, p2) {
    var R = 6378137; // Earth’s mean radius in meter
    var dLat = rad(p2.lat - p1.lat);
    var dLong = rad(p2.lng - p1.lng);
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(rad(p1.lat)) * Math.cos(rad(p2.lat)) *
        Math.sin(dLong / 2) * Math.sin(dLong / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = R * c;
    return commafy(d.toFixed(0)); // returns the distance in meter
};

function commafy(num) {
    var str = num.toString().split('.');
    str[0] = str[0].replace(/(\d)(?=(\d{3})+$)/g, '$1,');
    if (str[1]) {
        str[1] = str[1].replace(/(\d{3})/g, '$1 ');
    }
    return str.join(',');
}

function showMapByStaff() {
    // datepicker = $("#datepicker_date").val()
    // if (datepicker == '') datepicker = $("#default_datepicker").val()
    table.ajax.reload();
}

function removeNull(string) {
    if (string == null) {
        return ''
    } else {
        return string
    }
}

var MapModule = (function() {
    var mapElement = document.getElementById("staffScheduleMap");
    var mapInstance = null;

    var initGoogleMap = function() {

        mapInstance = new google.maps.Map(mapElement, {
            zoom: 5.5,
            center: new google.maps.LatLng(15.967674, 108.020437),
            //center: new google.maps.LatLng(lst_events[0].lat, lst_events[0].long),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
    }

    var addLocationsToMap = function(lst_events) {
        // Create markers.
        for (i = 0; i < lst_events.length; i++) {
            new google.maps.Marker({
                position: new google.maps.LatLng(lst_events[i].lat, lst_events[i].long),
                map: mapInstance,
                title: lst_events[i].CustomerName
            });
        }
    }

    return {
        initGoogleMap: initGoogleMap,
        addLocationsToMap: addLocationsToMap
    }
})()

var GPSModule = (function() {
    var mapElement = document.getElementById("GPSCONTENT");
    var mapInstance = null;

    var initGoogleMap = function() {

        mapInstance = new google.maps.Map(mapElement, {
            zoom: 5,
            center: new google.maps.LatLng(15.967674, 108.020437),
            //center: new google.maps.LatLng(gps_events.lat, gps_events.long),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
    }

    var addLocationsToMap = function(lst_events) {
        // Create markers.
        new google.maps.Marker({
            position: new google.maps.LatLng(gps_events.lat, gps_events.long),
            map: mapInstance,
            title: gps_events.CustomerName
        });

        // Create staff.
        new google.maps.Marker({
            position: new google.maps.LatLng(gps_events.slat, gps_events.slong),
            map: mapInstance,
            title: gps_events.StaffName,
            icon: {
                url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
            }
        });
    }

    return {
        initGoogleMap: initGoogleMap,
        addLocationsToMap: addLocationsToMap
    }
})()

var mapCheckin = null;
// var mapOptions = {
//     center: new google.maps.LatLng(gps_events.lat, gps_events.long),
//     zoom: centerDefault,
//     scrollwheel: false,
//     disableDoubleClickZoom: true,
//     mapTypeId: google.maps.MapTypeId.ROADMAP
// };
// mapCheckin = new google.maps.Map(document.getElementById("GPSCONTENT"),
//     mapOptions);


var GPSCheckinModule = (function() {
    var mapElement = document.getElementById("GPSCONTENT");


    var initGoogleMap = function() {

        mapCheckin = new google.maps.Map(mapElement, {
            // zoom: 5,
            zoom: 10,
            scrollwheel: false,
            // center: new google.maps.LatLng(15.967674, 108.020437),
            center: new google.maps.LatLng(gps_events.lat, gps_events.long),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
    }

    var addLocationsToMap = function(lst_events) {
        // Create markers.
        new google.maps.Marker({
            position: new google.maps.LatLng(gps_events.lat, gps_events.long),
            map: mapCheckin,
            title: gps_events.CustomerName
        });

        // Create staff.
        new google.maps.Marker({
            position: new google.maps.LatLng(gps_events.slat, gps_events.slong),
            map: mapCheckin,
            title: gps_events.StaffName,
            icon: {
                url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
            }
        });
    }

    return {
        initGoogleMap: initGoogleMap,
        addLocationsToMap: addLocationsToMap
    }
})()



$('#serverDataTable th').on('click', function() {
    setSort($(this))
})

function setSort(self) {
    setTimeout(function() {
        var index = $('th:contains(' + self.html() + ')').index()
        var dir = ''
        if (index != 0) {
            var name_col = self.html()
            $('#currIndexSort').val(index)
            dir = (self.attr('aria-sort') == 'ascending') ? "asc" : "desc"
            $('#currDirSort').val(dir)

            $.ajax({
                url: __baseUrl + 'admin/schedule/sessionSort',
                headers: { 'X-CSRF-Token': __csrfToken },
                type: 'post',
                data: { 'col': name_col, 'dir': dir },
            })
        }
    }, 1000)
}

// DECLARE AUTORELOAD
var autoReload

function beforeRender() {
    var col = $('#currIndexSort').val()
    var dir = $('#currDirSort').val()
    table.order([Number(col), dir]).draw()
    autoReload = setInterval(function() {
        showMapByStaff()
    }, 30000)
}
window.onload = beforeRender()
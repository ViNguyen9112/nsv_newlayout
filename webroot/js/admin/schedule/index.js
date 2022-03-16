// resizeable modal MAP
$('#gpsMapModal .modal-content').resizable({
    //alsoResize: ".modal-dialog",
    minHeight: 400,
    minWidth: 300
});

// resizeable modal Report (path:element/admin/popup_event_admin)
$('#eventModal .modal-content').resizable({
    //alsoResize: ".modal-dialog",
    minHeight: 750,
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

$('#btnMapSchedule').on('click', function(e) {
    e.preventDefault()
    var lst_events = schedule_lst_events
    MapModule.initGoogleMap(lst_events)
    MapModule.addLocationsToMap(lst_events)

    $("#eventMapModal").modal()
})

$("#close-map").click(function() {
    //do something
    $("#eventMapModal").modal('hide')
});

$("#btngpsAdjust").click(function() {
    console.log("btngpsAdjust")
});

$('#serverDataTable th').on('click', function() {
    setSort($(this))
})

var load_table = -1

var map;

var sStaffID = ''
var sCustomerID = ''
var sRegion = ''
var sAreaID = ''
var col
var dir

var lat = 0
var long = 0
var slat = 0
var slong = 0

var schedule_lst_events = null

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
            let time_from = $("#datepicker_time").val()
            let time_to = $("#datepicker_time_to").val()
            d.customerIds = sCustomerID
            d.staffIds = sStaffID
            d.date_from = date_from;
            d.date_to = date_to;
            d.time_from = time_from;
            d.time_to = time_to;
            d.region = sRegion;
            d.area = sAreaID;
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
            "sClass": "Staff-ID new-tag text-center",
            render: function(data, type, row) {
                var distance = (row.distance.toString().indexOf(".") > 0) ? commafy(Number(row.distance.toString().split(".")[0])) + "." + row.distance.toString().split(".")[1] : row.distance
                var txt = ""
                if (parseInt(row.isNewStaff) > 0) {
                    txt =  '<span class="New-Icon">New</span>' +  row.StaffID
                }
                else {
                    txt = row.StaffID
                }
                return "" +
                    txt +
                    '<span class="tooltip-distance">StaffID: ' + row.StaffID + ' - Total distances: ' + distance + ' km</span>'
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
        { "data": "checkin", "sClass": "text-center" },
        { "data": "checkout", "sClass": "text-center" },
        {
            "data": "CustomerName",
            render: function(data, type, row) {
                var txt = ""
                if (parseInt(row.isNewCustomer) === 1) {
                    txt =  '<span class="New-Icon">New</span>'
                }

                return "" +
                    txt +
                    "<a href=\"javascript:void(0)\" class=\"open-customer\" data-customer=\"" + row.CustomerID + "\">\n" +
                    "\t" + row.CustomerName + "\n" +
                    "</a>"
            }
        },
        {
            data: "ID",
            "sClass": "text-center",
            orderable: false,
            render: function(data, type, row) {
                lat = removeNull(row.lat)
                long = removeNull(row.long)
                slat = removeNull(row.slat)
                slong = removeNull(row.slong)
                var distance = Number(getDistance({ 'lat': lat, 'lng': long }, { 'lat': slat, 'lng': slong }).replaceAll(",", ""))
                var color = "#4e73df"
                if (distance >= 500) {
                    color = "#ea2d2d"
                }
                return "" +
                    "<a href=\"#\" data-toggle=\"modal\" \n" +
                    "\tdata-long=\"" + removeNull(row.long) + "\" \n" +
                    "\tdata-lat=\"" + removeNull(row.lat) + "\" \n" +
                    "\tdata-CustomerID=\"" + removeNull(row.CustomerID) + "\" \n" +
                    "\tdata-CustomerName=\"" + removeNull(row.CustomerName) + "\" \n" +
                    "\tdata-slong=\"" + removeNull(row.slong) + "\" \n" +
                    "\tdata-slat=\"" + removeNull(row.slat) + "\" \n" +
                    "\tdata-StaffName=\"" + removeNull(row.StaffName) + "\" \n" +
                    "\tclass=\"open-GPS\">\n" +
                    "\t<i class=\"fas fa-map-marker-alt\" style=\"color:" + color + "\"></i>\n" +
                    "</a>";
                // return getDistance({'lat': removeNull(row.lat), 'long':removeNull(row.long)}, {'lat':removeNull(row.slat),'long':removeNull(row.slong)})
            }
        },
        {
            data: 'ID',
            "sClass": "text-right",
            orderable: false,
            render: function(data, type, row) {
                if (getDistance({ 'lat': lat, 'lng': long }, { 'lat': slat, 'lng': slong }) != 'NaN') {
                    return getDistance({ 'lat': lat, 'lng': long }, { 'lat': slat, 'lng': slong }) + "m"
                } else {
                    return ""
                }

            }
        },
        {
            data: "ID",
            "sClass": "Report-ID text-center",
            orderable: false,
            render: function(data, type, row) {
                var txt = ''
                var tooll = ''
                // old
                /*if (parseInt(row.icon_type) > 0 && parseInt(row.icon_type) <= 7) {
                    txt = "\t<img src=\""+__baseUrl+"img/icon-report/icon-"+row.icon_type+".png?v="+__refeshCathe+"\" width=\"40\"/>\n"
                    tooll = '<span class="tooltip-report">' + row.type_name + '</span>'
                }else if (parseInt(row.icon_type) > 0){
                    txt = "\t<img src=\""+__baseUrl+"img/icon-report/icon-1.png\" width=\"40\"/>\n"
                    tooll = '<span class="tooltip-report">' + row.type_name + '</span>'
                }*/

                if (row.Report == null) return "";

                tooll = '<span class="tooltip-report">' + row.type_name + '</span>';
                if (row.TypeImage != null) {
                    txt =  "<img src=\""+__baseUrl + "img/admin/config-type/"+row.TypeImage+"?v="+__refeshCathe+"\" width=\"40\"/>\n";
                } else {
                    txt = "<img src=\""+__baseUrl+"img/icon-report/icon-default.png\" width=\"40\"/>\n";
                }

                return "" +
                    "<a href=\"#\" data-toggle=\"modal\" \n" +
                    "\tdata-timecardid=\"" + removeNull(row.TimecardID) + "\" \n" +
                    "\tdata-id=\"" + removeNull(row.id) + "\" \n" +
                    "\tdata-staffid=\"" + removeNull(row.StaffID) + "\" \n" +
                    "\tdata-staffname=\"" + removeNull(row.StaffName) + "\" \n" +
                    "\tdata-date=\"" + removeNull(row.date) + "\" \n" +
                    "\tdata-time=\"" + removeNull(row.time) + "\" \n" +
                    "\tdata-ftime=\"" + removeNull(row.ftime) + "\" \n" +
                    "\tdata-customerid=\"" + removeNull(row.CustomerID) + "\" \n" +
                    "\tdata-customername=\"" + removeNull(row.CustomerName) + "\" \n" +
                    "\tdata-imgcheckin=\"" + removeNull(row.imgcheckin) + "\" \n" +
                    "\tdata-imgcheckout=\"" + removeNull(row.imgcheckout) + "\" \n" +
                    "\tclass=\"open-AddBookDialog\">\n" +
                    txt +
                    "</a>" +
                    tooll;
            }
        },
        {
            data: "ID",
            "sClass": "text-center text-middle",
            orderable: false,
            render: function(data, type, row) {
                return "" +
                    "<a href=\"#\" data-toggle=\"modal\" \n" +
                    "\tdata-checkin=\"" + removeNull(row.imgcheckin) + "\" \n" +
                    "\tdata-checkout=\"" + removeNull(row.imgcheckout) + "\" \n" +
                    "\tclass=\"open-Face\">\n" +
                    "\t<i class=\"far fa-smile-beam\"style=\"font-size: 24px;\"></i>\n" +
                    "</a>";
            }
        },
        {
            "data": "Region",
            "sClass": "text-center",
            render: function(data, type, row) {
                var result = Object.keys(areas).map(key => ({ key, value: areas[key] }));
                for(var z = 0; z < result.length; z++) {
                    var e = result[z]
                    if (e.key == row.TBLMArea.Region) {
                        return e.value;
                    }
                }
                return "";
            }
        },
        { "data": "TBLMArea.Name" },
    ],
    "drawCallback": function(settings) {
        if (load_table % 10 == 0) { //10
            var api = this.api();
            schedule_lst_events = api.ajax.json().data;
        }
        load_table++

        // calcualte distance of staffs
        // var staffs = api.ajax.json().sortedStaffs;
        // calDistanceStaff(staffs)

    }
});

let gps_events = []
var centerDefault = 10
var mapCheckin = null;

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
    $(".sCustomerID").select2({
        allowClear: true,
        placeholder: 'Please choose customer',
        tags: true
    });
    $(".sCustomerID").on("select2:select", function(e) {
        sCustomerID = e.params.data.id;
        $('#filterSchedule').click()
    });
    $(".sCustomerID").on("select2:clear", function(e) {
        sCustomerID = ''
        $('#filterSchedule').click()
    });

    // date range picker
    setRangeDatepicker.rangeDay('#datepicker_date', '#datepicker_date_to')

    // date range picker
    // setRangeTimepicker.rangeTime('#datepicker_time', '#datepicker_time_to')

    // Region
    $(document).on('change', '#Region', function(e) {
        sRegion = $(this).val()

        getArea(sRegion)


        $('#filterSchedule').click()
    })

    // Time
    $(document).on('change', '#datepicker_time', function(e) {
        $('#filterSchedule').click()
    })

    // Time
    $(document).on('change', '#datepicker_time_to', function(e) {
        $('#filterSchedule').click()
    })

    // Region
    $(document).on('change', '#AreaID', function(e) {
        sAreaID = $(this).val()
        $('#filterSchedule').click()
    })

    // clear filter
    $(document).on('click', '#clearFilter', function(e) {
        e.preventDefault()
            // clear
        $(".sStaffID").val(null).trigger('change');
        sStaffID = ''
        $(".sCustomerID").val(null).trigger('change');
        sCustomerID = ''
        $('#datepicker_date').val(moment().format('YYYY/MM/DD'))
        $('#datepicker_date_to').val(moment().format('YYYY/MM/DD'))
        setRangeDatepicker.rangeDay('#datepicker_date', '#datepicker_date_to')
        $('#datepicker_time').val("00:00")
        $('#datepicker_time_to').val("23:59")
        // setRangeTimepicker.rangeTime('#datepicker_time', '#datepicker_time_to')
        $("#Region").val(null);
        sRegion = ''
        $("#AreaID").val(null);
        sAreaID = ''
            // reset table
        $('#filterSchedule').click()
    })

    // apply filter
    $(document).on('click', '#filterSchedule', function() {
        load_table = 0
        showMapByStaff()
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
                    $('#modalInfoStaff #InfoArea').html(data.AreaName)
                    $('#modalInfoStaff #InfoTitle').html(data.Title)
                    $('#modalInfoStaff #InfoRegion').html(data.RegionName)
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
        $(".StaffID-Report").html($(this).attr('data-StaffID'));
        $("#StaffName").html($(this).attr('data-StaffName'));
        $("#date").html($(this).attr('data-date'));
        // $("#time").html(  $(this).attr('data-ftime') );
        $(".CustomerID-Report").html($(this).attr('data-CustomerID'));
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
            img_in = '<img src="' + __baseUrl + img_in + '" width="150px" class="img-zoom">'
        }
        if (img_out) {
            img_out = '<img src="' + __baseUrl + img_out + '" width="150px" class="img-zoom">'
        }
        $("#checkIn").html(img_in);
        $("#checkOut").html(img_out);

        $('#faceModeClose').hide()
        $('#faceModeBack').show()
        $('#eventModal').modal('hide')

        $('#faceModal').modal()
    });

    $(document).on('click', '.img-zoom', function() {
        $('#venoboxImage').attr("src", $(this).attr("src"))
        $('#viewImageModal').modal()
    })

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

        if (img_in && $.UrlExists(__baseUrl + img_in)) {
            img_in = '<img src="' + __baseUrl + img_in + '" width="150px" class="img-zoom">'
        }
        else if(img_in) {
            img_in = '<img src="' + __baseUrl + 'img/deleted.jpg' + '" width="150px" class="img-zoom">'
        }
        if (img_out && $.UrlExists(__baseUrl + img_out)) {
            img_out = '<img src="' + __baseUrl + img_out + '" width="150px" class="img-zoom">'
        }
        else if (img_out){
            img_out = '<img src="' + __baseUrl + 'img/deleted.jpg' + '" width="150px" class="img-zoom">'
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

        let adjust_btn = $('.customer-adjust-long-lat')
        adjust_btn.attr("adjust-customer-long", gps_events.slong)
        adjust_btn.attr("adjust-customer-lat", gps_events.slat)
        adjust_btn.attr("adjust-customer-id", $(this).attr("data-CustomerID"))
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


    $('.customer-adjust-long-lat').click(function() {
        swal({
            text: "Are you sure you want to adjust",
            icon: false,
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': __csrfToken },
                        url: __baseUrl + 'admin/customer/adjust',
                        type: 'POST',
                        data: {
                            'Longitude': $(this).attr("adjust-customer-long"),
                            'Latitude': $(this).attr("adjust-customer-lat"),
                            'ID': $(this).attr("adjust-customer-id"),
                        },
                        success: function(response) {
                            load_table = 0
                            showMapByStaff()
                            $("#gpsMapModal").modal('hide')
                        }
                    })
                }
            })
    });

})

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

function getArea(region) {
    $.ajax({
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/schedule/getArea',
        type: 'POST',
        data: {
            'region': region
        },
        success: function(response) {
            var list = '<option value=""></option>'
            $.each(response, function(index, value){
                list +=  '<option value="'+ value.AreaID +'">【'+ value.AreaID + "】" + value.Name +'</option>'
            })
            $('#AreaID').html(list)
        }
    })
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

var setRangeDatepicker = (function() {
    var rangeDay = function(dateFrom, dateTo, startDate = 0) {

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
                $('#filterSchedule').click()
            })
            .on('click', function() {
                jQuery(dateFrom).datepicker('update', jQuery(dateFrom).val())
            })
            .on('changeDate', function() {

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
                $('#filterSchedule').click()
            })
            .on('click', function() {
                jQuery(dateTo).datepicker('update', jQuery(dateTo).val())
            })
            .on('changeDate', function() {

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

var setRangeTimepicker = (function() {
    var rangeTime = function(timeFrom, timeTo) {
        jQuery(timeFrom).timepicker({
            timeFormat: 'HH:mm',
            interval: 1,
            minHour: 0,
            maxHour: 24,
            dynamic: true,
            dropdown: true,
            scrollbar: true,
            change: function(time) {
                $('#filterSchedule').click()

                // the input field
                var timepicker = $(timeFrom).timepicker();
                var text = timepicker.format(time);

                $(timeTo).timepicker("destroy");
                $(timeTo).timepicker({
                    timeFormat: 'HH:mm',
                    interval: 5,
                    minTime: text,
                    maxHour: 21,
                    // dynamic: true,
                    dropdown: true,
                    scrollbar: true,
                    change: function(time) {
                        $('#filterSchedule').click()
                    }
                })
            }
        })
        jQuery(timeTo).timepicker({
            timeFormat: 'HH:mm',
            interval: 1,
            minHour: 0,
            maxHour: 24,
            // dynamic: true,
            dropdown: true,
            scrollbar: true,
            change: function(time) {
                $('#filterSchedule').click()
            }
        })
    }

    return {
        rangeTime: rangeTime
    }
})();

$.UrlExists = function(url) {
    var http = new XMLHttpRequest();
    http.open('HEAD', url + "?v=" + __refeshCathe, false);
    http.send();
    return http.status!=404;
}

var rad = function(x) {
    return x * Math.PI / 180;
};

// DECLARE AUTORELOAD
var autoReload

function beforeRender() {
    var col = $('#currIndexSort').val()
    var dir = $('#currDirSort').val()
    table.order([Number(col), dir]).draw()
    autoReload = setInterval(function() {
        showMapByStaff()
    }, 3600000)
}

window.onload = beforeRender()

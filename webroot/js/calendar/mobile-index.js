var currentTypeCode = '';
//Get the button
var topBtn = document.getElementById("topBtn");
var downBtn = document.getElementById("downBtn");
// When the user scrolls down 20px from the top of the document, show the button
$(".sec-report").scroll(function () {
    scrollFunction();
});

function scrollFunction() {
    if ($(".sec-report").scrollTop() > 20) {
        topBtn.style.display = "block";
        downBtn.style.display = "none";
    } else {
        downBtn.style.display = "block";
        topBtn.style.display = "none";
    }
}

$(document).on("click", "#topBtn", function (e) {
    e.preventDefault();
    topFunction();
});
$(document).on("click", "#downBtn", function (e) {
    e.preventDefault();
    bottomFunction();
});
// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    $(".sec-report").animate(
        {
            scrollTop: 0,
        },
        500
    );
    downBtn.style.display = "block";
    topBtn.style.display = "none";
}

function bottomFunction() {
    $(".sec-report").animate(
        {
            scrollTop: $(".sec-report").prop("scrollHeight"),
        },
        500
    );
    topBtn.style.display = "block";
    downBtn.style.display = "none";
}
// END BUTTON

let lst_events = [];
let map_events = [];
jQuery(document).ready(function () {
    $("#datepicker").monthpicker({
        dateFormat: "yy-mm",
    });

    initCalendar();

    showCalendarStaff();
});

function initCalendar() {
    $("#calendar").fullCalendar("destroy");
    let datepicker = $("#default_datepicker").val();
    let my = datepicker.split("-");
    $("#calendar").fullCalendar({
        customButtons: {
            backButton: {
                text: "Back",
                click: function () {
                    var view = $("#calendar").fullCalendar("getView");
                    var view_name = view.name;
                    if (view_name == "month") {
                        window.location = __baseUrl;
                    } else {
                        $("#calendar").fullCalendar("changeView", "month");
                    }
                },
            },
        },
        header: {
            left: "prev,next today",
            center: "title",
            right: "backButton agendaDay",
        },
        views: {
            month: {
                // name of view
                titleFormat: "MMM YYYY",
                // other view-specific options here
            },
            agendaDay: {
                // name of view
                titleFormat: "MMM D YYYY",
                // other view-specific options here
            },
        },
        viewRender: function (view, element) {
            //Do something
            var moment = $("#calendar").fullCalendar("getDate");
            $("#default_datepicker").val(moment.format("YYYY-MM"));
        },
        //hiddenDays: [ 0, 6 ], hidden Sun and Sat
        eventClick: function (calEvent, jsEvent, view) {
            $("#StaffID").html(calEvent.StaffID);
            $("#StaffName").html(calEvent.StaffName);
            $("#Starttime").html(calEvent.starttime);
            $("#Endtime").html(calEvent.endtime);
            $("#IdCustomer").html(calEvent.CustomerID);
            $("#CustomerName").html(calEvent.CustomerName);
            $("#Picture").attr("data-checkIn", calEvent.imgcheckin);
            $("#Picture").attr("data-checkOut", calEvent.imgcheckout);

            $(".textarea-report").val(calEvent.Report);

            $("#report_id").val(calEvent.Report_ID);
            $("#timecard_id").val(calEvent.id);

            console.log(calEvent);

            //if (currentTypeCode != '') {
            //  calEvent.TypeCode = currentTypeCode;
            //}

            // TODO: append select Type
            appendTypeReport(calEvent.TypeCode);

            // TODO: resetFieldCheckbox
            //resetFieldReport(calEvent.id, calEvent.Report_ID, calEvent.TypeCode);
            resetFieldReport(calEvent.id, calEvent.Report_ID, '');

            // append images
            appendImages(calEvent.Report_ID);

            // $("#report_event").val(calEvent.Report)

            // var rows = calEvent.Report.split("\n").length;
            // if (rows > 10) rows = 10

            // $('#report_event').attr('rows', rows)
            // $('#report_event').attr('readonly', true)

            // TODO: DISABLED FORM

            $("#event-save").hide();
            $("#event-edit").show();

            // $('#report_event').scrollTop(0)
            setTimeout(function () {
                $("#eventModal").modal();
            }, 500);

            map_events[0] = calEvent;
            disbledReport();
        },
        // eventLimitClick: function(cellInfo, jsEvent, view) {
        //     console.log("eventLimitClick")
        // },
        //height: 700,
        //aspectRatio: 1,
        defaultDate: my[0] + "-" + my[1] + "-01",
        selectable: true,
        selectHelper: false,
        navLinks: true,
        editable: false,
        timeFormat: "H(:mm)",
        eventLimit: 4, // allow "more" link when too many events
        events: lst_events,
    });
}

function showCalendarStaff() {
    let datepicker = $("#default_datepicker").val();
    $.ajax({
        type: "POST",
        url: __baseUrl + "ajax/getAllHoliday",
        data: {
            staffIds: $("#multiple-select").val(),
            roll: $("#roll").val(),
            month: datepicker,
        },
        headers: {
            "X-CSRF-TOKEN": __csrfToken,
        },
        success: function (data) {
            lst_events = $.parseJSON(data);
            $("#calendar").fullCalendar("removeEvents");
            $("#calendar").fullCalendar("addEventSource", lst_events);
            $("#calendar").fullCalendar("rerenderEvents");
        },
    });
}

function appendTypeReport(type) {
    $.ajax({
        headers: { "X-CSRF-TOKEN": __csrfToken },
        url: __baseUrl + "mypage/getType",
        type: "GET",
        success: function (res) {
            var data = res.data;
            var types = "<option value=''>-- Select --</option>";
            $.each(data, function (index, value) {
                var selected = "";
                if (type == value.TypeCode) {
                    selected = "selected";
                    $("#textType").html(value.TypeEN);
                }
                types +=
                    '<option value="' +
                    value.TypeCode +
                    '" ' +
                    selected +
                    ">" +
                    value.TypeEN +
                    "</option>";
            });
            $("#TypeReport").html(types);
            $("#TypeCode").val(type);

            $("#topBtn").click();
        },
    });
}

function resetFieldReport(id_timecard, id_report = null, id_type) {
    // reset error
    $('.error-note').text('');
    $('#NoteReport').css({'border' : '2px solid #ccc'});
    $('#ContentReport').css({'border' : '2px solid #ccc'});

    var data = {};
    if (id_report != null) {
        data = {
            id_timecard: id_timecard,
            report_id: id_report,
            TypeCode: id_type,
        };
    } else {
        data = { TypeCode: id_type };
    }
    $.ajax({
        headers: { "X-CSRF-TOKEN": __csrfToken },
        url: __baseUrl + "mypage/getFormReports",
        type: "POST",
        data: data,
        success: function (response) {
            if (response.typeCheck == 1) {
                $("#ContentReport").val("");
                $("#NoteReport").val("");

                var data = response.data;

                var rows = "";
                const tplCheckbox = $("#tplCheckbox").html();
                const tplRadio = $("#tplRadio").html();
                const tplSecCheck = $("#tplSecCheck").html();

                var secCheck = "";
                var checkboxs = "";
                var category = "";
                var haveCategory = 0;

                $.each(data, function (index, child) {
                    secCheck = "";
                    checkboxs = "";
                    category = index;
                    haveCategory =
                        index != "" ? haveCategory + 1 : haveCategory;
                    secCheck = tplSecCheck.replace(/__category__/g, category);
                    $.each(child, function (index, val) {
                        //console.log(val);
                        if (val.TypeCat == 1 || val.TypeCat == null) {
                            checkboxs += tplCheckbox
                                .replace(/__id__/g, val.CheckID)
                                .replace(/__detail__/g, val.CheckPoint);
                        } else if (val.TypeCat == 3) {
                            checkboxs += tplRadio
                                .replace(/__id__/g, val.CheckID)
                                .replace(/__detail__/g, val.CheckPoint)
                                .replace(/__idCat__/g, val.idCat);
                        }
                    });
                    secCheck = secCheck.replace(/__checkboxs__/g, checkboxs);

                    rows += secCheck;
                });

                $("#checkreport").html(rows);

                //set values
                if (response.submited != null) {
                    var submited = response.submited;
                    $.each(submited.Check, function (index, value) {
                        $(".checkbox-report").each(function () {
                            var this_value = $(this).val();
                            if (
                                this_value == value.CheckID &&
                                value.Result == 1
                            ) {
                                $(this).prop("checked", true);
                            }
                        });
                    });
                    $("#NoteReport").val(submited.Content);
                }

                // handle html
                if (haveCategory == 0) {
                    $(".legend-category").css("display", "none");
                }
                $(".report-check").css("display", "block");
                $(".not-check").css("display", "none");
            } else {
                if (response.submited != null) {
                    var submited = response.submited;
                    $("#ContentReport").val(submited.Content);
                } else {
                    $("#ContentReport").val("");
                }
                $(".report-check").css("display", "none");
                $(".not-check").css("display", "block");
            }
            if ($("#flagEdit").val() == "dis") {
                disbledReport();
            } else {
                enabledReport();
            }

            $("#topBtn").click();
        },
    });
}

function appendImages(report_id) {
    $.ajax({
        headers: { "X-CSRF-TOKEN": __csrfToken },
        url: __baseUrl + "mypage/getImageReport",
        data: { ReportID: report_id },
        type: "POST",
        success: function (res) {
            if (res.success == 1) {
                if (res.images) {
                    $(".files-preview").html("");
                    var i = 0;
                    $.each(res.images, function (index, value) {
                        var tplImageUploaded = $("#tplImageUploaded").html();
                        tplImageUploaded = tplImageUploaded
                            .replace(/__id__/g, i)
                            .replace(/__id-uploaded__/g, value.ID)
                            .replace(
                                /__src__/g,
                                "ImageReport/ID_" +
                                    report_id +
                                    "/" +
                                    value.ImageName
                            );
                        $(".files-preview").append(tplImageUploaded);

                        i++;
                    });
                    $("#currentIndexFiles").val(i);

                    $(".delete-image, .fileinput-button").hide();
                }
                $("#previewImages").show();
            }
            $("#topBtn").click();
        },
    });
}

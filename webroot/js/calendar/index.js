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
            filterButton: {
                text: "Please choose Staff",
                click: function () {
                    $("#calendarModal").modal();
                },
            },
            patrolButton: {
                text: "Patrol",
            },
            meetingButton: {
                text: "Meeting (Customer)",
            },
            deskButton: {
                text: "Desk Work",
            },
            inhouseButton: {
                text: "In-house Meeting",
            },
            recruitmentButton: {
                text: "Recruitment Activities",
            },
            securiryButton: {
                text: "Security Guard/ Post Disposition",
            },
            otherButton: {
                text: "Others",
            },
        },
        header: {
            left: "prev,next",
            center: "title",
            right: "patrolButton,meetingButton,deskButton,inhouseButton,recruitmentButton,securiryButton,otherButton filterButton",
        },
        viewRender: function (view, element) {
            //Do something
            var moment = $("#calendar").fullCalendar("getDate");
            $("#default_datepicker").val(moment.format("YYYY-MM"));
        },
        eventClick: function (calEvent, jsEvent, view) {
            $("#StaffID").html(calEvent.StaffID);
            $("#StaffName").html(calEvent.StaffName);
            $("#Starttime").html(calEvent.starttime);
            $("#Endtime").html(calEvent.endtime);
            $("#CustomerID").html(calEvent.CustomerID);
            $("#CustomerName").html(calEvent.CustomerName);
            $("#report_event").val(calEvent.Report);
            $("#div_event").html(calEvent.Report);
            $("#report_id").val(calEvent.Report_ID);

            $("#Picture").attr("data-checkIn", calEvent.imgcheckin);
            $("#Picture").attr("data-checkOut", calEvent.imgcheckout);

            var rows = calEvent.Report.split("\n").length;
            if (rows > 10) rows = 10;

            $("#report_event").attr("rows", rows);
            $("#report_event").attr("readonly", true);

            map_events[0] = calEvent;
            console.log(map_events[0]);
            $("#eventModal").modal();
        },
        //height: 700,
        //aspectRatio: 1,
        defaultDate: my[0] + "-" + my[1] + "-01",
        selectable: false,
        selectHelper: false,
        editable: false,
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

jQuery(document).ready(function () {
    // sự kiện ẩn modal
    $('#eventModal').on('hidden.bs.modal', function () {

    });

    $("body").on("click", "button.fc-prev-button", function () {
        //do something
        showCalendarStaff();
    });

    $("body").on("click", "button.fc-next-button", function () {
        //do something
        showCalendarStaff();
    });

    $(".fc-today-button").click(function () {
        //do something
        showCalendarStaff();
    });

    $("#close-map").click(function () {
        //do something
        $("#eventModal").modal("show");
        $("#eventMapModal").modal("hide");
    });

    $("body").on("click", "#MAP", function (e) {
        e.preventDefault();
        //do something
        MapModule.initGoogleMap(map_events);
        MapModule.addLocationsToMap(map_events);
        $("#eventModal").modal("hide");
        $("#eventMapModal").modal();
    });

    $("body").on("click", "#event-edit", function (e) {
        e.preventDefault();
        enabledReport();
        $("#event-save").show();
        $("#event-edit").hide();

        $(".delete-image, .fileinput-button").show();
    });

    $('#ContentReport').keyup(function(e){
        $('#ContentReport').css({'border' : '2px solid #ccc'});
        $('.error-note').text('');
    });

    $('#NoteReport').keyup(function(e){
        $('#NoteReport').css({'border' : '2px solid #ccc'});
        $('.error-note').text('');
    });

    $("body").on("click", "#event-save", function (e) {
        e.preventDefault();
        $(this).attr('disabled', true);

        if($('.not-check').css('display') == 'block') {
            let content = $('#ContentReport').val().trim();
            let lines = content.split(/\r|\r\n|\n/);
            let count = lines.length;

            if ($('#TypeReport').val() == '') {
                let msg0 = $('#msg_error_user_report_0').val();
                $('.error-type').text(msg0);
                $(this).attr('disabled', false);
            } else if (content == "") {
                // TODO:^language
                //alert($('#text_required_report').val());
                $('.error-note').text($('#text_required_report').val());
                $('#ContentReport').css({'border' : '2px solid red'});
                $(this).attr('disabled', false);
            } else {
                if (count < 6) {
                    $('#ContentReport').css({'border' : '2px solid red'});
                    var messError = $('#text_required_note_count_line').val();
                    $('.error-note').text(messError);
                    $(this).attr('disabled', false);
                } else {
                    let result = validateContent(content);
                    if (result.status == false) {
                        $('#ContentReport').css({'border' : '2px solid red'});
                        $('.error-note').text(result.msg);
                        $(this).attr('disabled', false);
                    } else {
                        // reset message
                        $('#ContentReport').css({'border' : '2px solid #ccc'});
                        $('.error-note').text('');
                        $("#event-save").hide();
                        $("#event-loading").show();
                        insertReport();
                    }
                }
            }
        } else {
            let content = $('#NoteReport').val().trim();
            let lines = content.split(/\r|\r\n|\n/);
            let count = lines.length;

            if ($('#TypeReport').val() == '') {
                let msg0 = $('#msg_error_user_report_0').val();
                $('.error-type').text(msg0);
                $(this).attr('disabled', false);
            } else if (content == "") {
                // TODO:^language
                //alert($('#text_required_report').val());
                $('.error-note').text($('#text_required_report').val());
                $('#NoteReport').css({'border' : '2px solid red'});
                $(this).attr('disabled', false);
            } else {
                if (count < 6) {
                    $('#NoteReport').css({'border' : '2px solid red'});
                    var messError = $('#text_required_note_count_line').val();
                    $('.error-note').text(messError);
                    $(this).attr('disabled', false);
                } else {
                    let result = validateContent(content);
                    if (result.status == false) {
                        $('#NoteReport').css({'border' : '2px solid red'});
                        $('.error-note').text(result.msg);
                        $(this).attr('disabled', false);
                    } else {
                        // reset message
                        $('#NoteReport').css({'border' : '2px solid #ccc'});
                        $('.error-note').text('');
                        $("#event-save").hide();
                        $("#event-loading").show();
                        insertReport();
                    }
                }
            }
        }
    });

    $(document).on("click", "#Picture", function (e) {
        e.preventDefault();
        let img_in = $(this).attr("data-checkIn");
        let img_out = $(this).attr("data-checkOut");
        if (img_in) {
            img_in = '<img src="' + __baseUrl + img_in + '" width="150px">';
        }
        if (img_out) {
            img_out = '<img src="' + __baseUrl + img_out + '" width="150px">';
        }
        $("#checkIn").html(img_in);
        $("#checkOut").html(img_out);

        $("#faceModeClose").hide();
        $("#faceModeBack").show();
        $("#eventModal").modal("hide");

        $("#faceModal").modal();
    });

    $(document).on("click", "#faceModeBack", function () {
        $("#faceModal").modal("hide");
        $("#eventModal").modal();
    });

    $(document).on("click", "#close-report", function () {
        $("#eventModal").modal("hide");
    });

    $("#TypeReport").on("change", function () {
        if ($("#TypeCode").val() == $(this).val()) {
            //$("#flagEdit").val("ena");
            resetFieldReport(
                $("#timecard_id").val(),
                $("#report_id").val(),
                $(this).val()
            );
        } else {
            //$("#flagEdit").val("ena");
            resetFieldReport(null, null, $(this).val());
        }
    });

    $(document).on('click', '.checkbox-report', function() {
        var $radio = $(this);
        var name_radio = $radio.attr('name');

        // if this was previously checked
        if ($radio.data('waschecked') == true)
        {
            $radio.prop('checked', false);
            $radio.data('waschecked', false);
        }
        else
            $radio.data('waschecked', true);

        // remove was checked from other radios
        $radio.siblings('input[name="'+name_radio+'"]').data('waschecked', false);

        postCheckbox($("#timecard_id").val(), $('#TypeReport').val(), $(this).val(), $(this).prop('checked'));
    });
});

var MapModule = (function () {
    var mapElement = document.getElementById("MAPCONTENT");
    var mapInstance = null;

    var initGoogleMap = function () {
        mapInstance = new google.maps.Map(mapElement, {
            zoom: 13,
            //center: new google.maps.LatLng(15.967674, 108.020437),
            center: new google.maps.LatLng(
                map_events[0].lat,
                map_events[0].long
            ),
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        });
    };

    var addLocationsToMap = function (map_events) {
        console.log(map_events);
        // Create markers.
        for (i = 0; i < map_events.length; i++) {
            new google.maps.Marker({
                position: new google.maps.LatLng(
                    map_events[i].lat,
                    map_events[i].long
                ),
                map: mapInstance,
                title: map_events[i].CustomerName,
            });
        }
    };

    return {
        initGoogleMap: initGoogleMap,
        addLocationsToMap: addLocationsToMap,
    };
})();

function disbledReport() {
    $("#textType").show();
    $("#TypeReport").hide();
    $("#ContentReport").attr("readonly", true);
    $("#NoteReport").attr("readonly", true);
    $(".checkbox-report").prop("disabled", true);
    $("#flagEdit").val("dis");
}

function enabledReport() {
    $("#textType").hide();
    $("#TypeReport").show();
    $("#ContentReport").attr("readonly", false);
    $("#NoteReport").attr("readonly", false);
    $(".checkbox-report").prop("disabled", false);
    $("#flagEdit").val("ena");
}

function insertReport() {
    const form = $(".ajax-form-report");
    ajax_form(form);
}

function ajax_form(form) {
    const form_data = new FormData(form.get(0));

    const button = form.find("button[id=event-save]");
    button.attr("disabled", true);

    // case update
    $arr_uploaded = [];
    $(".image-uploaded").each(function () {
        $arr_uploaded.push($(this).attr("data-id-uploaded"));
    });
    form_data.append("imagesUploaded", $arr_uploaded);

    if (files.length < 1) {
        form_data.append("files", "null");
    } else {
        files.forEach((file) => {
            /* here just put file as file[] so now in submitting it will send all files */
            form_data.append("files[]", file);
        });
    }

    var haveCheck = $(".report-check").css("display") == "none" ? 0 : 1;

    var arrChecked = [];
    $('input[name="Check"]:checked').each(function () {
        arrChecked.push($(this).val());
    });

    $('input[class="checkbox-report"]:checked').each(function () {
        arrChecked.push($(this).val());
    });

    // loại bỏ trùng lặp
    arrChecked = Array.from(new Set(arrChecked));

    let typeReport = $("#TypeReport").val();
    currentTypeCode = typeReport;

    form_data.append("typeSubmit", "update");
    form_data.append("typeReport", typeReport);
    form_data.append("customerID", $('#customerID').val());
    form_data.append("ID", $("#report_id").val());
    form_data.append("TimeCardID", $("#timecard_id").val());
    form_data.append("content", $("#ContentReport").val());
    form_data.append("haveCheck", haveCheck);
    form_data.append("valuesChecked", arrChecked);
    form_data.append("note", $("#NoteReport").val());

    $.ajax({
        headers: { "X-CSRF-TOKEN": __csrfToken },
        url: __baseUrl + "mypage/insertReport",
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        dataType: "json",
        success: function (res) {
            if (res.success == 1) {
                $('#TypeCode').val(typeReport);

                showCalendarStaff();

                files = [];
                swal({
                    // TODO:^language
                    title: $("#text_report").val(),
                    text: $("#text_submit_successfully").val(),
                    icon: "success",
                });
                setTimeout(function () {
                    swal.close();
                }, 3000);
            }
        },
        error: function (res) {
            swal({
                // TODO:^language
                title: $("#text_report").val(),
                text: $("#text_submit_failed").val(),
                icon: "error",
            });
        },
    }).done(function (res) {
        $("#event-save").attr("disabled", false);
        $("#event-loading").hide();
        $("#event-edit").show();
    });
}

function postCheckbox(timecard, type, id, checked){
    $.ajax({
        headers: { "X-CSRF-TOKEN": __csrfToken },
        url: __baseUrl + 'mypage/queryCheck',
        type: 'POST',
        data: {'timecard': timecard, 'type': type, 'id': id, 'checked': checked},
        success:function(res){
            if(res.success == 1){
                console.log('ok')
            }
        }
    })
}

// Validate nội dung content
function validateContent(content) {
    let msg1 = $('#msg_error_user_report_1').val();
    // xử lý nếu bạn nhập quá nhiều tin nhắn giống nhau
    let arrKeywords = [];
    let arrContents = content.split(/\r|\r\n|\n/);
    $.each(arrContents, function( index, value ) {
        let itemContent = value.split(/,| /);
        $.each(itemContent, function( ind, val ) {
            if (val.trim() != '') {
                arrKeywords.push(val);
            }
        });
    });

    let _length = arrKeywords.length;
    if (_length <= 10 ) {
        return {
            'msg': msg1,
            'status':false
        };
    } else {
        // xử lý nếu nhập quá nhiều ksy tự giống nhau
        let arrUnique = arrKeywords.filter(function(item, i, arrKeywords) {
            return i == arrKeywords.indexOf(item);
        });

        if (arrUnique.length <= 6) {
            return {
                'msg':msg1,
                'status':false
            };
        }
    }

    return {
        'msg':'success',
        'status':true
    };
}

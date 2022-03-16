jQuery(document).ready(function() {

    $('table.response').hide()

    // set date for face
    setDatepicker.pickerDate('#datepicker_backup_from')

    //set date for report
    setDatepicker.pickerDate('#datepicker_backup_to')

    var f = $('#tblBackup')

    f.on('click', '.btn-check-backup', function () {
        $('table.response').hide()
        $.ajax({
            headers: {'X-CSRF-TOKEN': __csrfToken},
            url: __baseUrl + 'admin/backup/check_number',
            type: 'POST',
            data: {
                'datepicker_backup_from': $('#datepicker_backup_from').val(),
                'datepicker_backup_to': $('#datepicker_backup_to').val()
            },
            success: function (response) {
                $(".chk-face").text(response.total_face)
                $(".chk-report").text(response.total_report)
                $(".total_image").text(response.total_image)
                $('.response-check').show()
            }
        })
    })

    f.on('click', '.btn-zip-backup', function () {
        var total = $(".total_image").text()

        if (total <= 0) {
            swal("There is no image in this period!", {
                buttons: {
                    catch: {
                        text: "OK",
                        value: true,
                    },
                },
                className: "swal-full-width",
            }).then((value) => {
                // resolve(value);
            });
        }
        else {
            $.ajax({
                headers: {'X-CSRF-TOKEN': __csrfToken},
                url: __baseUrl + 'admin/backup/zip_file',
                type: 'POST',
                data: {
                    'datepicker_backup_from': $('#datepicker_backup_from').val(),
                    'datepicker_backup_to': $('#datepicker_backup_to').val()
                },
                success: function (response) {
                    if (parseInt(response.zip_status) === 1) {
                        $('.zip-file').text(response.zip_file)
                        $('.response-zip').show()
                    }
                }
            })
        }
    })

    f.on('click', '.zip-file', function () {
        $(location).attr('href', __baseUrl + "ZipImage/" + $(this).text());
        $('.response-delete').show()
    })

    f.on('click', '.btn-delete-zip', function () {
        swal({
            text: "Are you sure you want to delete all images?",
            icon: false,
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        headers: {'X-CSRF-TOKEN': __csrfToken},
                        url: __baseUrl + 'admin/backup/delete',
                        type: 'POST',
                        data: {
                            'datepicker_backup_from': $('#datepicker_backup_from').val(),
                            'datepicker_backup_to': $('#datepicker_backup_to').val()
                        },
                        success: function (response) {
                            $('.free-space').text(response.free_space)
                            $('.response-complete').show()

                            $('.response-check').hide()
                            $('.response-zip').hide()
                            $('.response-delete').hide()
                        }
                    })
                }
            })
    })

    //showing password confirm
    if (parseInt(CST_SHOW_PASSWORD_DIALOG) === 1) {
        outputPasswordConfirm()
    }
})

var setDatepicker = (function() {
    var pickerDate = function(date, startDate = 0) {
        jQuery(date).datepicker({
            format: "yyyy/mm/dd",
            startDate: startDate,
            autoclose: true,
            todayHighlight: true,

        })
            .on("change", function() {

            })
            .on('click', function() {
                //jQuery(date).datepicker('update', jQuery(data).val())
            });
    }

    return {
        pickerDate: pickerDate,
    }
})();

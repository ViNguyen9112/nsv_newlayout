jQuery(document).ready(function() {
    // set date for face
    setDatepicker.pickerDate('#datepicker_face_to')

    //set date for report
    setDatepicker.pickerDate('#datepicker_report_to')

    var f = $('#tblDelete')

    f.on('change', '#datepicker_face_to', function () {
        $.ajax({
            headers: { 'X-CSRF-TOKEN': __csrfToken },
            url: __baseUrl + 'admin/delete/check_number',
            type: 'POST',
            data: {
                'target': 1,
                'datepicker_face_to': $(this).val()
            },
            success: function(response) {
                $(".btn-delete-face").attr("total-image", response.total)
            }
        })
    })

    f.on('click', '.btn-delete-face', function() {
        var date = $('#datepicker_face_to').val()
        var total = $(".btn-delete-face").attr("total-image")

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
            swal({
                text: "Are you sure you want to delete all images to "+date+"?\n(Image Number: "+total+" images)",
                icon: false,
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            headers: { 'X-CSRF-TOKEN': __csrfToken },
                            url: __baseUrl + 'admin/delete',
                            type: 'POST',
                            data: {
                                'target': 1,
                                'datepicker_face_to': $('#datepicker_face_to').val()
                            },
                            success: function(response) {
                                $(".btn-delete-face").attr("total-image", 0)
                                swal(response.total + " images were deleted successfully!", {
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
                        })
                    }
                })
        }
    })

    f.on('change', '#datepicker_report_to', function () {
        $.ajax({
            headers: { 'X-CSRF-TOKEN': __csrfToken },
            url: __baseUrl + 'admin/delete/check_number',
            type: 'POST',
            data: {
                'target': 2,
                'datepicker_report_to': $(this).val()
            },
            success: function(response) {
                $(".btn-delete-report").attr("total-image", response.total)
            }
        })
    })

    f.on('click', '.btn-delete-report', function() {

        var date = $('#datepicker_report_to').val()
        var total = $(".btn-delete-report").attr("total-image")

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
            swal({
                text: "Are you sure you want to delete all images to "+date+"?\n(Image Number: "+total+" images)",
                icon: false,
                buttons: true,
                dangerMode: true,
            })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            headers: { 'X-CSRF-TOKEN': __csrfToken },
                            url: __baseUrl + 'admin/delete',
                            type: 'POST',
                            data: {
                                'target': 2,
                                'datepicker_report_to': $('#datepicker_report_to').val()
                            },
                            success: function(response) {
                                $(".btn-delete-report").attr("total-image", 0)
                                swal(response.total + " images were deleted successfully!", {
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
                        })
                    }
                })
        }

    })
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

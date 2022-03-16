jQuery(document).ready(function() {
    // date range for excel report
    setRangeDatepicker.rangeDay('#datepicker_date', '#datepicker_date_to')
    // date range for excel covid
    setRangeDatepicker.rangeDay('#datepicker_covid', '#datepicker_covid_to')

    $('#tblExcel').on('click', '.btn-report-excel', function() {
        $("#target").val(1);
        $("#tblExcel").submit()
    })
})

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

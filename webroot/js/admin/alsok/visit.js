var customerId = 0;
var sArea = ''
var sCustomerID = ''
var sStaffID = ''
var sTimeVisit = ''

let visitLogTable = null
var psTimeVisit = ''
var ssStaffID = ''

// ************* DATATABLE *************
var visitTable = $('#serverDataTable').DataTable({
    "dom": 'itp',
    "processing": true,
    "serverSide": true,
    "pageLength": PAGE_LIMIT_EXTENT,
    "order": [
        [5, 'desc']
    ],
    "ajax": {
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/visit',
        type: 'POST',
        data: function(d) {
            d.sCustomerID = sCustomerID;
            d.sStaffID = sStaffID;
            d.sTimeVisit = sTimeVisit;
            d.sArea = sArea;
        }
    },
    "columns": [{
            "sName": "Index",
            "sClass": "text-right no-row",
            orderable: false,
            "render": function(data, type, row, meta) {
                return meta.row + 1; // This contains the row index
            }
        },
        {
            "data": "Region",
            "sClass": "text-center",
            render: function(data, type, row) {
                var result = Object.keys(areas).map(key => ({ key, value: areas[key] }));
                for(var z = 0; z < result.length; z++) {
                    var e = result[z]
                    if (e.key == row.Region) {
                        return e.value;
                    }
                }
                return "";
            }
        },
        { "data": "AreaName", "sClass": "area-name" },
        { "data": "IDCustomer", "sClass": "text-center customer-id" },
        {
            data: "ID",
            render: function(data, type, row) {
                return "" +
                    "<a href=\"javascript:void(0)\" class=\"open-visit\" data-customer=\"" + row.IDCustomer + "\">\n" +
                    "\t" + row.CustomerName + "\n" +
                    "</a>"
            }
        },
        { "data": "NumberVisit", "sClass": "text-center number-visit" },
        { "data": "IDStaff", "sClass": "text-center staff-id" },
        { "data": "StaffName", "sClass": "staff-name" },
        {
            "data": "LastVisit",
            "sClass": "text-center",
            render: function(data, type, row) {
                if (type === "sort" || type === "type") {
                    return data;
                }
                return moment(data).format("YYYY/MM/DD HH:mm:ss");
            }
        },
    ],
    // "drawCallback": function( settings ) {
    //     getMaxStaffID()
    // },
});

$(document).ready(function() {
    $(".sArea").select2({
        allowClear: true,
        placeholder: '',
        tags: true
    });
    $(".sArea").on("select2:select", function(e) {
        sArea = e.params.data.text;
        visitTable.draw()
    });
    $(".sArea").on("select2:clear", function(e) {
        sArea = ''
        visitTable.draw()
    });

    $(".sCustomerID").select2({
        allowClear: true,
        placeholder: '',
        tags: true
    });
    $(".sCustomerID").on("select2:select", function(e) {
        sCustomerID = e.params.data.text;
        visitTable.draw()
    });
    $(".sCustomerID").on("select2:clear", function(e) {
        sCustomerID = ''
        visitTable.draw()
    });

    $(".sStaffID").select2({
        allowClear: true,
        placeholder: '',
        tags: true
    });
    $(".sStaffID").on("select2:select", function(e) {
        sStaffID = e.params.data.text;
        visitTable.draw()
    });
    $(".sStaffID").on("select2:clear", function(e) {
        sStaffID = ""
        visitTable.draw()

    });

    $(".sTimeVisit").select2({
        allowClear: true,
        placeholder: '',
        //tags: true
    });
    $(".sTimeVisit").on("select2:select", function(e) {
        sTimeVisit = e.params.data.text;
        visitTable.draw()
    });
    $(".sTimeVisit").on("select2:clear", function(e) {
        sTimeVisit = e.params.data.text;
        visitTable.draw()
    });


    $(".psTimeVisit").select2({
        allowClear: true,
        placeholder: '',
        //tags: true
    });
    $(".psTimeVisit").on("select2:select", function(e) {
        psTimeVisit = e.params.data.text;
        visitLogTable.draw()
    });
    $(".psTimeVisit").on("select2:clear", function(e) {
        psTimeVisit = e.params.data.text;
        visitLogTable.draw()
    });


    $(".psStaffID").select2({
        allowClear: true,
        placeholder: '',
        tags: true
    });
    $(".psStaffID").on("select2:select", function(e) {
        ssStaffID = e.params.data.text;
        visitLogTable.draw()
    });
    $(".psStaffID").on("select2:clear", function(e) {
        ssStaffID = e.params.data.text;
        visitLogTable.draw()
    });

    $("#close-report").click(function() {
        //do something
        $("#visitModal").modal('show')
        $("#largeModal").modal('hide')
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

        $('#largeModal').modal('hide')

        $('#faceModal').modal()
    });

    $(document).on("click", "#faceModeBack", function() {
        $('#faceModal').modal('hide')
        $('#largeModal').modal()
    });

    // ************* DATATABLE *************
    visitLogTable = $('#dataTableReport').DataTable({
        "dom": 'it',
        "processing": true,
        "serverSide": true,
        "pageLength": PAGE_LIMIT_FULL,
        "order": [
            [0, 'desc']
        ],
        "ajax": {
            headers: { 'X-CSRF-TOKEN': __csrfToken },
            url: __baseUrl + 'ajax/getVisits',
            type: 'POST',
            data: function(d) {
                d.customerId = customerId;
                d.psStaffID = ssStaffID;
                d.psTimeVisit = psTimeVisit;
            }
        },
        "columns": [{
                "data": "DateTime",
                "sClass": "text-center",
                render: function(data, type, row) {
                    if (type === "sort" || type === "type") {
                        return data;
                    }
                    return moment(data).format("YYYY/MM/DD");
                }
            },
            { "data": "Tblmstaff.StaffID", "sClass": "text-center" },
            { "data": "Tblmstaff.Name" },
            {
                data: "ID",
                "sClass": "text-center",
                orderable: false,
                render: function(data, type, row) {
                    let staff = row.Tblmstaff
                        // let report = row.TBLTReport
                    let customer = row.TBLMCustomer
                    let StaffID;
                    let StaffName;
                    let CustomerName;
                    // let Date;
                    // let Time;
                    // let Report;
                    if (removeNull(staff) !== '') {
                        StaffID = staff.StaffID
                        StaffName = staff.Name
                    }
                    // if (removeNull(report) !== '') {
                    //     Date = report.Date
                    //     Time = report.Time
                    //     Report = report.Report
                    // }
                    if (removeNull(customer) !== '') {
                        CustomerName = customer.Name
                    }
                    return "" +
                        "<a href=\"#\" data-toggle=\"modal\" \n" +
                        "\tdata-id=\"" + row.ID + "\" \n" +
                        "\tdata-staffid=\"" + removeNull(StaffID) + "\" \n" +
                        "\tdata-staffname=\"" + removeNull(StaffName) + "\" \n" +
                        "\tdata-datetime=\"" + row.DateTime + "\"\n" +
                        "\tdata-customerid=\"" + removeNull(row.CustomerID) + "\" \n" +
                        "\tdata-customername=\"" + removeNull(CustomerName) + "\" \n" +
                        "\tdata-report=\"" + removeNull(row.Report) + "\"\n" +
                        "\tdata-imgcheckin=\"" + removeNull(row.imgcheckin) + "\" \n" +
                        "\tdata-imgcheckout=\"" + removeNull(row.imgcheckout) + "\" \n" +
                        "\tclass=\"visit-report\"><i class=\"far fa-edit\"></i>\n" +
                        "</a>";
                }
            }
        ],
        "drawCallback": function(settings) {
            if (customerId !== 0) $('#visitModal').modal()
        },
    });
});
// ************* SHOW MODAL HISTORIES *************
$(document).on('click', ".open-visit", function() {
    customerId = $(this).attr('data-customer')
    $('.psTimeVisit').val('');
    $('.psTimeVisit').trigger('change');

    $('.psStaffID').val('');
    $('.psStaffID').trigger('change');

    visitLogTable.draw()
})

// ************* SHOW MODAL REPORT *************
$(document).on('click', ".visit-report", function() {
    // $('.form-report').scrollTop(0)
    // $('#topBtn').hide()
    // $('#downBtn').show() //todo:scrooltop 0


    $("#StaffID").html($(this).attr('data-StaffID'));
    $("#StaffName").html($(this).attr('data-StaffName'));
    $("#date").html(moment($(this).attr('data-datetime')).format("YYYY/MM/DD"));
    $("#time").html(moment($(this).attr('data-datetime')).format("HH:mm:ss"));
    $("#CustomerID").html($(this).attr('data-CustomerID'));
    $("#CustomerName").html($(this).attr('data-CustomerName'));
    var report_id = $(this).attr('data-id')

    //report
    $.ajax({
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/schedule/getReport',
        type: 'POST',
        data: {
            'id': report_id,
        },
        success: function(res) {
            setFormReport(res)
            appendImages(report_id, res.images)
        }
    })

    $("#Picture").attr("data-checkIn", $(this).attr('data-imgcheckin'))
    $("#Picture").attr("data-checkOut", $(this).attr('data-imgcheckout'))

    $('#visitModal').modal('hide')
    setTimeout(function() {
        $('#largeModal').modal()
    }, 500)
});

function removeNull(string) {
    if (string == null) {
        return ''
    } else {
        return string
    }
}

// function getMaxStaffID(){
//     $('#tblVisitor .customer-id').each(function(){
//         var staffid = ''
//         var staffname = ''
//         $.ajax({
//             async:false,
//             headers: {'X-CSRF-TOKEN': __csrfToken},
//             url: __baseUrl + 'admin/visit/getFirstStaffID',
//             type: 'POST',
//             data:{'customerID': $(this).html()},
//             success:function(res){
//                 if(res.data){
//                     const data = res.data
//                     staffid = data.StaffID
//                     staffname = data.StaffName
//                 }

//             }
//         })

//         $(this).closest('tr').find('.staff-id').html(staffid)
//         $(this).closest('tr').find('.staff-name').html(staffname)
//         swal.close()
//     })
// }

function filter(area = "", customer = "", staff = "") {
    // step 1: hide
    $('#tblVisitor tr').each(function() {
            var staffID = $(this).find('.staff-id').html()
            var customerID = $(this).find('.customer-id').html()
            if (staff != "") {
                if (customer == "" && staff == staffID || customer == customerID && staff == staffID) {
                    $(this).show()
                    $(this).attr('attr-display', 'show')
                } else {
                    $(this).hide()
                    $(this).attr('attr-display', 'hide')
                }
            } else {
                if (customer == "" || customer == customerID) {
                    $(this).show()
                    $(this).attr('attr-display', 'show')
                } else {
                    $(this).hide()
                    $(this).attr('attr-display', 'hide')
                }
            }

        })
        // step 2: set No. again
    var i = 1
    $('#tblVisitor tr').each(function() {
            var display = $(this).attr('attr-display')
            if (display == "show") {
                $(this).find('.no-row').html(i)
                i++
            }
        })
        // step 3: set ... results found
    i--
    $('#serverDataTable_info').html('Showing 1 to ' + i + " of " + i + " entries")

}

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
                url: __baseUrl + 'admin/visit/sessionSort',
                headers: { 'X-CSRF-Token': __csrfToken },
                type: 'post',
                data: { 'col': name_col, 'dir': dir },
            })
        }
    }, 1000)
}

function beforeRender() {
    var col = $('#currIndexSort').val()
    var dir = $('#currDirSort').val()
    visitTable.order([Number(col), dir]).draw()
}
window.onload = beforeRender()

$('.modal-dialog').draggable({
    handle: ".modal-header"
});
$('form input').keydown(function (e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        sKeyword = $(this).val()
        $('#filterStaff').click()
        return false;
    }
});

// $('#btnHoldMove').click(function(e){
//     e.preventDefault()
// })

// var tmpAreaChecked = ''

$('#modalStaff').on('hidden.bs.modal', function(e) {
    // do something...
    $('#checkboxes').hide()
    expanded = false;
    // $('.areaChecked').html('')
})

$('.btnCloseCheckboxArea').on('click', function(e) {
    e.preventDefault()
    $('#checkboxes').hide()
    expanded = false;

    // append
    var i = 0
    var checked = ''
    var values = ''
    $("input:checkbox[name=Area]:checked").each(function() {
        if (i == 0) {
            checked += $(this).closest('label').find('span').html()
            values += $(this).closest('label').find('input').val()
        } else {
            checked += ", " + $(this).closest('label').find('span').html()
            values += "," + $(this).closest('label').find('input').val()
        }
        i++
    });
    $('.areaChecked').html(checked)
    $('.valuesChecked').val(values)
})


var sRegion = ''
var sAreaID = ''
var sKeyword = ''

var table = $('#serverDataTable').DataTable({
    "dom": '<"pull-left"fi>tp',
    "processing": true,
    "serverSide": true,
    "ajax": {
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/staff',
        type: 'POST',
        data: function(d) {
            d.region = sRegion;
            d.area = sAreaID;
            d.search.value = sKeyword;
        }
    },
    "columns": [{
            "data": null,
            "sortable": false,
            orderable: false,
            "sClass": "text-right",
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
            "data": "StaffID",
            "sClass": "new-tag text-center",
            render: function(data, type, row) {
                if (parseInt(row.isNew) === 0) {
                    return row.StaffID
                }
                else {
                    return '<span class="New-Icon">New</span>' +
                        row.StaffID
                }
            }
        },
        { "data": "Name" },
        { "data": "Password", orderable: false },
        { "data": "Position", "sClass": "text-center" },
        {
            "data": "Region",
            "sClass": "text-center",
            render: function(data, type, row) {
                var area = '<span class="area-text hidden-el">'+row.Area+'</span>'
                var result = Object.keys(areas).map(key => ({ key, value: areas[key] }));
                for(var z = 0; z < result.length; z++) {
                    var e = result[z]
                    if (e.key == row.Region) {
                        return e.value + area;
                    }
                }
                return "" + area
            }
        },
        { "data": "Area", "sClass": "area-text" },
        {
            "data": "CreatedDate",
            "sClass": "text-center",
            render: function(data, type, row) {
                if (type === "sort" || type === "type") {
                    return data;
                }
                return data != null ? moment(data).format("YYYY/MM/DD HH:mm") : "";
            }
        },

        {
            data: "ID",
            orderable: false,
            sClass: "text-center",
            render: function(data, type, row) {
                return "" +
                    "<input type=\"hidden\" id=\"ID\" value=\"" + row.ID + "\">\n" +
                    "<button type=\"button\" data-toggle=\"tooltip\" title=\"Edit\" class=\"btn btn-info btnEditStaff\" data-areaId=\"" + row.AreaID + "\"><i class=\"far fa-edit\"></i></button>\n" +
                    "<button type=\"button\" data-toggle=\"tooltip\" title=\"Delete\" class=\"btn btn-danger btnDeleteStaff\"><i class=\"far fa-trash-alt\"></i></button>\n" +
                    "<button type=\"button\" data-toggle=\"tooltip\" title=\"QR Code\" class=\"btn btn-dark btnQrCode\"><i class=\"fas fa-qrcode\"></i></button>\n" +
                    "<button type=\"button\" data-toggle=\"tooltip\" title=\"ID Card\" class=\"btn btn-success btnIdCard\"><i class=\"fas fa-id-card\"></i></button>";
            }
        },
    ],
    "searching": true,
    "paging": true,
    "pageLength": 100,
    "info": true,
    "order": [
        [1, 'asc']
    ],
});

$(document).ready(function() {
    // ************* DATATABLE *************
    $('[data-toggle="tooltip"]').tooltip();

    // ************* SHOW ADD STAFF MODAL *************
    $('#show-modal-staff').on('click', function() {
        clearStaffForm()
        $('.areaChecked').html('')
        $('.valuesChecked').val('')
        $('#modalStaff').modal()
    })

    // ************* SHOW EDIT STAFF MODAL *************
    $('#tblStaff').on('click', '.btnEditStaff', function() {
        let id = $(this).closest('tr').find("#ID").val()
        $('.areaChecked').html($(this).closest("tr").find('.area-text').html())
        $('.valuesChecked').val($(this).attr('data-areaId'))

        formEdit(id)
    })

    // *************** SUBMIT ADD/EDIT STAFF ****************
    $('#btnSubmitStaff').on('click', function() {
        putStaff()
    })

    // Region
    $(document).on('change', '#Region', function(e) {
        getAreaModal($(this).val())
        $('.valuesChecked').val("")
        $('.areaChecked').html("")
    })

    // **************** DELETE STAFF *************
    $('#tblStaff').on('click', '.btnDeleteStaff', function() {
        swal({
                title: "Are you sure you want to delete this staff?",
                icon: false,
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    let id = $(this).closest('tr').find("#ID").val()
                    delStaff(id)
                }
            })

    })

    // **************** SEARCH AREA *************
    // Region
    $(document).on('change', '#sRegion', function(e) {
        sRegion = $(this).val()

        getArea(sRegion)


        $('#filterStaff').click()
    })

    // Region
    $(document).on('change', '#sAreaID', function(e) {
        sAreaID = $(this).val()
        $('#filterStaff').click()
    })

    // Keyword
    $(document).on('change', '#sKeyword', function(e) {
        sKeyword = $(this).val()
        $('#filterStaff').click()
    })
});

// clear filter
$(document).on('click', '#clearFilter', function(e) {
    e.preventDefault()
    $("#sKeyword").val("");
    sKeyword = ''
    $("#sRegion").val(null);
    sRegion = ''
    $("#sAreaID").val(null);
    sAreaID = ''
    // reset table
    $('#filterStaff').click()
})

// apply filter
$(document).on('click', '#filterStaff', function() {
    table.ajax.reload();
})

/**
 *
 * @param region
 */
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
            $('#sAreaID').html(list)
        }
    })
}

/**
 *
 * @param region
 */
function getAreaModal(region, areas) {
    $.ajax({
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/schedule/getArea',
        type: 'POST',
        data: {
            'region': region
        },
        success: function(response) {
            var list = ''
            $.each(response, function(index, value){
                list +=  '<label><input type="checkbox" name="Area" value="'+ value.AreaID +'" /><span>【'+ value.AreaID + "】" +value.Name +'</span></label>'
            })
            $('.multile-checked-area').html(list)

            //dialog create/edit staff
            if (areas != undefined) {
                $('.form-area').attr('style', 'display:flex !important')
                $.each(areas, function(index, value) {
                    $("input:checkbox[name=Area]").each(function() {
                        if ($(this).val() == value.AreaID) {
                            $(this).prop('checked', true)
                        }
                    });
                })
            }
        }
    })
}

/**
 *
 */
function clearStaffForm() {
    $('#id_staff').val('')
    $('#StaffID').show()
    $('.id-helper').show()
    $('#spanStaffID').html('')
    $('#spanStaffID').css('display', 'none')
    $('#StaffID').val('')
    $('#Name').val('')
    $('#Position').val('-1')
    $('#Region').val('')
    $('.form-area').attr('style', 'none !important')
    $("input:checkbox[name=Area]:checked").each(function() {
        $(this).prop('checked', false)
    });
}

/**
 *
 * @param id
 */
function formEdit(id) {
    clearStaffForm()
    $.ajax({
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/staff/search',
        type: 'post',
        data: { 'id_staff': id },
        success: function(response) {
            if (response.success) {
                const data = response.data
                const areas = response.areas

                getAreaModal(data.Region, areas)

                $('#StaffID').hide()
                $('.id-helper').hide()
                $('#spanStaffID').html(data.StaffID)
                $('#spanStaffID').css('display', 'block')
                $('#Name').val(data.Name)
                $('#Position').val(data.Position)
                $('#id_staff').val(data.ID)
                $('#Password').val(data.Password)
                $('#Title').val(data.Title)
                $('#Region').val(data.Region)

                if (data.Position == 'Area Leader' || data.Position == 'Leader') {
                    // $('.form-area').attr('style', 'display:flex !important')
                    // $.each(areas, function(index, value) {
                    //     $("input:checkbox[name=Area]").each(function() {
                    //         if ($(this).val() == value.AreaID) {
                    //             $(this).prop('checked', true)
                    //         }
                    //     });
                    // })
                }

                $('#modalStaff').modal()
            }
        },
        error: function(response) {
            console.log(response)
        }
    })
}

/**
 *
 */
function putStaff() {
    let check = validateform()
    if (check) {
        var areas = []
        $("input:checkbox[name=Area]:checked").each(function() {
            areas.push($(this).val());
        });
        var data = {
                'ID': $('#id_staff').val(),
                'Name': $('#Name').val(),
                'Password': $('#Password').val(),
                'Position': $('#Position').val(),
                'Title': $('#Title').val(),
                'Region': $('#Region').val(),
                'Areas': areas,
                'Area': $('.valuesChecked').val(),
                'IDStaff': $('#spanStaffID').html(),
                'currIndexSort': $("#currIndexSort").val(),
                'currDirSort': $("#currDirSort").val(),
            }
            // add new
        if ($('#spanStaffID').html() == "") {
            data['StaffID'] = $('#StaffID').val()
            data['IDStaff'] = $('#StaffID').val()
        }
        $.ajax({
            url: __baseUrl + 'admin/staff/edit',
            headers: { 'X-CSRF-Token': __csrfToken },
            type: 'post',
            data: data,
            success: function(response) {
                if (parseInt(response.status) === 1) {
                    $('#modalStaff').modal('hide')
                    clearStaffForm()
                    swal({
                        title: "Successfully!",
                        icon: "success",
                    })
                    table.order([
                        [Number($('#currIndexSort').val()), $('#currDirSort').val()]
                    ]).draw()
                } else {
                    swal({
                        title: "Have error. Please double check that the staff ID is not duplicates in the database.",
                        icon: "error",
                    });
                }
            },
            error: function(response) {
                console.log(response)
            }
        })
    }
}

/**
 *
 * @param id
 */
function delStaff(id) {
    $.ajax({
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/staff/delete',
        type: 'post',
        data: { 'id_staff': id },
        success: function(response) {
            if (response.status) {
                // loadStaffTable(response.lst_staffs)
                swal({
                        'title': 'Deleted Successfully!',
                        'icon': 'success'
                    })
                    .then((OK) => {
                        location.reload()
                    });
            }
        },
        error: function(response) {
            console.log(response)
        }
    })
}

/**
 *
 * @param lst_staffs
 */
function loadStaffTable(lst_staffs) {
    $('#dataTable').DataTable().clear().draw();
    var table = $('#dataTable').DataTable();
    for (i = 0; i < lst_staffs.length; i++) {
        table.rows.add($(
            '<tr>' +
            '<td class="text-center">' + lst_staffs[i].StaffID + '</td>' +
            '<td>' + lst_staffs[i].Name + '</td>' +
            '<td class="text-center">' + lst_staffs[i].Position + '</td>' +
            '<td class="text-center">' + lst_staffs[i].CreatedDate + '</td>' +
            '<td class="text-center w-10">\n' +
            '    <input type="hidden" id="ID" value="' + lst_staffs[i].ID + '">\n' +
            '    <button type="button" class="btn btn-info btnEditStaff"><i class="far fa-edit"></i></button>\n' +
            '    <button type="button" class="btn btn-danger btnDeleteStaff"><i class="far fa-trash-alt"></i></button>\n' +
            '</td>' +
            '</tr>'
        )).draw();
    }
}

/**
 *
 * @returns {boolean}
 */
function validateform() {
    if ($('#spanStaffID').html() == "" && !$('#StaffID').val().length) {
        alert("Please fill out StaffID field!")
        return false;
    }
    if (!$('#Name').val().length) {
        alert("Please fill out Name field!")
        return false;
    }
    if (!$('#Password').val().length) {
        alert("Please fill out Password field!")
        return false;
    }
    if (!$('#Password').val().match(/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{6,}/g)) {
        alert("The password must be 6 characters long, must contain letters (uppercase and lowercase) and digits")
        return false;
    }
    // if(!$('input[name="Position"]:checked').length){
    //     alert("Please select Position!");
    //     return false;
    // }
    if (!$('#Region').val().length) {
        alert("Please fill out Region field!")
        return false;
    }
    if ($('#Position').val() == '-1') {
        alert("Please select Position!");
        return false;
    } else {
        if ($('#Position').val() == positions.supper_leader || $('#Position').val() == positions.leader) {
            var check = 0
            $("input:checkbox[name=Area]:checked").each(function() {
                check++
            });
            if (check == 0) {
                alert("Please select Area");
                return false;
            }
        }
    }

    return true;
}

$('#modalStaff').on('hidden.bs.modal', function() {
    $(this).find('.input').val('');
})

$('#StaffID').on('input', function() {
    showClearInput("#StaffID")
})
$('#Name').on('input', function() {
    showClearInput("#Name")
})
$('#Password').on('input', function() {
    showClearInput("#Password")
})
$('#Title').on('input', function() {
    showClearInput("#Title")
})

$('#clearInputID').on('click', function() {
    clearInput('#clearInputID')
})
$('#clearInputName').on('click', function() {
    clearInput('#clearInputName')
})
$('#clearInputPassword').on('click', function() {
    clearInput('#clearInputPassword')
})
$('#clearInputTitle').on('click', function() {
    clearInput('#clearInputTitle')
})

function showClearInput(input) {
    if ($(input).val().length) {
        $(input).closest("div").find("i").css("display", "block")
    } else {
        $(input).closest("div").find("i").css("display", "none")
    }
}

function clearInput(el) {
    $(el).closest("div").find("input").val('')
    $(el).css('display', 'none')
}

// qr code
$('#tblStaff').on('click', '.btnQrCode', function(e) {
    e.preventDefault()
    var id = $(this).closest('tr').find("#ID").val()
    $.ajax({
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/staff/qrCode',
        type: 'POST',
        data: { 'id': id },
        success: function(res) {
            var data = res.data
            $('#StaffIDQR').html(data.StaffID)
            $('#StaffNameQR').html(data.Name)
            $('#imgQR').attr('src', res.file)
            $('#modalQR').modal()
        }
    })
})

$('#btnSaveQR').on('click', function(e) {
    e.preventDefault()

    var src = $('#modalQR').find("#imgQR").attr('src')
    $.ajax({
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/staff/saveQR',
        type: 'POST',
        data: { 'src': src },
        success: function(res) {
            if (res.success) {
                $('#modalQR').modal('hide')
                swal({
                    title: 'Saved successfully!',
                    icon: 'success'
                })
                setTimeout(function() {
                    swal.close()
                }, 1500)
            }
        }
    })

})

$('#btnBackQR').on('click', function(e) {
    e.preventDefault()
    var src = $('#modalQR').find("#imgQR").attr('src')
    $.ajax({
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/staff/delQR',
        type: 'POST',
        data: { 'src': src },
        success: function(res) {
            if (res.success) {
                $('#modalQR').modal('hide')
            }
        }
    })
})

// id card
$('#tblStaff').on('click', '.btnIdCard', function(e) {
    e.preventDefault()
    var id = $(this).closest('tr').find("#ID").val()
    $.ajax({
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/staff/exportNamecard',
        type: 'POST',
        data: { 'id': id },
        success: function(res) {
            window.open(
                res.file,
                '_blank'
            )
        }
    })
})

$('#modalStaff').on('change', '#Position', function() {
    // $("input:checkbox[name=Area]:checked").each(function(){
    //     $(this).prop('checked', false)
    // });
    if ($(this).val() == 'Area Leader' || $(this).val() == 'Leader') {
        $('.form-area').attr('style', 'display:flex !important')
    } else {
        $('.form-area').attr('style', 'display:none !important')
    }
})

$('#serverDataTable th').on('click', function() {
    setSort($(this))
})

function setSort(self) {
    setTimeout(function() {
        var index = $('th:contains(' + self.html() + ')').index()
        var dir = ''
        if (index != 0 && index != 3 && index != 7) {
            var name_col = self.html()
            if (name_col == 'Name') {
                name_col = 'Staff Name'
            }
            $('#currIndexSort').val(index)
            dir = (self.attr('aria-sort') == 'ascending') ? "asc" : "desc"
            $('#currDirSort').val(dir)

            $.ajax({
                url: __baseUrl + 'admin/staff/sessionSort',
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
    table.order([Number(col), dir]).draw()
}
window.onload = beforeRender()

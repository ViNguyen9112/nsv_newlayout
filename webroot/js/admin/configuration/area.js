jQuery(document).ready(function() {
    $('#serverDataTable').DataTable({
        "dom": '<"pull-button"fi>tp',
        "bFilter": false,
        "pageLength": PAGE_LIMIT_SPECIFIC,
        "bSort" : false
    });

    $('.fa-minus').hide()

    $('.fa-plus').on('click', function() {
        var id = $("#AreaID").val()
        var region = $("#Region").val()
        var code = $("#AreaCode").val()
        var name = $("#AreaName").val()

        if (code == '' || name == '' || region == '') {
            swal('Code and Name are required.', {
                buttons: {
                    cancel: "OK",
                },
            });
        }
        else if ( checkCode(code, id) ) {
            swal('The Code must contain letters uppercase and digits', {
                buttons: {
                    cancel: "OK",
                },
            });
        }
        else if ( checkExist(code, id) ) {
            swal('Code already exist.', {
                buttons: {
                    cancel: "OK",
                },
            });
        }
        else {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': __csrfToken },
                url: __baseUrl + 'admin/configuration/area',
                type: 'POST',
                data: {
                    'AreaID': id,
                    'AreaCode': code,
                    'Region': region,
                    'AreaName': name
                },
                success: function(response) {
                    swal({
                        'title': 'Successfully!',
                        'icon': 'success'
                    }).then((OK) => {
                        location.reload()
                    });
                }
            })
        }
    })

    $('.fa-minus').on('click', function() {
        var id = $("#AreaID").val()
        if (id == '') return false;
        swal({
            text: "Areas and Staffs belong to this deleted too. \nAre you sure you want to delete ?",
            icon: false,
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': __csrfToken },
                        url: __baseUrl + 'admin/configuration/deleteArea',
                        type: 'POST',
                        data: {
                            'AreaID': id,
                        },
                        success: function(response) {
                            swal({
                                'title': 'Successfully!',
                                'icon': 'success'
                            }).then((OK) => {
                                location.reload()
                            });
                        }
                    })
                }
            })
    })

    $('.a-edit-click').on('click', function(evt) {
        evt.preventDefault()
        $('.fa-minus').show()
        var td = $(this).closest("tr").find("td")
        var AreaCode = td.eq(2).text()
        var AreaName = td.eq(3).text()
        var Region = td.eq(1).attr("target-region")
        $("#AreaCode").val(AreaCode).attr("readonly", true)
        $("#AreaName").val(AreaName)
        $("#Region").val(Region)
        $("#AreaID").val($(this).attr("target-id"))
    })
})

function checkExist(code, id) {
    var response = false
    $('#serverDataTable tbody tr').each(function(){
        var td = $(this).find('td').eq(1)
        if(td.text() == code && td.attr("target-id") != id){
            response = true
        }
    });
    return response
}

function checkCode(code, id) {
    if (!/[a-z]/.test(code) && /[A-Z][0-9]/.test(code)) {
        return false;
    }
    return true;
}

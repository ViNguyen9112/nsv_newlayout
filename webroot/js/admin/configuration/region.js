jQuery(document).ready(function() {

    $('.fa-minus').hide()

    $('.fa-plus').on('click', function() {
        var id = $("#ID").val()
        var code = $("#RegionCode").val()
        var name = $("#RegionName").val()

        if (code == '' || name == '') {
            swal('Code and Name are required.', {
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
        else if ( checkCode(code, id) ) {
            swal('The Code must contain letters uppercase', {
                buttons: {
                    cancel: "OK",
                },
            });
        }
        else {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': __csrfToken },
                url: __baseUrl + 'admin/configuration/region',
                type: 'POST',
                data: {
                    'ID': id,
                    'RegionCode': code,
                    'RegionName': name
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
        var id = $("#ID").val()
        if (id <= 0) return false;
        swal({
            text: "Areas belong to this deleted too. \nAre you sure you want to delete ?",
            icon: false,
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        headers: { 'X-CSRF-TOKEN': __csrfToken },
                        url: __baseUrl + 'admin/configuration/deleteRegion',
                        type: 'POST',
                        data: {
                            'ID': id,
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
        var RegionCode = td.eq(1).text()
        var RegionName = td.eq(2).text()
        $("#RegionCode").val(RegionCode).attr("readonly", true)
        $("#RegionName").val(RegionName)
        $("#ID").val($(this).attr("target-id"))
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

function checkCode(code) {
    if (!/[a-z]/.test(code) && /[A-Z]/.test(code)) {
        return false;
    }
    return true;
}

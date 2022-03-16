jQuery(document).ready(function() {
    var f = $("#tblRestore")

    f.on('click', '.btn-export-backup', function () {
        var file_data = $('#zip_file').prop('files')[0];
        var form_data = new FormData();
        form_data.append('upload', file_data);

        $.ajax({
            headers: {'X-CSRF-TOKEN': __csrfToken},
            url : __baseUrl + "admin/restore",
            type : 'POST',
            data : form_data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,
            success : function(response){
                if (response.status) {
                    swal({
                        text: "The images have been restored!!!",
                        icon: false,
                        buttons: {
                            catch: {
                                text: "OK",
                                value: true,
                            },
                        },
                        dangerMode: true,
                    })
                        .then((willDelete) => {
                            if (willDelete) {
                                $(location).attr('href', __baseUrl + "admin/restore");
                            }
                        })
                }
                else {
                    swal("There are some issue with restore. Please try again!", {
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
            }
        });
    })

    //showing password confirm
    if (parseInt(CST_SHOW_PASSWORD_DIALOG) === 1) {
        outputPasswordConfirm()
    }
})

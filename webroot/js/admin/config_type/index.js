var global_type_opt = []
var color_of_type = ''
jQuery(document).ready(function () {
    //click Add Type
    $("#show-modal-config-type").click(function () {
        clearForm()

        $("#modalReportType").modal()
    })


})

//click Submit
$(document).on('click', '#btnSubmitType', function () {
    var isValidate = isValidateForm()

    if (isValidate) {
        var f = $("#modalReportType")
        var data = {
            ID: f.find(".inputID").val(),
            TypeCode: f.find(".inputTypeCode").val(),
            Type1: f.find(".inputType1").val(),
            Type2: f.find(".inputType2").val(),
            Type3: f.find(".inputType3").val(),
            TypeColor : color_of_type,
            filename : $('input[name="filename"]').val(),
            org_filename : $('input[name="org_filename"]').val(),
            temp : $('input[name="temp"]').val(),
            del_image : $('input[name="del_image"]').val()
        };

        $.ajax({
            url: __baseUrl + 'admin/config-type/edit',
            headers: {'X-CSRF-Token': __csrfToken},
            type: 'post',
            data: data,
            success: function (response) {
                clearForm()
                if (parseInt(response.status) === 1) {
                    table.ajax.reload();
                    PopupModule.alertPopup('Successfully!')
                    f.modal('hide')
                } else {
                    PopupModule.errorPopup('There are issue on processing. Please try again!')
                }
            },
            error: function (response) {
                console.log(response)
            }
        })
    }
})

$(document).on('click', '.btnEditConfigType', function () {
    let id = $(this).closest('tr').find("#ID").val()
    loadEditForm(id)
})

$(document).on('click', '.color', function () {
    if(color_of_type == $(this).attr('data-color')){
        color_of_type = ''
        $(this).removeAttr('data-selected')
    }else{
        $('.color').removeAttr('data-selected');
        color_of_type = $(this).attr('data-color')
         $(this).attr('data-selected','')
    }

})

$(document).on('click', '.btnDeleteConfigType', function () {
    let id = $(this).closest('tr').find("#ID").val()
    PopupModule.confirmPopuWithContent('', 'Are you sure you want to delete this item?').then((response) => {
        if (response) {
            deleteObject(id, table)
        }
    });

})
jQuery('.checkDelete').change(function() {
    if($('.checkDelete').is(":checked")){
        isDeleted = 1;
     }else{
        isDeleted = '';
     }
    table.ajax.reload();
})

// ************* DATATABLE *************
var sKeyword = ''
var isDeleted = ''
var table = $('#serverDataTable').DataTable({
    "dom": '<"pull-left"fi>tp',
    "processing": true,
    "serverSide": true,
    "ajax": {
        headers: {'X-CSRF-TOKEN': __csrfToken},
        url: __baseUrl + 'admin/config-type',
        type: 'POST',
        data: function (d) {
            d.search.value = sKeyword;
            d.isDeleted = isDeleted
        }
    },
    "createdRow": function (row, data, dataIndex) {
        if (parseInt(data.FlagDelete) === 1) {
            $(row).addClass('delete');
        }
    },
    "drawCallback": function(settings) {
        console.log(settings.json);
        var response = settings.json
        global_type_opt = response.type_opt
        //do whatever
    },
    "columns": [{
        "data": null,
        "orderable": false,
        "sortable": false,
        "sClass": "text-right",
        render: function (data, type, row, meta) {
                return (meta.row + meta.settings._iDisplayStart + 1);
            }
        },
        {data: "TypeCode", "sClass": "text-left"},
        {data: "Type2", "sClass": "text-left work-pre"},
        {data: "Type3", "sClass": "text-left work-pre"},
        {data: "Type1", "sClass": "text-left work-pre"},
        {
            data: "TypeColor",
            "sClass": "text-center work-pre",
            orderable: false,
             render: function (data, type, row) {

                    return "" +
                        "<span class='span-color' style='background-color:"+row.TypeColor+"; color:"+row.TypeColor+" '>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";

            }
        },
        {
            data: "Icon",
            "sClass": "text-center",
            render: function (data, type, row) {
                if (row.TypeImage != null) {
                    return "<img src=\""+__baseUrl + "img/admin/config-type/"+row.TypeImage+"\" width=\"40\"/>\n";
                } else {
                    return "<img src=\""+__baseUrl+"img/icon-report/icon-default.png\" width=\"40\"/>\n";
                }
            }
        },
        /*{
            data: "Icon",
            "sClass": "text-center",
            orderable: false,
             render: function (data, type, row) {
                    if(parseInt(row.TypeCode) > 7){
                         return "" +
                        "\t<img src=\""+__baseUrl+"img/icon-report/icon-default.png\" width=\"40\"/>\n";
                    }
                    return "" +
                        "\t<img src=\""+__baseUrl+"img/icon-report/icon-"+parseInt(row.TypeCode)+".png?="+__refeshCathe+"\" width=\"40\"/>\n";

            }
        },*/
        {
            data: "ID",
            orderable: false,
            sClass: "text-center",
            render: function (data, type, row) {
                if (parseInt(row.FlagDelete) === 1) {
                    return ""
                }
                else {
                    return "" +
                        "<input type=\"hidden\" id=\"ID\" value=\"" + row.ID + "\">\n" +
                        "<button type=\"button\" class=\"btn btn-info btnEditConfigType\"><i class=\"far fa-edit\"></i></button>\n" +
                        "<button type=\"button\" class=\"btn btn-danger btnDeleteConfigType\"><i class=\"far fa-trash-alt\"></i></button>";
                }
            }
        },
    ],
    "searching": true,
    "paging": true,
    "pageLength": 200,
    "info": true,
    "order": [
        [1, 'asc']
    ],
});

// ************* FUNCTIONS *************
function isValidateForm() {
    var f = $("#modalReportType")
    var TypeCode = f.find(".inputTypeCode").val()
    var type1 = f.find(".inputType1").val()
    var type2 = f.find(".inputType2").val()
    var type3 = f.find(".inputType3").val()
    var id = f.find(".inputID").val()
    var msg = ""
    if (TypeCode == '') {
        msg += "Please fill out Report ID field!"
    }
    if (type1 == '') {
        msg += "\nPlease fill out Native language field!"
    }
    if (type2 == '') {
        msg += "\nPlease fill out English field!"
    }
    if (type3 == '') {
        msg += "\nPlease fill out Japanese field!"
    }
    if (checkExist(TypeCode, id)) {
        msg += "\nCode already exist."
    }
    if (checkCode(TypeCode)) {
        msg += "\nThe Code must contain number."
    }

    if (msg != "") {
        PopupModule.alertPopup(msg)
        return false;
    }

    return true;
}

function clearForm() {
    var f = $("#modalReportType")
    f.find(".inputID").val("")
    f.find(".inputTypeCode").val("").attr("readonly", false)
    f.find(".inputType1").val("")
    f.find(".inputType2").val("")
    f.find(".inputType3").val("")
    $('.color').removeAttr('data-selected','')
    $('#txt-name-main-image').text('')
    color_of_type = '';
}

function loadEditForm(id) {
    $('.color').removeAttr('data-selected','')
    $.ajax({
        headers: {'X-CSRF-TOKEN': __csrfToken},
        url: __baseUrl + 'admin/config-type/search',
        type: 'post',
        data: {'ID': id},
        success: function (response) {
            var obj = response.obj
            console.log(obj);
            color_of_type = obj.TypeColor;
            var f = $("#modalReportType")
            f.find(".inputID").val(obj.ID)
            f.find(".inputTypeCode").val(obj.TypeCode).attr("readonly", true)
            f.find(".inputType1").val(obj.Type1)
            f.find(".inputType2").val(obj.Type2)
            f.find(".inputType3").val(obj.Type3)
            f.find(".color").val(obj.Type3)
            $('#txt-name-main-image').text(obj.TypeImage)
            $('span[data-color="'+obj.TypeColor+'"]').attr('data-selected','')
            $('#modalReportType').modal()
        },
        error: function (response) {
            console.log(response)
        }
    })
}

function deleteObject(id, table) {
    $.ajax({
        headers: {'X-CSRF-TOKEN': __csrfToken},
        url: __baseUrl + 'admin/config-type/delete',
        type: 'post',
        data: {'ID': id},
        success: function (response) {
            if (response.status) {
                table.ajax.reload();
                PopupModule.alertPopup('Deleted Successfully!')
            }
        },
        error: function (response) {
            console.log(response)
        }
    })
}

function checkExist(code, id) {
    var response = false

    var result = Object.keys(global_type_opt).map(key => ({ key, value: global_type_opt[key] }));
    for(var z = 0; z < result.length; z++) {
        var e = result[z]
        if (e.key == code && id == '') {
            response =  true;
        }
    }
    return response;
}

function checkCode(code) {
    if (!/\D/.test(code)) {
        return false;
    }
    return true;
}

$(document).ready(function() {
  $('select[name="colorpicker-change-background-color"]').on('change', function() {
    $(document.body).css('background-color', $('select[name="colorpicker-change-background-color"]').val());
  });

  setTimeout(function() {
    $('select[name="colorpicker-selectColor-#fbd75b"]').simplecolorpicker('selectColor', '#fbd75b');
  }, 5000);

  setTimeout(function() {
    $('select[name="colorpicker-selectColor-#FBD75B"]').simplecolorpicker('selectColor', '#FBD75B');
  }, 5000);

  setTimeout(function() {
    $('select[name="colorpicker-selectColor-#fbd75b-multiple"]').simplecolorpicker('selectColor', '#fbd75b');
  }, 5000);

  setTimeout(function() {
    // Generates a JavaScript error
    $('select[name="colorpicker-selectColor-unknown"]').simplecolorpicker('selectColor', 'unknown');
  }, 5000);


  setTimeout(function() {
    $('select[name="colorpicker-picker-selectColor-#fbd75b"]').simplecolorpicker('selectColor', '#fbd75b');
  }, 5000);

  setTimeout(function() {
    // Generates a JavaScript error
    $('select[name="colorpicker-picker-selectColor-unknown"]').simplecolorpicker('selectColor', 'unknown');
  }, 5000);


  $('#init').on('click', function() {
    $('select[name="colorpicker-shortlist"]').simplecolorpicker();
    $('select[name="colorpicker-longlist"]').simplecolorpicker();
    $('select[name="colorpicker-notheme"]').simplecolorpicker();
    $('select[name="colorpicker-regularfont"]').simplecolorpicker({theme: 'regularfont'});
    $('select[name="colorpicker-glyphicons"]').simplecolorpicker({theme: 'glyphicons'});
    $('select[name="colorpicker-fontawesome"]').simplecolorpicker({theme: 'fontawesome'});
    $('select[name="colorpicker-bootstrap3-form"]').simplecolorpicker({theme: 'glyphicons'});
    $('select[name="colorpicker-modal-inline"]').simplecolorpicker();
    $('select[name="colorpicker-modal-picker"]').simplecolorpicker({picker: true});
    $('select[name="colorpicker-option-selected"]').simplecolorpicker({theme: 'glyphicons'});
    $('select[name="colorpicker-options-disabled"]').simplecolorpicker({theme: 'glyphicons'});
    $('select[name="colorpicker-option-selected-disabled"]').simplecolorpicker({theme: 'glyphicons'});
    $('select[name="colorpicker-optgroups"]').simplecolorpicker();
    $('select[name="colorpicker-change-background-color"]').simplecolorpicker();
    $('select[name="colorpicker-selectColor-#fbd75b"]').simplecolorpicker({theme: 'glyphicons'});
    $('select[name="colorpicker-selectColor-#FBD75B"]').simplecolorpicker({theme: 'glyphicons'});
    $('select[name="colorpicker-selectColor-#fbd75b-multiple"]').simplecolorpicker({theme: 'glyphicons'});
    $('select[name="colorpicker-selectColor-unknown"]').simplecolorpicker({theme: 'glyphicons'});

    $('select[name="colorpicker-picker-shortlist"]').simplecolorpicker({picker: true, theme: 'glyphicons'});
    $('select[name="colorpicker-picker-longlist"]').simplecolorpicker({picker: true, theme: 'glyphicons'});
    $('select[name="colorpicker-picker-delay"]').simplecolorpicker({picker: true, theme: 'glyphicons', pickerDelay: 1000});
    $('select[name="colorpicker-picker-option-selected"]').simplecolorpicker({picker: true, theme: 'glyphicons'});
    $('select[name="colorpicker-picker-options-disabled"]').simplecolorpicker({picker: true, theme: 'glyphicons'});
    $('select[name="colorpicker-picker-option-selected-disabled"]').simplecolorpicker({picker: true, theme: 'glyphicons'});
    $('select[name="colorpicker-picker-optgroups"]').simplecolorpicker({picker: true, theme: 'glyphicons'});
    $('select[name="colorpicker-picker-selectColor-#fbd75b"]').simplecolorpicker({picker: true, theme: 'glyphicons'});
    $('select[name="colorpicker-picker-selectColor-unknown"]').simplecolorpicker({picker: true, theme: 'glyphicons'});

  });


  // By default, activate simplecolorpicker plugin on HTML selects
  $('#init').trigger('click');

    //click button UPLOAD
    $(document).on('click', '.upload-btn', function (evt) {
        $('#upload').click();
    });

    //after choose local file
    $(document).on('change', '#upload', function (evt) {
        var file_data = $(this).prop('files')[0];
        var form_data = new FormData();
        form_data.append('upload', file_data);

        $.ajax({
            headers: {'X-CSRF-TOKEN': __csrfToken},
            url: __baseUrl + 'admin/config-type/upload',
            type : 'POST',
            data : form_data,
            processData: false,  // tell jQuery not to process the data
            contentType: false,
            beforeSend: function() {
                //LoadingModule.showLoading()
            },
            success : function(resp) {
                $(".main-image-response").html(resp)
            },
            complete: function () {
                //LoadingModule.hideLoading()
            }
        });
    });
});

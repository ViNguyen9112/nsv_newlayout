var global_category_opt = []
jQuery(document).ready(function () {
    //click Add Type
    $("#show-modal-config-type").click(function () {
        clearForm()
        $("#modalReportCategory").modal()
    })
})

//click Submit
$(document).on('click', '#btnSubmitType', function() {
    var isValidate = isValidateForm()

    if (isValidate) {
        var f = $("#modalReportCategory")
        if(f.find(".inputID").val() ==""){
            sKeyword =  f.find(".inputTypeCode").val();
            sCatCode ='';
            $('#sType').val(sKeyword)

        }       
        var data = {
            ID: f.find(".inputID").val(),
            CatCode: f.find(".inputCatCode").val(),
            CatName1: f.find(".inputCatName1").val(),
            CatName2: f.find(".inputCatName2").val(),
            CatName3: f.find(".inputCatName3").val(),
            TypeCode: f.find(".inputTypeCode").val(),
            HideFlag: f.find(".inputHideFlag").is(":checked") ? 1 : 0,
            TypeCat : f.find(".inputTypeCat").val()
        }
        $.ajax({
            url: __baseUrl + 'admin/config-category/edit',
            headers: { 'X-CSRF-Token': __csrfToken },
            type: 'post',
            data: data,
            success: function(response) {
                clearForm()
                if (parseInt(response.status) === 1) {
                    table.ajax.reload();
                    PopupModule.alertPopup('Successfully!')
                    f.modal('hide')
                }
                else {
                    PopupModule.errorPopup('There are issue on processing. Please try again!')
                }
            },
            error: function(response) {
                console.log(response)
            }
        })
    }
})
$(document).on('change', '#sType', function(e) {
    sKeyword = $(this).val()
    sCatCode = '';
    $('#filterStaff').click()
})
// apply filter
$(document).on('click', '#filterStaff', function() {
    table.ajax.reload();
})

$(document).on('click','.bi-arrow-up', function(){
    row_id = $(this).attr('row-id')
    updatesort(row_id, 'down')
})

$(document).on('click','.bi-arrow-down', function(){
    row_id = $(this).attr('row-id')
    updatesort(row_id, 'up')
})

$(document).on('click', '.btnEditConfigCategory', function() {
    let id = $(this).closest('tr').find("#ID").val()
    loadEditForm(id)
})

$(document).on('click', '.btnDeleteConfigCategory', function() {
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
var sCatCode = ''
var isDeleted = ''
var table = $('#serverDataTable').DataTable({
    "dom": '<"pull-left"fi>tp',
    "processing": true,
    "serverSide": true,
    "ajax": {
        headers: {'X-CSRF-TOKEN': __csrfToken},
        url: __baseUrl + 'admin/config-category',
        type: 'POST',
        data: function (d) {
            d.TypeCode  = sKeyword;
            d.CatCode   = sCatCode
            d.isDeleted = isDeleted
        }
    },
    "createdRow": function (row, data, dataIndex) {
        if (parseInt(data.FlagDelete) === 1) {
            $(row).addClass('delete');
        }
    },
    "drawCallback": function(settings) {
        var response = settings.json
        global_category_opt = response.category_opt
        //do whatever
    },
    "columns": [{
        "data": null,
        "sortable": false,
        "orderable": false,
        "sClass": "text-right",
        render: function (data, type, row, meta) {
                return (meta.row + meta.settings._iDisplayStart + 1);
            }
        },
        { data: "CatCode", "sClass": "text-left"},        
        { data: "CatName2" , "sClass": "text-left work-pre"},
        { data: "CatName3" , "sClass": "text-left work-pre"},
        { data: "CatName1" , "sClass": "text-left work-pre"},
        { data: "TypeWithName" , "sClass": "text-left"},
        { data: "TypeCategory" , "sClass": "text-left"},
        {
            data: "CatSortNumber" ,
            "sClass": "text-center ",
            render: function(data, type, row) {
                checked = ''
                if(row.CatSortNumber ==null){
                    return '';
                }
                return  ""+
                    '<span class="numbersort">'+row.CatSortNumber+'</span>'+
                    '<svg xmlns="http://www.w3.org/2000/svg" row-id="'+row.ID +'" width="16"  height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">'+
                        '<path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>'+
                    '</svg>' +
                    ' <svg xmlns="http://www.w3.org/2000/svg" row-id="'+row.ID +'" width="16"  height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">'+
                        '<path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"/>'+
                    '</svg>'
                }
        },
        {
            data: "HideFlag" ,
            orderable: false,
            "sClass": "text-canter",
            render: function(data, type, row) {
                checked = ''
                if(row.HideFlag == 1){
                    checked = "checked"
                }
                return "" +
                    "<input type=\"checkbox\" id=\"ID\" value=\"" + row.ID + "\" "+checked +" class='disabled-check'>\n"
                     }

        },
        {
            data: "ID",
            orderable: false,
            sClass: "text-center ",
            render: function(data, type, row) {
                if (parseInt(row.FlagDelete) === 1) {
                    return ""
                }
                else {
                    return "" +
                        "<input type=\"hidden\" id=\"ID\" value=\"" + row.ID + "\">\n" +
                        "<button type=\"button\" class=\"btn btn-info btnEditConfigCategory\"><i class=\"far fa-edit\"></i></button>\n" +
                        "<button type=\"button\" class=\"btn btn-danger btnDeleteConfigCategory\"><i class=\"far fa-trash-alt\"></i></button>";
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
    var f = $("#modalReportCategory")
    var CatCode = f.find(".inputCatCode").val()
    var CatName1 = f.find(".inputCatName1").val()
    var CatName2 = f.find(".inputCatName2").val()
    var CatName3 = f.find(".inputCatName3").val()
    var TypeCode = f.find(".inputTypeCode").val()
    var id = f.find(".inputID").val()
    var msg = ""
    if (CatCode == '') {
        msg += "Please fill out Category ID field!"
    }
    if (CatName1 == '') {
        msg += "\nPlease fill out Native language field!"
    }
    if (CatName2 == '') {
        msg += "\nPlease fill out English field!"
    }
    if (CatName3 == '') {
        msg += "\nPlease fill out Japanese field!"
    }
    if (TypeCode == '') {
        msg += "\nPlease choose Type field!"
    }
    if (checkExist(CatCode, id)) {
        msg += "\nCode already exist."
    }
    if (checkCode(CatCode)) {
        msg += "\nThe Code must contain letters uppercase and digits."
    }

    if (msg!="") {
        PopupModule.alertPopup(msg)
        return false;
    }

    return true;
}

function clearForm() {
    var f = $("#modalReportCategory")
    f.find(".inputID").val("")
    f.find(".inputCatCode").val("").attr("readonly", false)
    f.find(".inputCatName1").val("")
    f.find(".inputCatName2").val("")
    f.find(".inputCatName3").val("")
    f.find(".inputTypeCode").val("")
    f.find(".inputHideFlag").prop('checked', false)

}

function loadEditForm(id) {
    $.ajax({
        headers: {'X-CSRF-TOKEN': __csrfToken},
        url: __baseUrl + 'admin/config-category/search',
        type: 'post',
        data: {'ID': id},
        success: function (response) {
            var obj = response.obj

            var f = $("#modalReportCategory")
            f.find(".inputID").val(obj.ID)
            f.find(".inputCatCode").val(obj.CatCode).attr("readonly", true)
            f.find(".inputCatName1").val(obj.CatName1)
            f.find(".inputCatName2").val(obj.CatName2)
            f.find(".inputCatName3").val(obj.CatName3)
            f.find(".inputTypeCode").val(obj.TypeCode)
            f.find(".inputTypeCat").val(obj.TypeCat)
            if(obj.HideFlag == 1 ){
                f.find(".inputHideFlag").prop( "checked", true );
            }else{
                 f.find(".inputHideFlag").prop( "checked", false );
            }

            $('#modalReportCategory').modal()
        },
        error: function (response) {
            console.log(response)
        }
    })
}

function deleteObject(id, table) {
    $.ajax({
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/config-category/delete',
        type: 'post',
        data: { 'ID': id },
        success: function(response) {
            if (response.status) {
                table.ajax.reload();
                PopupModule.alertPopup('Deleted Successfully!')
            }
        },
        error: function(response) {
            console.log(response)
        }
    })
}

function checkExist(code, id) {
    var response = false

    var result = Object.keys(global_category_opt).map(key => ({ key, value: global_category_opt[key] }));
    for(var z = 0; z < result.length; z++) {
        var e = result[z]
        if (e.key == code && id == '') {
            response =  true;
        }
    }
    return response;
}

function checkCode(code) {
    if (!/[a-z]/.test(code) && /[A-Z][0-9]/.test(code)) {
        return false;
    }
    return true;
}

function updatesort(id, type){
     $.ajax({
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/config-category/updatesort',
        type: 'post',
        data: {
            'ID': id,
            'TYPE' : type
        },
        success: function(response) {
            if (response.status) {
                table.ajax.reload();
                PopupModule.alertPopup('Change Sort Successfully!')
            }
            if(response.status == 3){
                PopupModule.alertPopup('Position not change!')
            }
        },
        error: function(response) {
            console.log(response)
        }
    })
}

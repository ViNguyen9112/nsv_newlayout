var global_detail_opt = []
jQuery(document).ready(function () {
    //click Add Type
    $("#show-modal-config-type").click(function () {
        clearForm()
        $("#modalReportReport").modal()
    })

})

//click Submit
$(document).on('click', '#btnSubmitType', function() {
    var isValidate = isValidateForm()

    if (isValidate) {
        var f = $("#modalReportReport")
        if( f.find(".inputID").val()== ''){
            sKeyword = ''
            sType = ''
            sCategory =  f.find(".inputCatCode").val()
            $('#sType').val('')
            getAllCategoryByType('')
           
            
        }
        

        var data = {
            ID: f.find(".inputID").val(),
            DetailCode: f.find(".inputDetailCode").val(),
            DeName1: f.find(".inputDeName1").val(),
            DeName2: f.find(".inputDeName2").val(),
            DeName3: f.find(".inputDeName3").val(),
            CatCode: f.find(".inputCatCode").val(),
            ProcessFlag: f.find(".inputProcessFlag").is(":checked") ? 1 : 0
        }
        $.ajax({
            url: __baseUrl + 'admin/config-report/edit',
            headers: { 'X-CSRF-Token': __csrfToken },
            type: 'post',
            data: data,
            success: function(response) {
                if( f.find(".inputID").val()== ''){
                    sKeyword = ''
                    sType = ''
                    sCategory =  f.find(".inputCatCode").val()
                    $('#sType').val('')                    
                    $('#sCategory').val(sCategory)
                }
       
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

$(document).on('click', '.btnEditConfigReport', function() {
    let id = $(this).closest('tr').find("#ID").val()
    loadEditForm(id)
})

$(document).on('click', '.btnDeleteConfigReport', function() {
    let id = $(this).closest('tr').find("#ID").val()
    PopupModule.confirmPopuWithContent('', 'Are you sure you want to delete this item?').then((response) => {
        if (response) {
            deleteObject(id, table)
        }
    });
})
$(document).on('change', '#sType', function(e) {
    sType = $(this).val()
    sCategory = ''
    sDetailCode = ''
    getAllCategoryByType(sType)
    $('#filterStaff').click()
})
$(document).on('change', '#sCategory', function(e) {
    sCategory = $(this).val()
    sDetailCode = ''
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
var sType = ''
var sCategory =  ''
var sDetailCode = ''
var isDeleted = ''
var table = $('#serverDataTable').DataTable({
    "dom": '<"pull-left"fi>tp',
    "processing": true,
    "serverSide": true,
    "ajax": {
        headers: {'X-CSRF-TOKEN': __csrfToken},
        url: __baseUrl + 'admin/config-report',
        type: 'POST',
        data: function (d) {
            d.search.value = sKeyword;
            d.TypeCode     = sType;
            d.CatCode      = sCategory;
            d.DetailCode   = sDetailCode;
            d.isDeleted    = isDeleted;
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
        global_detail_opt = response.detail_opt
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
        { data: "DetailCode", "sClass": "text-left"},
        { data: "DeName2" , "sClass": "text-left work-pre"},
        { data: "DeName3" , "sClass": "text-left work-pre"},
        { data: "DeName1" , "sClass": "text-left work-pre"},
        { data: "CategoryWithName" , "sClass": "text-left"},
        {
            data: "DeSortNumber" ,
            "sClass": "text-canter",
            render: function(data, type, row) {
                if(row.DeSortNumber ==null){
                    return '';
                }
                return ""+
                    '<span class="numbersort">'+row.DeSortNumber+'</span>'+
                    '<svg xmlns="http://www.w3.org/2000/svg" row-id="'+row.ID +'" width="16"  height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">'+
                        '<path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>'+
                    '</svg>' +
                    ' <svg xmlns="http://www.w3.org/2000/svg" row-id="'+row.ID +'" width="16"  height="16" fill="currentColor" class="bi bi-arrow-down" viewBox="0 0 16 16">'+
                        '<path fill-rule="evenodd" d="M8 1a.5.5 0 0 1 .5.5v11.793l3.146-3.147a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 .708-.708L7.5 13.293V1.5A.5.5 0 0 1 8 1z"/>'+
                    '</svg>'
                }
        },
        {
            data: "ID",
            orderable: false,
            sClass: "text-center",
            render: function(data, type, row) {
                if (parseInt(row.FlagDelete) === 1) {
                    return ""
                }
                else {
                    return "" +
                        "<input type=\"hidden\" id=\"ID\" value=\"" + row.ID + "\">\n" +
                        "<button type=\"button\" class=\"btn btn-info btnEditConfigReport\"><i class=\"far fa-edit\"></i></button>\n" +
                        "<button type=\"button\" class=\"btn btn-danger btnDeleteConfigReport\"><i class=\"far fa-trash-alt\"></i></button>";
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
    var f = $("#modalReportReport")
    var DetailCode = f.find(".inputDetailCode").val()
    var DeName1 = f.find(".inputDeName1").val()
    var DeName2 = f.find(".inputDeName2").val()
    var DeName3 = f.find(".inputDeName3").val()
    var CatCode = f.find(".inputCatCode").val()
    var id = f.find(".inputID").val()
    var msg = ""
    if (DetailCode == '') {
        msg += "Please fill out Detail ID field!"
    }
    if (DeName1 == '') {
        msg += "\nPlease fill out Native language field!"
    }
    if (DeName2 == '') {
        msg += "\nPlease fill out English field!"
    }
    if (DeName3 == '') {
        msg += "\nPlease fill out Japanese field!"
    }
    if (CatCode == '') {
        msg += "\nPlease choose Category field!"
    }
    if (checkExist(DetailCode, id)) {
        msg += "\nCode already exist."
    }
    if (checkCode(DetailCode)) {
        msg += "\nThe Code must contain letters uppercase and digits."
    }

    if (msg!="") {
        PopupModule.alertPopup(msg)
        return false;
    }

    return true;
}

function clearForm() {
    var f = $("#modalReportReport")
    f.find(".inputID").val("")
    f.find(".inputDetailCode").val("").attr("readonly", false)
    f.find(".inputDeName1").val("")
    f.find(".inputDeName2").val("")
    f.find(".inputDeName3").val("")
    f.find(".inputCatCode").val("")
}

function loadEditForm(id) {
    $.ajax({
        headers: {'X-CSRF-TOKEN': __csrfToken},
        url: __baseUrl + 'admin/config-report/search',
        type: 'post',
        data: {'ID': id},
        success: function (response) {
            var obj = response.obj

            var f = $("#modalReportReport")
            f.find(".inputID").val(obj.ID)
            f.find(".inputDetailCode").val(obj.DetailCode).attr("readonly", true)
            f.find(".inputDeName1").val(obj.DeName1)
            f.find(".inputDeName2").val(obj.DeName2)
            f.find(".inputDeName3").val(obj.DeName3)
            f.find(".inputCatCode").val(obj.CatCode)

            $('#modalReportReport').modal()
        },
        error: function (response) {
            console.log(response)
        }
    })
}

function deleteObject(id, table) {
    $.ajax({
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/config-report/delete',
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

    var result = Object.keys(global_detail_opt).map(key => ({ key, value: global_detail_opt[key] }));
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

function getAllCategoryByType(type){
     $.ajax({
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/config-report/getAllCategoryByType',
        type: 'post',
        data: { 'TypeCode': type },
        success: function(response) {
            $('#sCategory').html('')
            $('#sCategory').append('<option value="">Please choose Category </option>');
            for (var key in response.obj) {
                var obj = response.obj[key];
                $('#sCategory').append('<option value="'+obj.CatCode+'">【'+obj.CatCode+'】'+obj.CatName1+'</option>');

            }
        },
        error: function(response) {
            console.log(response)
        }
    })
}
function updatesort(id, type){
     $.ajax({
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/config-report/updatesortDe',
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

//Get the button
var topBtn = document.getElementById("topBtn");
var downBtn = document.getElementById("downBtn");
// When the user scrolls down 20px from the top of the document, show the button
$('.form-report').scroll(function() {
    scrollFunction()
});

function scrollFunction() {
    if ($('.form-report').scrollTop() > 100) {
        topBtn.style.display = "block";
        downBtn.style.display = "none";
    } else {
        downBtn.style.display = "block";
        topBtn.style.display = "none";
    }
}

$(document).on('click', '#topBtn', function(e){
    e.preventDefault()
    topFunction()
})
$(document).on('click', '#downBtn', function(e){
    e.preventDefault()
    bottomFunction()
})
// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    $('.form-report').animate({
        scrollTop: 0
    }, 500);
    downBtn.style.display = "block";
    topBtn.style.display = "none";
}

function bottomFunction() {
    $('.form-report').animate({
        scrollTop: $('.form-report').prop("scrollHeight")
    }, 500);
    topBtn.style.display = "block";
    downBtn.style.display = "none";
}
// END BUTTON

function setFormReport(res){
    const data = res.report
    $(".textarea-report").val( data.Report );
    // type
    $('.type-report').html(data.TypeJP)
    $('#typeEN').val(data.TypeEN)
    $('#typeVN').val(data.TypeVN)
    $('#typeJP').val(data.TypeJP)
    // content report
    $('#reportEN').val(data.ReportEN)
    $('#reportVN').val(data.ReportVN)
    $('#reportJP').val(data.ReportJP)

    if(res.formCheck){
        const form = res.form
        const valuesChecked = data.checked
        console.log(data)
        var rows = ''
        const tplCheckbox = $('#tplCheckbox').html()
        const tplSecCheck = $('#tplSecCheck').html()
        const tplRadio    = $('#tplRadio').html()
        var secCheck = ''
        var checkboxs = ''
        var category = ''
        var haveCategory = 0

        var categoryJP = ''
        var categoryEN = ''
        var categoryVN = ''
        var list_radio = ''
        if (form === undefined) { // không có dữ liệu checkbox mà đúng
            $('.content-report').css('display', 'block')
            $('.form-report').css('display', 'none')
            return false;
        }
        else {
            $('.content-report').css('display', 'none')
            $('.form-report').css('display', 'block')
        }

        $.each(form, function(index, child){
            secCheck = '';
            checkboxs = '';
            category = index;
            haveCategory = (index != "") ? haveCategory + 1  : haveCategory;
            categoryJP = '';
            categoryEN = '';
            categoryVN = '';

            $.each(child,function(index, val){
                 var typeOfCate = val.TypeOfCate;

                if(val.CategoryJP != categoryJP){
                    secCheck = tplSecCheck.replace(/__category-jp__/g,val.CategoryJP)
                                            .replace(/__category-en__/g,val.CategoryEN)
                                            .replace(/__category-vn__/g,val.CategoryVN);
                    categoryJP = val.CategoryJP;
                }
                //set checked

                if(typeOfCate == 1){
                    var checked = "";
                    $.each(valuesChecked, function(index, value){
                        if(val.CheckID == value.CheckID && value.Result == '1'){
                            checked = "checked";
                        }
                    });
                    checkboxs += tplCheckbox.replace(/__id__/g,val.CheckID)
                                    .replace(/__checkcode__/g,val.CheckCode)
                                    .replace(/__detail-vn__/g,val.CheckPointVN)
                                    .replace(/__detail-en__/g,val.CheckPointEN)
                                    .replace(/__detail-jp__/g,val.CheckPointJP)
                                    .replace(/__checked__/g, checked);
                }

                if(typeOfCate == 3) {

                    $.each(valuesChecked, function(index, value){
                        if(val.CheckID == value.CheckID && value.Result == '1'){
                            checked_vn = "id='radio-check-vn-"+value.CheckID+"'";
                            checked_en = "id='radio-check-en-"+value.CheckID+"'";
                            checked_jp = "id='radio-check-jp-"+value.CheckID+"'";
                            list_radio += "#radio-check-vn-"+value.CheckID+',';
                            list_radio += "#radio-check-en-"+value.CheckID+',';
                            list_radio += "#radio-check-jp-"+value.CheckID+',';
                        }else{
                            checked_vn = "";
                            checked_en = "";
                            checked_jp = "";
                        }

                    });

                    name_vn    = 'radio-check-vn-'+val.idCat;
                    name_jp    = 'radio-check-jp-'+val.idCat;
                    name_en    = 'radio-check-en-'+val.idCat;

                    checkboxs += tplRadio.replace(/__id__/g,val.CheckID)
                                    .replace(/__checkcode__/g,val.CheckCode)
                                    .replace(/__detail-vn__/g,val.CheckPointVN)
                                    .replace(/__detail-en__/g,val.CheckPointEN)
                                    .replace(/__detail-jp__/g,val.CheckPointJP)
                                    .replace(/__checked-vn__/g, checked_vn)
                                    .replace(/__checked-en__/g, checked_en)
                                    .replace(/__checked-jp__/g, checked_jp)
                                    .replace(/__name-vn__/g, name_vn)
                                    .replace(/__name-en__/g, name_en)
                                    .replace(/__name-jp__/g, name_jp);
                }

                // if(typeOfCate == 2){
                //     checkboxs += tplRadio.replace(/__id__/g,val.CheckID)
                //                     .replace(/__checkcode__/g,val.CheckCode)
                //                     .replace(/__detail-vn__/g,val.CheckPointVN)
                //                     .replace(/__detail-en__/g,val.CheckPointEN)
                //                     .replace(/__detail-jp__/g,val.CheckPointJP)
                //                     .replace(/__checked__/g, checked)
                // }

            });
            secCheck = secCheck.replace(/__checkboxs__/g,checkboxs);
            rows += secCheck;
        })

        $("#checkreport").html(rows)
        list_radio = list_radio.substring(0, list_radio.length - 1)
        $(list_radio).prop("checked", true);
        if(haveCategory == 0){
            $('.category-report').css('display', 'none')
        }

    } else {
        $('.content-report').css('display', 'block')
        $('.form-report').css('display', 'none')
    }

    // scroll to top
    $('#topBtn').click()
}

$(document).ready(function(){
    $('.report-jp').on('click', function(){
        translateReport('JP')
    })
    $('.report-en').on('click', function(){
        translateReport('EN')
    })
    $('.report-vn').on('click', function(){
        translateReport('VN')
    })
})

function translateReport(lang){
    // type
    var type = "#type" + lang
    $('.type-report').html($(type).val())

    // content
    var content = "#report" + lang
    $('.textarea-report').val($(content).val())

    if(lang == 'VN'){
        $('.form-vn').css('display', 'block')
        $('.form-en').css('display', 'none')
        $('.form-jp').css('display', 'none')
    } else if(lang == 'EN'){
        $('.form-vn').css('display', 'none')
        $('.form-en').css('display', 'block')
        $('.form-jp').css('display', 'none')
    } else {
        $('.form-vn').css('display', 'none')
        $('.form-en').css('display', 'none')
        $('.form-jp').css('display', 'block')
    }
}

function appendImages(report_id, images){
    $('.files-preview').html('')
    var i = 0
    $.each(images, function(index,value){
        var tplImageUploaded = $('#tplImageUploaded').html()
        tplImageUploaded = tplImageUploaded
                .replace(/__id__/g, i)
                .replace(/__id-uploaded__/g,value.ID)
                .replace(/__src__/g, "../ImageReport/ID_" + report_id + "/" + value.ImageName)
        $('.files-preview').append(tplImageUploaded)

        i++
    })
    $('#currentIndexFiles').val(i)

}


// ================== MODAL SHOW IMAGE =====================
var modalID = ($('#eventModal').length) ? $('#eventModal') : $('#largeModal')

modalID.on('click', '.item-image', function(){
    var imagesTotal = $('.item-image').length
    // if($('#currentIndexFiles').val() != "-1"){
    //     imagesTotal = imagesTotal + Number($('#currentIndexFiles').val())
    // }

    // set image
    $('#venoboxImage').attr('src', $(this).attr('src'))
    var id = Number($(this).attr('data-id'))
    $('#venoboxImage').attr('data-id', id)

    // set index
    $('#currentImage').val(id)

    if(imagesTotal > 1){
        if(id == 0){
            $('#rightArrow').show()
            $('#leftArrow').hide()
        } else if(id == imagesTotal - 1){
            $('#leftArrow').show()
            $('#rightArrow').hide()
        } else {
            $('#leftArrow, #rightArrow').show()
        }
    } else {
        $('#leftArrow, #rightArrow').hide()
    }
    $('#viewImageModal').modal('show')

})

$('#leftArrow').on('click', function(){
    var id = Number($('#currentImage').val()) - 1
    $('#currentImage').val(id)

    $('.files-preview img').each(function(){
        if($(this).attr('data-id') == id){
            $('#venoboxImage').attr('src', $(this).attr('src'))
            $('#venoboxImage').attr('data-id', $(this).attr('data-id'))
        }
    })

    $('#rightArrow').show()
    if(id > 0) {
        $('#leftArrow').show()
    } else {
        $('#leftArrow').hide()
    }
})

$('#rightArrow').on('click', function(){
    var imagesTotal = $('.item-image').length
    // if($('#currentIndexFiles').val() != "-1"){
    //     imagesTotal = imagesTotal + Number($('#currentIndexFiles').val())
    // }
    var id = Number($('#currentImage').val()) + 1
    $('#currentImage').val(id)

    $('.files-preview img').each(function(){
        if($(this).attr('data-id') == id){
            $('#venoboxImage').attr('src', $(this).attr('src'))
            $('#venoboxImage').attr('data-id', $(this).attr('data-id'))
        }
    })

    $('#leftArrow').show()
    if(Number($('#currentImage').val()) < imagesTotal - 1) {
        $('#rightArrow').show()
    } else {
        $('#rightArrow').hide()
    }
})




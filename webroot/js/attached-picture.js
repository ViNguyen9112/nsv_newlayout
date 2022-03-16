var inputFile = $('#multiple_images');
var button = $('.fileinput-button');
var files = [];
var numberCurrent = 0

button.click(function() {
     inputFile.click();
     if($('#statusProgress').val() == 'uploaded'){
          $('.files-preview').html('')
          $('#statusProgress').val('')
     }
});

$(document).ready(function () {
    $('#multiple_images').on('change', function () {
        if ($(this).prop("files")) {
            var begin = numberCurrent
            var begin_group = numberCurrent
            if($('#currentIndexFiles').val() != "-1"){
                begin_group = begin_group + Number($('#currentIndexFiles').val())
            }
            var filesAmount = $(this).prop("files").length;

            for (i = 0; i < filesAmount; i++) {
                readImage($(this).prop("files")[i], i+begin, i+begin_group)
                files.push($(this).prop("files")[i]);
                numberCurrent++
            }
        }
    })
})

function readImage(file, index_select, index_group) {
     var listExtImage = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'tiff', 'bmp'];
     var name = file.name;
     var dotSeperate = name.lastIndexOf(".") + 1;
     var extFile = name.substr(dotSeperate, name.length).toLowerCase();
     /* reader images */
     var reader = new FileReader();
     reader.onload = function (event) {
          if ($.inArray(extFile, listExtImage) !== -1) {
               var tplPreviewImage = $('#tplPreviewImage').html()
               tplPreviewImage = tplPreviewImage
                    .replace(/__id-select__/g, index_select)
                    .replace(/__id__/g, index_group)
                    .replace(/__src__/g, event.target.result)
               $('.files-preview').append(tplPreviewImage)
          }
     }
     reader.readAsDataURL(file);
}

function formatBytes(bytes, decimals = 2) {
     if (bytes === 0) return '0 Bytes';
     const k = 1024;
     const dm = decimals < 0 ? 0 : decimals;
     const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
     const i = Math.floor(Math.log(bytes) / Math.log(k));
     return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
}

$(document).on('click', '.delete-image', function(e){
    e.preventDefault()
    var indexToRemove = $(this).attr('data-id');

    // reset values
    $('.files-preview .item-image').each(function(){
        if(Number($(this).attr('data-id')) > Number(indexToRemove)){
            var index = Number($(this).attr('data-id')) - 1
            $(this).attr('data-id', index)
        }
    })
    numberCurrent--

    $(this).closest('div').remove()
    //  if($('.delete-image').length){
    //       $(".files-preview").css('display','block')
    //     //   $(".part-show").css('display','none')
    //  } else {
    //       $(".files-preview").css('display','none')
    //  }
})

$(document).on('click', '.times-select', function(){
    var indexToRemove = $(this).attr('data-id-select');
    files.splice(indexToRemove, 1);

    // reset id select
    $('.files-preview .image-select').each(function(){
        if(Number($(this).attr('data-id-select')) > Number(indexToRemove)){
            var index = Number($(this).attr('data-id-select')) - 1
            $(this).attr('data-id-select', index)
        }
    })

    console.log(files)
})

// ================== MODAL SHOW IMAGE =====================
$('#reportModal').on('click', '.item-image', function(){
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
            $('.rightArrow-disabled').hide()
        } else if(id == imagesTotal - 1){
            $('#leftArrow').show()
            $('.leftArrow-disabled').hide()
        } else {
            $('#leftArrow, #rightArrow').show()
            $('.leftArrow-disabled, .rightArrow-disabled').hide()
        }
    } else {
        $('#leftArrow, #rightArrow').hide()
        // $('.leftArrow-disabled, .rightArrow-disabled').show()
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
    $('.rightArrow-disabled').hide()
    if(id > 0) {
        $('#leftArrow').show()
        $('.leftArrow-disabled').hide()
    } else {
        $('#leftArrow').hide()
        $('.leftArrow-disabled').show()
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
    $('.leftArrow-disabled').hide()
    if(Number($('#currentImage').val()) < imagesTotal - 1) {
        $('#rightArrow').show()
        $('.rightArrow-disabled').hide()
    } else {
        $('#rightArrow').hide()
        $('.rightArrow-disabled').show()
    }
})



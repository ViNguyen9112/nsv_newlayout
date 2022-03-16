$(document).ready(function(){
    // bootstrap datepicker
    $("#datepicker_filter_date").datepicker( {
        format: 'yyyy/mm/dd',
        autoclose: true,
        endDate: '0d'
    });

    $('#divImages .pic-face').venobox({ closeBackground: 'rgba(68,68,68,0)', frameheight: '600' });

    $('#sortDate').on('click', function(){
        const folderid = window.location.href.split('/gallery-')[1]

        var sort = ''
        if($(this).data('order') == 'DESC'){
            $(this).data('order', 'ASC')
            $(this).html('<i class="far fa-caret-square-up"></i>')
            sort = 'ASC'
        } else {
            $(this).data('order', 'DESC')
            $(this).html('<i class="far fa-caret-square-down"></i>')
            sort = 'DESC'
        }

        $.ajax({
            headers: {'X-CSRF-TOKEN': __csrfToken},
            url: __baseUrl + 'admin/gallery/sort',
            type:'POST',
            data:{'sort': sort,'folderid': folderid},
            success:function(res){
                if(res.data){
                    resetHTMLGallery(res.data)
                }
            }
        })
    })

    $("#datepicker_filter_date").on('change', function(){
        const date = $(this).val()
        $.ajax({
            headers: {'X-CSRF-TOKEN': __csrfToken},
            url: __baseUrl + 'admin/gallery/filter',
            type:'POST',
            data:{'date': date},
            success:function(res){
                resetHTMLGallery(res.data)
            }
        })
    })
})

function resetHTMLGallery(data){
    $("#divImages").html('')
    // images
    var rows = ''
    const tplImage = $('#tplImage').html()
    const tplSecImage = $('#tplSecImage').html()

    var secImage = ''
    var images = ''
    var date = ''

    $.each(data, function(index, child){
        secImage = ''
        images = ''
        date = index
        secImage = tplSecImage.replace(/__date__/g,date)
        $.each(child,function(index, val){
            images += tplImage.replace(/__src__/g,__baseUrl + val.Source + val.Name)
                                .replace(/__date__/g,date)
                                .replace(/__type__/g,val.Type)
                                .replace(/__time__/g,val.Time)
                                .replace(/__area__/g,val.Area)
                                .replace(/__customer__/g,val.Customer)
        })
        secImage = secImage.replace(/__images__/g,images)

        rows += secImage
    })

    $("#divImages").html(rows)
    $('#divImages .pic-face').venobox({ closeBackground: 'rgba(68,68,68,0)', frameheight: '600' });
}

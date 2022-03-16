$(document).ready(function(){
    // ====================== MAIN FOLDER ===============================
    $('#mainFolder').on('click', '.folder-image', function(){
        if($(this).data('class') == 'normal'){
            $(this).data('class', 'overlay')
            $(this).parent().addClass('folder-overlay')
        } else {
            $(this).data('class', 'normal')
            $(this).parent().removeClass('folder-overlay')
        }

        var items = 0;
        var both = 0;
        $('#mainFolder .folder-overlay').each(function(){
            items += 1;
            both += 1;
        })
        $('#subFolder .folder-overlay').each(function(){
            both += 1;
        })

        if(items > 0){
            $('#copyFolder, #cutFolder, #shortcutFolder').css('display','inline')
        } else {
            $('#copyFolder, #cutFolder, #shortcutFolder').css('display','none')
        }

        if(both > 0){
            $('#deleteFolder, #downloadFolder').css('visibility', 'visible')
        } else {
            $('#deleteFolder, #downloadFolder').css('visibility', 'hidden')
        }
    })

    $('#mainFolder').on('dblclick', '.folder-image', function(){
        location.href = __baseUrl + 'admin/face-image/gallery-' + $(this).data('id')
    })

    $('#downSort').on('click', function(){
        if($(this).data('display') == 'none'){
            $(this).data('display', 'block')
            $(this).removeClass('disable-color')
        }

        $('#upSort').data('display', 'none')
        $('#upSort').addClass('disable-color')

        $.ajax({
            headers: {'X-CSRF-TOKEN': __csrfToken},
            url: __baseUrl + 'admin/faceImage/sort',
            type:'POST',
            data:{'sort': 'ASC'},
            success:function(res){
                if(res.folders){
                    $("#mainFolder").html('')
                    const tplFolder = $('#tplFolder').html()
                    var rows = ''
                    $.each(res.folders,function(index,value){
                        rows += tplFolder.replace(/__folderid__/g, value.ID)
                                        .replace(/__name__/g, value.Name)
                                        .replace(/__class__/g, "")
                    })
                    $("#mainFolder").append(rows)
                }
            }
        })
    })

    $('#upSort').on('click', function(){
        if($(this).data('display') == 'none'){
            $(this).data('display', 'block')
            $(this).removeClass('disable-color')
        }

        $('#downSort').data('display', 'none')
        $('#downSort').addClass('disable-color')

        $.ajax({
            headers: {'X-CSRF-TOKEN': __csrfToken},
            url: __baseUrl + 'admin/faceImage/sort',
            type:'POST',
            data:{'sort': 'DESC'},
            success:function(res){
                if(res.folders){
                    $("#mainFolder").html('')
                    const tplFolder = $('#tplFolder').html()
                    var rows = ''
                    $.each(res.folders,function(index,value){
                        rows += tplFolder.replace(/__folderid__/g, value.ID)
                                        .replace(/__name__/g, value.Name)
                                        .replace(/__class__/g, "")
                    })
                    $("#mainFolder").append(rows)
                }
            }
        })
    })

    //  ============================= SUB FOLDER ============================
    $('#subFolder').on('click', '.sub-folder-image', function(){
        if($(this).data('class') == 'normal'){
            $(this).data('class', 'overlay')
            $(this).parent().addClass('folder-overlay')
        } else {
            $(this).data('class', 'normal')
            $(this).parent().removeClass('folder-overlay')
        }
        var items = 0;
        var both = 0;
        $('#subFolder .folder-overlay').each(function(){
            items += 1;
            both += 1;
        })

        $('#mainFolder .folder-overlay').each(function(){
            both += 1;
        })

        if(items > 0){
            $('#renameFolder').css('display', 'inline')
        } else {
            $('#renameFolder').css('display', 'none')
        }

        if(both > 0){
            $('#deleteFolder, #downloadFolder').css('visibility', 'visible')
        } else {
            $('#deleteFolder, #downloadFolder').css('visibility', 'hidden')
        }
    })

    $('#subFolder').on('dblclick', '.sub-folder-image', function(){
        var id = $(this).data('id')
        var name = $(this).data('name')
        $('#openSubFolder').val(id)
        $('#nameSubFolder').html(name)
        $.ajax({
            headers: {'X-CSRF-TOKEN': __csrfToken},
            url: __baseUrl + 'admin/faceImage/getChildrenFolder',
            type: 'POST',
            data:{'id':id},
            success:function(res){
                $('#inSubFolder').css('display','inline')
                $('#newFolder').css('display','none')
                $('#deleteFolder, #downloadFolder').css('visibility', 'hidden')
                $('#renameFolder').css('display', 'none')

                if($('#statusTool').val() != 'default'){
                    $('#pasteFolder').css('display','inline')
                }

                if(res.children){
                    $('#childrenFolder').html('')

                    const tplFolder = $('#tplFolder').html()
                    const tplShortcutFolder = $('#tplShortcutFolder').html()

                    var rows = ''
                    $.each(res.children,function(index,value){
                        if(value.Reason == 'shortcuted'){
                            rows += tplShortcutFolder.replace(/__folderid__/g, value.ID)
                                        .replace(/__name__/g, value.Name)
                                        .replace(/__class__/g, "")
                        } else {
                            rows += tplFolder.replace(/__folderid__/g, value.ID)
                                        .replace(/__name__/g, value.Name)
                                        .replace(/__class__/g, "")
                        }
                    })
                    if(rows == ''){
                        $('#childrenFolder').append('<div class="col-12 text-center"><h5>This folder is empty</h5></div>')
                    } else {
                        $('#childrenFolder').append(rows)
                    }

                    $('#subFolder').css('display', 'none')
                    $('#childrenFolder').css('display','flex')
                }
            }
        })
    })

    $('#newFolder').on('click', function(){
        $.ajax({
            headers: {'X-CSRF-TOKEN': __csrfToken},
            url: __baseUrl + 'admin/faceImage/newFolder',
            type: 'GET',
            success:function(res){
                if(res.newFolder){
                    const data = res.newFolder
                    const tplSubFolder = $('#tplSubFolder').html()
                    row = tplSubFolder.replace(/__folderid__/g, data.ID)
                                        .replace(/__name__/g, data.Name)
                                        .replace(/__class__/g, "folder-overlay")
                    $('#subFolder').append(row)
                    $('#renameFolder').css('display', 'inline')
                }
            }
        })
    })

    $('#upParent').on('click', function(){
        $('#pasteFolder').css('display','none')
        $.ajax({
            headers: {'X-CSRF-TOKEN': __csrfToken},
            url: __baseUrl + 'admin/faceImage/getSubFolder',
            type: 'GET',
            success:function(res){
                $('#inSubFolder').css('display','none')
                $('#newFolder').css('display','block')
                if(res.listFolder){
                    $('#subFolder').html('')
                    const tplSubFolder = $('#tplSubFolder').html()
                    var rows = ''
                    $.each(res.listFolder, function(index, value){
                        rows += tplSubFolder.replace(/__folderid__/g, value.ID)
                                        .replace(/__name__/g, value.Name)
                                        .replace(/__class__/g, "")
                    })
                    $('#subFolder').append(rows)
                    $('#subFolder').css('display', 'flex')
                    $('#childrenFolder').css('display','none')
                }
            }
        })
    })

    // ========================== TOOL =========================

    // TODO: array sub folder ->download contains folders inside
        // (Type=>Copy (as zip folders Copy,Cut))

    $('#refreshFolder').on('click', function(e){
        e.preventDefault()
        $('#downSort').click()
        $('#upParent').click()
        $('#renameFolder').css('display', 'none')
        $('#deleteFolder, #downloadFolder').css('visibility', 'hidden')
        $('#copyFolder, #cutFolder, #shortcutFolder').css('display','none')
        $(this).blur()
    })

    $('#deleteFolder').on('click', function(e){
        swal({
            title: "Are you sure you want to delete folders?",
            icon: "error",
            buttons: true,
            dangerMode: true,
        })
        .then((willDelete) => {
            if(willDelete){
                var arrMainFolderDelete = []
                $('#mainFolder .folder-overlay').each(function(){
                    arrMainFolderDelete.push($(this).children().data('id'))
                })
                var arrSubFolderDelete = []
                $('#subFolder .folder-overlay').each(function(){
                    arrSubFolderDelete.push($(this).children().data('id'))
                })
                $.ajax({
                    headers: {'X-CSRF-TOKEN':__csrfToken},
                    url: __baseUrl + 'admin/faceImage/deleteFolder',
                    type: 'POST',
                    data: {
                        'MainFolder':  arrMainFolderDelete,
                        'SubFolder':  arrSubFolderDelete,
                    },
                    success:function(res){
                        if(res.success){
                            $('#refreshFolder').click()
                        }
                    }
                })
            }
        })
    })

    $('#downloadFolder').on('click', function(e){
        var arrMainFolderDelete = []
        $('#mainFolder .folder-overlay').each(function(){
            arrMainFolderDelete.push($(this).children().data('id'))
        })
        var arrSubFolderDelete = []
        $('#subFolder .folder-overlay').each(function(){
            arrSubFolderDelete.push($(this).children().data('id'))
        })
        $.ajax({
            headers: {'X-CSRF-TOKEN':__csrfToken},
            url: __baseUrl + 'admin/faceImage/downloadFolder',
            type: 'POST',
            data: {
                'MainFolder':  arrMainFolderDelete,
                'SubFolder':  arrSubFolderDelete,
            },
            success:function(res){
                if(res.success){
                    window.open(
                        __baseUrl + res.File,
                        "_blank"
                    )
                }
            }
        })
    })

    $('#copyFolder').on('click', function(e){
        e.preventDefault()
        processFolderOverlay('copied')
    })

    $('#cutFolder').on('click', function(e){
        e.preventDefault()
        processFolderOverlay('cutted')
    })

    $('#shortcutFolder').on('click', function(e){
        e.preventDefault()
        processFolderOverlay('shortcuted')
    })

    var arrDuplicateFolder = []
    $('#pasteFolder').on('click', function(e){
        e.preventDefault()
        $('.folder-overlay').each(function(){
            $(this).removeClass('folder-overlay')
        })

        var parentFolder = $('#openSubFolder').val()
        var arrayFolderID = $('#arrayFolderID').val()
        var arrayFolderName = $('#arrayFolderName').val()
        var statusTool = $('#statusTool').val()

        var _arrFolderName = arrayFolderName.split(',')
        var _arrFolderID = arrayFolderID.split(',')

        // names
        var arrOverlayNames = []
        _arrFolderName.forEach(function(item, index){
            val = {}
            val['ID'] = index
            val['Name'] = item
            arrOverlayNames.push(val)
        })
        // ids
        var arrOverlayIds = []
        _arrFolderID.forEach(function(item, index){
            val = {}
            val['ID'] = index
            val['OverlayID'] = item
            arrOverlayIds.push(val)
        })
        // ==> overlay
        var arrOverlayFolder = arrOverlayNames.map((item, i) => Object.assign({}, item, arrOverlayIds[i]));
        //  ==> exist
        if(statusTool != 'shortcuted'){
            var arrExistFolder = []
            $('#childrenFolder .folder-image').each(function(index){
                val = {}
                val['ID'] = -1
                val['Name'] = $(this).data('name')
                val['ExistID'] = $(this).data('id')
                arrExistFolder.push(val)

            })

            const mergeByName = (a1, a2) =>
                a1.map(itm => ({
                    ...a2.find((item) => (item.Name === itm.Name) && item),
                    ...itm
                }));

            // ======== merge ========
            var arrMergeFolder = mergeByName(arrOverlayFolder, arrExistFolder)

            var arrValidFolder = []
            $.each(arrMergeFolder, function(index, value){
                if(value.ExistID){
                    arrDuplicateFolder.push(value)
                } else {
                    arrValidFolder.push(value)
                }
            })
        } else {
            var arrValidFolder = arrOverlayFolder
        }

        // paste function
        if(arrValidFolder.length){
            $.ajax({
                headers: {'X-CSRF-TOKEN':__csrfToken},
                url: __baseUrl + 'admin/faceImage/pasteFolder',
                type: 'POST',
                data: {
                    'parentFolder':  parentFolder,
                    'validFolder': arrValidFolder,
                    'statusTool': statusTool
                },
                success:function(res){
                    var errors = []
                    if(res.arrFolderError){
                        $.each(res.arrFolderError, function(index, value){
                            console.log(value)
                        })
                    }

                    const tplFolder = $('#tplFolder').html()
                    const tplShortcutFolder = $('#tplShortcutFolder').html()

                    // if cutted then update mainFolder
                    if(statusTool == 'cutted'){
                        $("#mainFolder").html('')

                        var rows = ''
                        $.each(res.mainFolder,function(index,value){
                            rows += tplFolder.replace(/__folderid__/g, value.ID)
                                            .replace(/__name__/g, value.Name)
                                            .replace(/__class__/g, "")
                        })
                        $("#mainFolder").append(rows)
                    }

                    if(res.listFolder){
                        var rows = ''
                        $.each(res.listFolder,function(index,value){
                            if(value.Reason == 'shortcuted'){
                                rows += tplShortcutFolder.replace(/__folderid__/g, value.ID)
                                            .replace(/__name__/g, value.Name)
                                            .replace(/__class__/g, "folder-overlay")
                            } else {
                                rows += tplFolder.replace(/__folderid__/g, value.ID)
                                            .replace(/__name__/g, value.Name)
                                            .replace(/__class__/g, "folder-overlay")
                            }
                        })
                        $('#childrenFolder').append(rows)
                    }
                }
            })
        }

        // merge / replace
        // 'This destination already contains a folder named [name]'
        // 'Do you want to <br> mrege - replace - cancel'
        if(statusTool != "shortcuted"){
            if(arrDuplicateFolder.length){
                var names = ''
                $.each(arrDuplicateFolder, function(index, value){
                    if(index < arrDuplicateFolder.length - 1){
                        names += value.Name + ", "
                    } else {
                        names += value.Name + "."
                    }
                })
                $('#nameFolderDuplicate').html(names)
                $('#askFolderModal').modal()
            }
        }

    })

    $('#replaceFolder').on('click', function(e){
        e.preventDefault()
        processFolderDuplicate(arrDuplicateFolder, 'replaceFolder', 'Replaced successfully!')
        arrDuplicateFolder = []
    })

    $('#mergeFolder').on('click', function(e){
        e.preventDefault()
        processFolderDuplicate(arrDuplicateFolder, 'mergeFolder', 'Merged successfully!')
        arrDuplicateFolder = []
    })

    jQuery("#renameFolder").on('click', function(e){
        e.preventDefault()
        const subFolder = document.getElementById("subFolder").getElementsByClassName('sub-folder folder-overlay')[0]
        const id = subFolder.querySelector('.id-folder')
        const name = subFolder.querySelector('.name-folder')
        name.innerHTML = '<input type="text" style="width:100px" id="ipNameSubFoldelRename" value="'+ name.querySelector("p").innerHTML +'">'
                        + '<input type="hidden" id="ipIDSubFolderRename" value="'+ id.value +'">'
        document.getElementById("ipNameSubFoldelRename").focus()
        document.getElementById("ipNameSubFoldelRename").select()
    })

    $('#subFolder').on('blur','#ipNameSubFoldelRename', function(){
        const id = $('#ipIDSubFolderRename').val()
        const name = $(this).val()

        $.ajax({
            headers: {'X-CSRF-TOKEN':__csrfToken},
            url: __baseUrl + 'admin/faceImage/renameFolder',
            type: 'POST',
            data: {
                'ID':  id,
                'Name': name
            },
            success:function(res){
                if(res.newName){
                    var text = '<p>'+ res.newName +'</p>'
                    $('#ipNameSubFoldelRename').parent().html(text)
                }
            }
        })
    })
})



function processFolderOverlay(type){
    // set statusTool
    $('#statusTool').val(type)
    // push value to arrayFolderID
    var ids = [];
    // push value to arrayFolderName
    var names = [];
    $('#mainFolder .folder-overlay').each(function(){
        // ids
        var id = $(this).children().data('id')
        ids.push(id)
        // names
        var name = $(this).children().data('name')
        names.push(name)
    })
    // append
    $('#arrayFolderID').val(ids)
    $('#arrayFolderName').val(names)
    //display Paste
    if($('#openSubFolder').val() != 0){
        $('#pasteFolder').css('display','inline')
    }
}

function processFolderDuplicate(arrDuplicateFolder, urlFunction, sentence){
    $('#askFolderModal').modal('hide')

    const statusTool = $('#statusTool').val()
    const parentFolder = $('#openSubFolder').val()
    $.ajax({
        headers: {'X-CSRF-TOKEN':__csrfToken},
        url: __baseUrl + 'admin/faceImage/' + urlFunction,
        type: 'POST',
        data: {
            'parentFolder':  parentFolder,
            'duplicateFolder':  arrDuplicateFolder,
            'statusTool': statusTool
        },
        success:function(res){
            if(res.success == 1){
                swal({
                    title: 'Face Image',
                    text: sentence,
                    icon: 'success'
                })
                setTimeout(function(){
                    swal.close()
                },3000)
            }
        }
    })
}

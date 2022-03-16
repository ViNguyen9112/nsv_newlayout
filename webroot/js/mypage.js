//Get the button
var topBtn = document.getElementById("topBtn");
var downBtn = document.getElementById("downBtn");
// When the user scrolls down 20px from the top of the document, show the button
$('#div-modal').scroll(function() {
    scrollFunction()
});
scrollingElement = (document.scrollingElement || document.body)

function scrollFunction() {
    if ($('#div-modal').scrollTop() > 20) {
        topBtn.style.display = "block";
        downBtn.style.display = "none";
    } else {
        downBtn.style.display = "block";
        topBtn.style.display = "none";
    }
}
// When the user clicks on the button, scroll to the top of the document
function topFunction() {
    $('#div-modal').animate({
        scrollTop: 0
    }, 500);
    downBtn.style.display = "block";
    topBtn.style.display = "none";
}

function bottomFunction() {
    $('#div-modal').animate({
        scrollTop: $('#div-modal').prop("scrollHeight")
    }, 500);
    topBtn.style.display = "block";
    downBtn.style.display = "none";
}
// END BUTTON

$(document).ready(function(){
    // $('#CurrentLocation').on('click',function(e){
    //     e.preventDefault()
    //     initializeMap()
    // })

    $('#Area').on('change', function(){
        if($('#onloadArea').length){
            $('#onloadArea').remove()
        }
        // clear checkin/out , report
        $('#TimeCheckin').html('')
        $('#TimeCheckout').html('')
        $('#ContentReport').val('')
        if($(this).val() != "-1"){
            // append customer
            // $("#Area option[value='-1']").remove()
            appendCustomer()
            setTimeout(function(){
                checkTimecardOfCustomer()
            },1000)
        } else {
            $('#CustomerName').html('<option value="-1"></option>')
        }
    })

    $('#CustomerName').on('change',function(){
        if($(this).val() != "-1"){
            checkTimecardOfCustomer()
        }
    })

    $('#CheckIn').on('click', function(e){
        e.preventDefault()
        LoadingModule.showLoading()
        initializeMap().then(function(currentPosition) {
            var allow_location = ($('#currentCoord').val() != "none") ? true : false
            if(allow_location){
                $(this).attr('disabled', true)
                if($('#Area').val() == "-1"){
                    // TODO:^language
                    alert($('#text_please_choose_area').val())
                    $(this).attr('disabled', false)
                } else {
                    if($('#CustomerName').val() == "-1"){
                        // TODO:^language
                        alert($('#text_please_choose_customer').val())
                        $(this).attr('disabled', false)
                    } else {
                        checkIn()
                    }
                }
            } else {
                swal({
                    title: '',
                    text: $("#text_enable_location").val(),
                    button: 'OK'
                })
            }
        }).catch(function(error) {
            console.log(error);
            swal({
                title: '',
                text: $("#text_enable_location").val(),
                button: 'OK'
            })
        }).finally(function() {
            LoadingModule.hideLoading()
        })
    })

    $('#Report').on('click',function(e){
        e.preventDefault()
        if($('#Area').val() == "-1"){
            // TODO:^language
            alert($('#text_please_choose_area').val())
        } else {
            if($('#CustomerName').val() == "-1"){
                // TODO:^language
                alert($('#text_please_choose_customer').val())
            } else {
                validateReport()
            }
        }
    })

    $('#ClearReport').on('click', function(e){
        e.preventDefault()
        $('#ContentReport').val('')
        $('#NoteReport').val('')
        if($('.checkbox-report').length){
            $('.checkbox-report').prop('checked', false)
        }
        $('#previewImages').html('')
        files = []
    })

    $('#ContentReport').keyup(function(e){
        $('#ContentReport').css({'border' : '2px solid #ccc'});
        $('.error-note').text('');
    });

    $('#NoteReport').keyup(function(e){
        $('#NoteReport').css({'border' : '2px solid #ccc'});
        $('.error-note').text('');
    });

    $('#SubmitReport').on('click', function(e){
        e.preventDefault();
        $(this).attr('disabled', true);
        if($('.not-check').css('display') == 'block') {
            let content = $('#ContentReport').val().trim();
            let lines = content.split(/\r|\r\n|\n/);
            let count = lines.length;

            if ($('#TypeReport').val() == null) {
                let msg0 = $('#msg_error_user_report_0').val();
                $('.error-type').text(msg0);
                $(this).attr('disabled', false);
            } else if (content == "") {
                // TODO:^language
                //alert($('#text_required_report').val());
                $('.error-note').text($('#text_required_report').val());
                $('#ContentReport').css({'border' : '2px solid red'});
                $(this).attr('disabled', false);
            } else {
                if (count < 6) {
                    $('#ContentReport').css({'border' : '2px solid red'});
                    var messError = $('#text_required_note_count_line').val();
                    $('.error-note').text(messError);
                    $(this).attr('disabled', false);
                } else {

                    let result = validateContent(content);
                    if (result.status == false) {
                        $('#ContentReport').css({'border' : '2px solid red'});
                        $('.error-note').text(result.msg);
                        $(this).attr('disabled', false);
                    } else {
                        $('#ContentReport').css({'border' : '2px solid #ccc'});
                        // reset message
                        $('.error-note').text('');
                        insertReport();
                    }
                }
            }
        } else {
            let content = $('#NoteReport').val().trim();
            let lines = content.split(/\r|\r\n|\n/);
            let count = lines.length;

            if ($('#TypeReport').val() == null) {
                let msg0 = $('#msg_error_user_report_0').val();
                $('.error-type').text(msg0);
                $(this).attr('disabled', false);
            } else if (content == "") {
                // TODO:^language
                //alert($('#text_required_report').val());
                $('.error-note').text($('#text_required_report').val());
                $('#NoteReport').css({'border' : '2px solid red'});
                $(this).attr('disabled', false);
            } else {
                if (count < 6) {
                    $('#NoteReport').css({'border' : '2px solid red'});
                    var messError = $('#text_required_note_count_line').val();
                    $('.error-note').text(messError);
                    $(this).attr('disabled', false);
                } else {
                    let result = validateContent(content);
                    if (result.status == false) {
                        $('#NoteReport').css({'border' : '2px solid red'});
                        $('.error-note').text(result.msg);
                        $(this).attr('disabled', false);
                    } else {
                        $('#NoteReport').css({'border' : '2px solid #ccc'});
                        // reset message
                        $('.error-note').text('');
                        insertReport();
                    }
                }
            }
        }

    });

    $('#CheckOut').on('click',function(e){
        e.preventDefault()
        LoadingModule.showLoading()
        initializeMap().then(function(currenPosition) {
            var allow_location = ($('#currentCoord').val() != "none") ? true : false
            if(allow_location){
                $(this).attr('disabled', true)
                if($('#Area').val() == "-1"){
                    // TODO:^language
                    alert($('#text_please_choose_area').val())
                    $(this).attr('disabled', false)
                } else {
                    if($('#CustomerName').val() == "-1"){
                        // TODO:^language
                        alert($('#text_please_choose_customer').val())
                        $(this).attr('disabled', false)
                    } else {
                        checkOut()
                    }
                }
            } else {
                swal({
                    title: '',
                    text: $("#text_enable_location").val(),
                    button: 'OK'
                })
            }
        }).catch(function(error) {
            console.log(error)
            swal({
                title: '',
                text: $("#text_enable_location").val(),
                button: 'OK'
            })
        }).finally(function() {
            LoadingModule.hideLoading()
        })
    })

    $('#CaptureCamera').on('click',function(e){
        e.preventDefault()
        $('#lookCameraModal').modal('hide')
        if($(this).data('type') == 'checkin'){
            $('#btnInsertCheckin').click()
        } else {
            $('#btnInsertCheckout').click()
        }
    })

    $('#WorkingCalendar').on('click',function(e){
        e.preventDefault()
        location.href = __baseUrl + 'calendar'
    })

    $('#TypeReport').on('change', function(){
        if ($('#TypeReport').val() == null) {
            let msg0 = $('#msg_error_user_report_0').val();
            $('.error-type').text(msg0);
        } else {
            $('.error-type').text('');
        }

        $('#ContentReport').css({'border' : '2px solid #ccc'});
        $('#NoteReport').css({'border' : '2px solid #ccc'});
        $('.error-note').text('');
        if($('#onloadTypeReport').length){
            $('#onloadTypeReport').remove()
        }
        resetFieldReport()
    })

    $(document).on('click', '.checkbox-report', function() {
        var $radio = $(this);
        var name_radio = $radio.attr('name');

        // if this was previously checked
        if ($radio.data('waschecked') == true)
        {
            $radio.prop('checked', false);
            $radio.data('waschecked', false);
        }
        else
            $radio.data('waschecked', true);

        // remove was checked from other radios
        $radio.siblings('input[name="'+name_radio+'"]').data('waschecked', false);

        postCheckbox($('#IDTimeCard').val(), $('#TypeReport').val(), $(this).val(), $(this).prop('checked'))
    });
})

function validateContent(content) {
    let msg1 = $('#msg_error_user_report_1').val();
    // xử lý nếu bạn nhập quá nhiều tin nhắn giống nhau
    let arrKeywords = [];
    let arrContents = content.split(/\r|\r\n|\n/);
    $.each(arrContents, function( index, value ) {
        let itemContent = value.split(/,| /);
        $.each(itemContent, function( ind, val ) {
            if (val.trim() != '') {
                arrKeywords.push(val);
            }
        });
    });

    let _length = arrKeywords.length;
    if (_length <= 10 ) {
        return {
            'msg': msg1,
            'status':false
        };
    } else {
        // xử lý nếu nhập quá nhiều ksy tự giống nhau
        let arrUnique = arrKeywords.filter(function(item, i, arrKeywords) {
            return i == arrKeywords.indexOf(item);
        });

        if (arrUnique.length <= 6) {
            return {
                'msg':msg1,
                'status':false
            };
        }
    }

    return {
        'msg':'success',
        'status':true
    };
}

function getListArea(){
    $.ajax({
        url: __baseUrl + 'mypage/getArea',
        type: 'get',
        success: function(response){
            var list = ''
            $.each(response.areas, function(index, value){
                list +=  '<option value="'+ value.AreaID +'">'+ value.Name +'</option>'
            })
            $('#Area').html(list)
        }
    })
}

function appendCustomer(){
    $.ajax({
        headers: {'X-CSRF-TOKEN': csrfToken},
        url: __baseUrl + 'mypage/getCustomer',
        type: 'post',
        data: {AreaID: $('#Area').val()},
        success: function(response){
            var list = ''
            $.each(response, function(index, value){
                list +=  '<option value="'+ value.CustomerID +'">'+ value.Name +'</option>'
            })
            $('#CustomerName').html(list)
        }
    })
}

function checkTimecardOfCustomer(){
    $.ajax({
        headers: {'X-CSRF-TOKEN': csrfToken},
        url: __baseUrl + 'mypage/checkTimecardOfCustomer',
        type:'POST',
        data: {'customerID': $('#CustomerName').val()},
        success:function(res){
            if(res.timecard){
                $('#TimeCheckin').html(res.timeCheckin)
                $('#TimeCheckout').html(res.timeCheckout)
            } else {
                $('#TimeCheckin').html('')
                $('#TimeCheckout').html('')
            }
        }
    })
}

function initializeMap() {
    return new Promise((resolve, reject) => {
        // Additional option, increase accuracy - 20211120
        var options = {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        };

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, showError, options);
        } else {
            alert("Geolocation is not supported by this browser.")
        }

        function showPosition(position) {
            console.log(position)
            let lat = position.coords.latitude
            let lng =  position.coords.longitude
            $('#currentCoord').val(lat + "," + lng)
            resolve({lat: lat, long: lng})
        }

        function showError(error) {
            reject(error)
            console.log(error)
        }
    })
    
}

function checkIn(){
    $.ajax({
        headers: {'X-CSRF-TOKEN': csrfToken},
        url: __baseUrl + 'mypage/validateCheckin',
        type: 'POST',
        data: {customerID: $('#CustomerName').val()},
        success: function(res){
            $('#CheckIn').attr('disabled', false)
            if(res.valid){
                $('#CaptureCamera').data('type','checkin')
                $('#lookCameraModal').modal()
            } else {
                if(res.same_area == 1){
                    swal({
                        // TODO:^language
                        title: $('#text_check_in').val(),
                        text: $('#text_you_checked_in').val().replace("TIME", res.timeCheckin),
                        icon: 'info'
                    })
                } else {
                    swal({
                        // TODO:^language
                        title: $('#text_check_in').val(),
                        text: $('#text_not_checked_out').val() + '\n' + res.customerName + '(' + res.areaName + ')',
                        icon: 'info',
                        button: 'OK'
                        // buttons:true,
                        // dangerMode: true,
                    })
                    .then((continueCheckin) => {
                        if(continueCheckin){
                            $('#Area').val(res.areaID)
                            appendCustomer()
                            setTimeout(function(){
                                $('#CustomerName').val(res.customerID)
                            },500)
                            $('#TimeCheckin').html(res.timeCheckin)
                        }
                    })
                }
            }
        }
    })
}

function validateReport(){
    $.ajax({
        headers: {'X-CSRF-TOKEN': csrfToken},
        url: __baseUrl + 'mypage/validateReport',
        type: 'POST',
        data: {customerID:$('#CustomerName').val()},
        success:function(res){
            if(res.NullCheckin){
                //TODO:^language
                swal({
                    title: $('#text_report').val(),
                    text: $('#text_not_checked_in').val(),
                    icon: 'error'
                })
            } else {
                // refresh form
                $('#ContentReport').val('')
                $('#ContentReport').attr('placeholder',$('#text_required_report').val())
                $('#NoteReport').val('')
                $('#NoteReport').attr('placeholder',$('#text_required_report').val())
                $('#previewImages').html('')
                files = []

                var options = ''
                if($('#onloadTypeReport').length < 1){
                    options += '<option value="-1" id="onloadTypeReport" selected></option>'
                }
                options += $('#TypeReport').html()
                $('#TypeReport').html(options)

                $('#TypeSubmit').val(res.TypeSubmit)
                $('#IDReport').val(res.IDReport)
                $('#IDTimeCard').val(res.IDTimeCard)

                if(res.TypeCode){
                    if($('#onloadTypeReport').length){
                        $('#onloadTypeReport').remove()
                    }
                    $('#TypeReport').val(res.TypeCode)
                    resetFieldReport(res.Check, res.Content)
                } else {
                    $('.report-check').css('display', 'none')
                    $(".not-check").css('display', 'none')
                }

                if(res.images){
                    var i = 0
                    $.each(res.images, function(index,value){
                        var tplImageUploaded = $('#tplImageUploaded').html()
                        tplImageUploaded = tplImageUploaded
                                .replace(/__id__/g, i)
                                .replace(/__id-uploaded__/g,value.ID)
                                .replace(/__src__/g, "ImageReport/ID_" + res.IDReport + "/" + value.ImageName)
                        $('.files-preview').append(tplImageUploaded)

                        i++
                    })
                    $('#currentIndexFiles').val(i)

                }

                setTimeout(function(){
                    $('#previewImages').show()
                },1000)
                $('#reportModal').modal()
            }
        }
    })
}

function insertReport(){
    const form = $(".ajax-form-report");
    ajax_form(form);
    $('.loader').show()
    $('.text-submit').hide()
}

function ajax_form(form){
    const form_data = new FormData(form.get(0));

    const button = form.find("button[id=SubmitReport]");
    button.attr("disabled", true);

    // case update
    $arr_uploaded = []
    if($('#TypeSubmit').val() == 'update'){
        $('.image-uploaded').each(function(){
            $arr_uploaded.push($(this).attr('data-id-uploaded'))
        })
        form_data.append('imagesUploaded', $arr_uploaded)
    }

    if(files.length == 0){
        form_data.append('files', 'null');
    } else {
        files.forEach(file => {
            /* here just put file as file[] so now in submitting it will send all files */
            form_data.append('files[]', file);
        });
    }

    var haveCheck = ($('.report-check').css('display') == "none") ? 0 : 1

    var arrChecked = []
    var i = 0
    $('input[name="Check"]:checked').each(function(){
        arrChecked.push($(this).val())
    })

    $('input[class="checkbox-report"]:checked').each(function(){
        arrChecked.push($(this).val())
    })

    // loại bỏ trùng lặp
    arrChecked =  Array.from(new Set(arrChecked));

    form_data.append('typeSubmit', $('#TypeSubmit').val())
    form_data.append('typeReport', $('#TypeReport').val())
    form_data.append('customerID', $('#CustomerName').val())
    form_data.append('ID', $('#IDReport').val())
    form_data.append('TimeCardID', '')
    form_data.append('content', $('#ContentReport').val())
    form_data.append('haveCheck', haveCheck)
    form_data.append('valuesChecked', arrChecked)
    form_data.append('note', $('#NoteReport').val())

    $.ajax({
        headers:{'X-CSRF-TOKEN': csrfToken},
        url: __baseUrl + 'mypage/insertReport',
        type: 'POST',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        dataType: "json",
        success:function(res){
            if(res.success == 1){
                files = []
                $('#reportModal').modal('hide')
                swal({
                    // TODO:^language
                    title: $('#text_report').val(),
                    text: $('#text_submit_successfully').val(),
                    icon: 'success'
                })
                setTimeout(function(){
                    swal.close()
                },3000)
            }
            $('#SubmitReport').attr('disabled', false)
            $('.loader').hide()
            $('.text-submit').show()
        },
        error:function(res){
            swal({
                // TODO:^language
                title: $('#text_report').val(),
                text: $('#text_submit_failed').val(),
                icon: 'error'
            })
            $('#SubmitReport').attr('disabled', false)
            $('.loader').hide()
            $('.text-submit').show()
        }
    })
}

function checkOut(){
    $.ajax({
        headers: {'X-CSRF-TOKEN': csrfToken},
        url: __baseUrl + 'mypage/validateCheckout',
        type: 'POST',
        data: {customerID: $('#CustomerName').val()},
        success: function(res){
            $('#CheckOut').attr('disabled', false)
            if(res.valid){
                if(res.valid == 1){
                    $('#timecardIDCheckout').val(res.timecardIDCheckout)
                    $('#CaptureCamera').data('type','checkout')
                    $('#lookCameraModal').modal()
                } else {
                    swal({
                        // TODO:language
                        title: $('#text_check_out').val(),
                        text: res.info,
                        icon: 'error'
                    })
                }
            } else {
                if(res.not_reported){
                    swal({
                        // TODO:^language
                        title: $('#text_check_out').val(),
                        text: $('#text_not_reported').val(),
                        icon: 'error'
                    })
                } else {
                    if(res.same_area == 0){
                        // if(res.not_timeout){ // diff area + not TimeOut
                        //     swal({
                        //         // TODO:^language
                        //         title: $('#text_check_out').val(),
                        //         // text: 'The Customer Name is selecting and check-in are not the same / You have not checked in yet at here.',
                        //         text: $('#text_not_checked_in').val(),
                        //         icon: 'error'
                        //     })
                        // } else { // diff area + Timeout
                        //     swal({
                        //         // TODO:^language
                        //         title: $('#text_check_out').val(),
                        //         text: $('#text_not_checked_in').val(),
                        //         icon: 'error'
                        //     })
                        // }
                        swal({
                            // TODO:^language
                            title: $('#text_check_out').val(),
                            text: $('#text_not_checked_in').val(),
                            icon: 'error'
                        })
                    } else {
                        swal({
                            // TODO:^language
                            title: $('#text_check_out').val(),
                            text:  $('#text_you_checked_out').val().replace("TIME",res.timeCheckout),
                            icon: 'info'
                        })
                    }
                }
            }
        }
    })
}

function resetFieldReport(checked = [], content = ""){
    $.ajax({
        headers: {'X-CSRF-TOKEN': csrfToken},
        url: __baseUrl + 'mypage/getFormReports',
        type: 'POST',
        data: {'TypeCode': $('#TypeReport').val()},
        success:function(response){
            if(response.typeCheck == 1){
                var data = response.data
                var rows = ''
                const tplCheckbox = $('#tplCheckbox').html()
                const tplRadio = $('#tplRadio').html()
                const tplSecCheck = $('#tplSecCheck').html()

                var secCheck = ''
                var checkboxs = ''
                var category = ''
                var haveCategory = 0

                $.each(data, function(index, child){
                    secCheck = ''
                    checkboxs = ''
                    category = index
                    haveCategory = (index != "") ? haveCategory + 1  : haveCategory
                    secCheck = tplSecCheck.replace(/__category__/g,category)
                    $.each(child,function(index, val){
                        //console.log(val);
                        if (val.TypeCat == 1 || val.TypeCat == null) {
                            checkboxs += tplCheckbox.replace(/__id__/g,val.CheckID)
                                        .replace(/__detail__/g,val.CheckPoint)
                        } else if (val.TypeCat == 3) {
                            checkboxs += tplRadio.replace(/__id__/g,val.CheckID)
                                        .replace(/__detail__/g,val.CheckPoint)
                                        .replace(/__idCat__/g,val.idCat)
                        }


                    })
                    secCheck = secCheck.replace(/__checkboxs__/g,checkboxs)

                    rows += secCheck
                })

                $("#checkreport").html(rows)

                //set values
                $.each(checked, function(index, value){
                    $('.checkbox-report').each(function(){
                        var this_value = $(this).val()
                        if(this_value == value.CheckID && value.Result == 1){
                            $(this).prop('checked', true)
                        }
                    })
                })
                if(content != "null"){
                    $('#NoteReport').val(content)
                }

                // handle html
                if(haveCategory == 0){
                    $('.legend-category').css('display', 'none')
                }
                $('.report-check').css('display', 'block')
                $(".not-check").css('display', 'none')
            } else {
                if(content != "null"){
                    $('#ContentReport').val(content)
                }
                $('.report-check').css('display', 'none')
                $(".not-check").css('display', 'block')
            }
        }
    })
}

function postCheckbox(timecard, type, id, checked){
    $.ajax({
        headers: {'X-CSRF-TOKEN': csrfToken},
        url: __baseUrl + 'mypage/queryCheck',
        type: 'POST',
        data: {'timecard': timecard, 'type': type, 'id': id, 'checked': checked},
        success:function(res){
            if(res.success == 1){
                console.log('ok')
            }
        }
    })
}

var LoadingModule = (function() {
    var $loadingInstance = $(".loading-screen")
    var showLoading = function() {
        $loadingInstance.addClass('show-loading-screen');
    }

    var hideLoading = function() {
        $loadingInstance.removeClass('show-loading-screen')
    }

    return {
        showLoading: showLoading,
        hideLoading: hideLoading,
    }
})()


/* Overtime */
$('.datepicker').datepicker({
    format: 'yyyy/mm/dd',
    daysOfWeekHighlighted: "1",
    todayHighlight: true,
    toggleActive: true,
	autoclose: true,
	startDate: new Date()
});
$('.datepicker_form .fa').on('click', function(){
	$(this).parents('.datepicker_form').find('.datepicker').focus();
});
if(location.pathname.split('/').pop() === 'annual_leave')
{
	var calcBusinessDays = function(startDate, endDate) {
		let count = 0;
		const curDate = new Date(startDate.getTime());
		while (curDate <= endDate) {
			const dayOfWeek = curDate.getDay();
			if(dayOfWeek !== 0 && dayOfWeek !== 6) count++;
			curDate.setDate(curDate.getDate() + 1);
		}
		return count;
	}
	var isToDateValid = false;
	var isTypeValid = false;
	var isReasonValid = false;
	var validDate = function(FromDate, ToDate, el)
	{
		var isToDateValid = false;
		if(FromDate && ToDate)
		{
			estimate = calcBusinessDays(new Date(FromDate), new Date(ToDate));
			if(estimate <= 0)
			{
				swal("", language['_overtime_validate_time'][language_choice], "error");
				el.val('');
			}
			else
			{
				isToDateValid = true;
			}
		}
		if(isToDateValid === true)
		{
			var postfix = '';
			if(language_choice == 'EN')
			{
				postfix = '(s)';
			}
			$('#ToDatePreview').text(ToDate);
			$('#Estimate, #EstimatePreview').text(estimate+' '+language['_overtime_date'][language_choice].toLowerCase()+postfix);
		}
		return isToDateValid;
	}
	$('[name="FromDate"]').on('change', function(){
		if($(this).val())
		{
			$('#FromDatePreview').text($(this).val());
			$('[name="ToDate"]').removeAttr('disabled');
			
			var FromDate = $('[name="FromDate"]').val();
			var ToDate = $('[name="ToDate"]').val();
			isToDateValid = validDate(FromDate, ToDate, $(this));
		}
		checkFormValid();
	});

	$('[name="ToDate"]').on('change', function(){
		var FromDate = $('[name="FromDate"]').val();
		var _self = $(this);
		setTimeout(function(){
			var estimate = 0;
			var ToDate = $('[name="ToDate"]').val();
			isToDateValid = validDate(FromDate, ToDate, $(this));
			checkFormValid();
		}, 1);
	});

	$('input[type=radio][name=Type]').on('change', function(){
		var v = $(this).val();
		if(['al', 'ul'].indexOf(v) > -1)
		{
			$('#TypePreview').text($(this).val().toUpperCase());
			isTypeValid = true;
			checkFormValid();
		}
	});

	$('[name=Reason]').keyup(function(){
		var len = $(this).val().length;
		$('#CountText').text(len);
		if (len > 0 && len <= 500) {
			isReasonValid = true;
			$('#ReasonPreview').text($(this).val());
		}
		else
		{
			isReasonValid = false;
		}
		checkFormValid();
	});

	var checkFormValid = function(){
		console.log(isToDateValid,isTypeValid,isReasonValid);
		if(isToDateValid && isTypeValid && isReasonValid)
		{
			$('#PreviewForm').removeAttr('disabled');
		}
		else
		{
			$('#PreviewForm').attr('disabled', 'disabled');
		}
	}

	$('#SubmitForm').on('click', function(){
		var hideForm = $('#HideForm');
		var _self = $(this);
		var defaultText = _self.text();
		var removeLoading = function(){
			hideForm.removeAttr('disabled');
			_self.removeAttr('disabled');
			_self.html(defaultText);
		}

		hideForm.attr('disabled', 'disabled');
		_self.attr('disabled', 'disabled');
		_self.html('<i class="fa fa-spinner fa-spin"></i>');

		var StartDate = $('[name="FromDate"]').val();
		var ToDate = $('[name="ToDate"]').val();
		var Type = $('[name="Type"]').val();
		var Reason = $('[name="Reason"]').val();
		if(StartDate && ToDate && Type && Reason)
		{
			var request = $.ajax({
				headers: {'X-CSRF-TOKEN': csrfToken},
				url: __baseUrl+"annual_leave",
				method: "POST",
				data: { 
					'StartDate' : StartDate,
					'ToDate' : ToDate,
					'Type' : Type,
					'Reason' : Reason,
				},
				dataType: "json"
			});
			request.done(function(res) {
				removeLoading();
				if(typeof res !== 'undefined' && typeof res.code !== 'undefined')
				{
					if(res.code === 200)
					{
						swal("", language['_overtime_validate_success'][language_choice], "success").then(function(){
							location.href = __baseUrl;
						});
					}
					else if(res.code === 409 || res.code === 404)
					{
						swal("", res.msg, "error");
						$('#HideForm').click();
					}
				}
				else
				{
					swal("", language['_overtime_validate_error'][language_choice], "error");
				}
			});
			request.fail(function( jqXHR, textStatus ) {
				removeLoading();
				swal("", language['_overtime_validate_error'][language_choice], "error");
			});
		}
		else
		{
			removeLoading();
		}
	});

}
if(location.pathname.split('/').pop() === 'overtime')
{
	var current = new Date();
	var startINS = new Picker(document.querySelector('[name="FromTime"]'), {
		format: 'HH:mm',
		headers: true,
		text: {
			title: language['_overtime_from_time'][language_choice],
		},
	});
	var toINS = new Picker(document.querySelector('[name="ToTime"]'), {
		format: 'HH:mm',
		headers: true,
		text: {
			title: language['_overtime_to_time'][language_choice],
		},
	});
	$('[name="StartDate"]').on('change', function(){
		$('#StartDatePreview').text($(this).val());
		if($(this).val())
		{
			startINS.setDate($(this).val());
			toINS.setDate($(this).val());
			$('[name="FromTime"]').removeAttr('disabled');
			$('[name="FromTime"]').focus();
		}
	});
	$('[name="FromTime"]').on('change', function(){
		$('[name="ToTime"]').attr('disabled', 'disabled');
		var StartDate = $('[name="StartDate"]').val();
		var FromTime = startINS.getDate(true);
		if(FromTime)
		{
			// if(+new Date(StartDate+' '+FromTime+':00') > +current - 5000)
			// {
			// 	swal("", language['_overtime_validate_start_time'][language_choice], "error").then((value) => {
			// 		$('[name="FromTime"]').focus();
			// 		startINS.reset();
			// 	});
			// }
			// else
			// {
			// 	$('[name="ToTime"]').removeAttr('disabled');
			// 	toINS.reset();
			// 	$('[name="ToTime"]').focus();
			// }
			$('[name="ToTime"]').removeAttr('disabled');
			toINS.reset();
			$('[name="ToTime"]').focus();
			
		}
	});
	$('[name="ToTime"]').on('change', function(){
		var _self = $(this);
		var isValid = false;
		var StartDate = $('[name="StartDate"]').val();
		var FromTime = startINS.getDate(true);
		var ToTime = toINS.getDate(true);
		console.log(ToTime);
		var estimate = 0;
		if(FromTime && ToTime && StartDate)
		{
			estimate = (new Date(StartDate+' '+ToTime+':00') - new Date(StartDate+' '+FromTime+':00'))/60000;
			if(estimate <= 0)
			{
				swal("", language['_overtime_validate_time'][language_choice], "error").then((value) => {
					$('[name="ToTime"]').focus();
					toINS.reset();
				});
			}
			else
			{
				isValid = true;
			}
		}
		if(isValid === true)
		{
			$('#PreviewForm').removeAttr('disabled');
			$('#Estimate, #EstimatePreview').text(timeConvert(estimate));
			$('#TimePreview').text(FromTime+' - '+ToTime);
		}
		if(startINS.getDate(true) && startINS.getDate(true) && isValid === false)
		{
			$('#PreviewForm').attr('disabled', 'disabled');
		}
	});
	$('#SubmitForm').on('click', function(){
		var hideForm = $('#HideForm');
		var _self = $(this);
		var defaultText = _self.text();
		var removeLoading = function(){
			hideForm.removeAttr('disabled');
			_self.removeAttr('disabled');
			_self.html(defaultText);
		}

		hideForm.attr('disabled', 'disabled');
		_self.attr('disabled', 'disabled');
		_self.html('<i class="fa fa-spinner fa-spin"></i>');

		var StartDate = $('[name="StartDate"]').val();
		var FromTime = $('[name="FromTime"]').val();
		var ToTime = $('[name="ToTime"]').val();
		if(FromTime && ToTime && StartDate)
		{
			var request = $.ajax({
				headers: {'X-CSRF-TOKEN': csrfToken},
				url: __baseUrl+"overtime",
				method: "POST",
				data: { 
					'StartDate' : StartDate,
					'FromTime' : FromTime,
					'ToTime' : ToTime,
				},
				dataType: "json"
			});
			request.done(function(res) {
				removeLoading();
				if(typeof res !== 'undefined' && typeof res.code !== 'undefined')
				{
					if(res.code === 200)
					{
						swal("", language['_overtime_validate_success'][language_choice], "success").then(function(){
							location.href = __baseUrl;
						});
					}
					else if(res.code === 409 || res.code === 404)
					{
						swal("", res.msg, "error");
						$('#HideForm').click();
					}
				}
				else
				{
					swal("", language['_overtime_validate_error'][language_choice], "error");
				}
			});
			request.fail(function( jqXHR, textStatus ) {
				removeLoading();
				swal("", language['_overtime_validate_error'][language_choice], "error");
			});
		}
		else
		{
			removeLoading();
		}
	});
}
$('#PreviewForm').on('click', function(){
	$('#form_preview').removeClass('d-none');
	$('#form_input').addClass('d-none');
});

$('#HideForm').on('click', function(){
	$('#form_preview').addClass('d-none');
	$('#form_input').removeClass('d-none');
});

/* Parse notification */
var numberWithCommas = function(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
var formatDate = function(raw, t){
	var d = new Date(raw);
	var m = d.getMonth()+1;
	if(m < 10)
	{
		m = '0'+m;
	}
	var dt = d.getDate();
	if(dt < 10)
	{
		dt = '0'+dt;
	}
	var mins = d.getMinutes();
	if(mins < 10 && (''+mins).length === 1)
	{
		mins = '0'+mins;
	}
	if(t === 'YYYY/MM/DD HH:II')
	{
		return d.getFullYear()+'/'+m+'/'+dt+' '+d.getHours()+':'+mins;
	}
	else if(t === 'YYYY/MM/DD')
	{
		return d.getFullYear()+'/'+m+'/'+dt;
	}
	else if(t === 'HH:II')
	{
		return d.getHours()+':'+mins;
	}
}
var dayConvert = function(d) {
	var postfix = '';
	if(language_choice == 'EN')
	{
		postfix = '(s)';
	}
	return numberWithCommas(d)+" "+language['_overtime_date'][language_choice]+postfix;
}

var timeConvert = function(n) {
	var num = n;
	var hours = (num / 60);
	var rhours = Math.floor(hours);
	var minutes = (hours - rhours) * 60;
	var rminutes = Math.round(minutes);
	var postfix = '';
	if(language_choice == 'EN')
	{
		postfix = '(s)';
	}
	if(rhours > 0)
	{
		
		if(rminutes > 0)
		{
			return rhours+" "+language['_overtime_hours'][language_choice]+postfix+" " + rminutes + " "+language['_overtime_minutes'][language_choice]+postfix;	
		}
		else
		{
			return rhours+" "+language['_overtime_hours'][language_choice]+postfix;
		}
	}
	else
	{
		return rminutes + " "+language['_overtime_minutes'][language_choice]+postfix;
	}
}
var getOverTimeList = function(role, callback, fallback){
	var request = $.ajax({
		headers: {'X-CSRF-TOKEN': csrfToken},
		url: __baseUrl+"notice",
		method: "POST",
		data: { 
			'role' : role
		},
		dataType: "json"
	});
	request.done(function(res) {
		callback(res);
	});
	request.fail(function( jqXHR, textStatus ) {
		fallback(textStatus);
	});
}

var renderStatus = function(status){
	var icon = true;
	if(status === 1)
	{
		if(typeof icon !== 'undefined')
		{
			return '<i style="color:#02b921" class="fa fa-check-circle" aria-hidden="true"></i> '+language['_overtime_accept'][language_choice];
		}
		return language['_overtime_accept'][language_choice];
	}
	else if(status === 2)
	{
		if(typeof icon !== 'undefined')
		{
			return '<i style="color:#F00" class="fa fa-ban" aria-hidden="true"></i> '+language['_overtime_refuse'][language_choice];
		}
		return language['_overtime_refuse'][language_choice];
	}
	else
	{
		if(typeof icon !== 'undefined')
		{
			return '<i style="color:#014ffb" class="fa fa-clock" aria-hidden="true"></i> '+language['_overtime_pending'][language_choice];
		}
		return language['_overtime_pending'][language_choice];
	}
}

var renderStatusAL = function(status){
	var icon = true;
	if(status === 1)
	{
		if(typeof icon !== 'undefined')
		{
			return '<i style="color:#4e73df" class="fa fa-check-circle" aria-hidden="true"></i> '+language['_annualleave_leader_accept'][language_choice];
		}
		return language['_overtime_accept'][language_choice];
	}
	else if(status === 3)
	{
		if(typeof icon !== 'undefined')
		{
			return '<i style="color:#02b921" class="fa fa-check-circle" aria-hidden="true"></i> '+language['_annualleave_manager_accept'][language_choice];
		}
		return language['_overtime_accept'][language_choice];
	}
	else if(status === 2)
	{
		if(typeof icon !== 'undefined')
		{
			return '<i style="color:#F00" class="fa fa-ban" aria-hidden="true"></i> '+language['_overtime_refuse'][language_choice];
		}
		return language['_overtime_refuse'][language_choice];
	}
	else
	{
		if(typeof icon !== 'undefined')
		{
			return '<i style="color:#014ffb" class="fa fa-clock" aria-hidden="true"></i> '+language['_overtime_pending'][language_choice];
		}
		return language['_overtime_pending'][language_choice];
	}
}

var getMyListOT = function(Year, Month)
{
	var request = $.ajax({
		headers: {'X-CSRF-TOKEN': csrfToken},
		url: __baseUrl+"overtime-management",
		method: "POST",
		data: { 
			'Year' : Year,
			'Month' : Month
		},
		dataType: "json"
	});
	request.done(function(res) {
		if(typeof res !== 'undefined' && typeof res['data'] !== 'undefined' && res['data'].length > 0)
		{
			var leaders = [];
			if(typeof res['leaders'] !== 'undefined' && res['leaders'].length > 0)
			{
				$.each(res['leaders'], function(kl, vl){
					leaders.push(vl['Position']+': '+vl['StaffID']+' '+vl['Name']);
				});
			}
			var html = '';
			$.each(res['data'], function(k, v){
				html += '\
				<p class="mb-2">'+formatDate(v['StartTime'], 'YYYY/MM/DD')+' <span class="ml-3">'+formatDate(v['StartTime'], 'HH:II')+'-'+formatDate(v['EndTime'], 'HH:II')+'</span></p>\
				<p class="mb-2"><span class="toatl">'+timeConvert(v['Total'])+'</span> | '+renderStatus(v.Status, true)+'</p>\
				<p class="mb-2">'+leaders.join(' | ')+'</p>\
				<hr></hr>\
				';
			});
			$('#result-ot').html(html);
		}
		else
		{
			$('#result-ot').html(noData);
		}
	});
	request.fail(function( jqXHR, textStatus ) {
		$('#result-ot').html(noData);
	});
}

var getRequestListOT = function(Year, Month)
{
	var request = $.ajax({
		headers: {'X-CSRF-TOKEN': csrfToken},
		url: __baseUrl+"overtime-request",
		method: "POST",
		data: { 
			'Type': "list",
			'Year' : Year,
			'Month' : Month
		},
		dataType: "json"
	});
	request.done(function(res) {
		if(typeof res !== 'undefined' && typeof res['data'] !== 'undefined' && res['data'].length > 0)
		{
			var html = '';
			$.each(res['data'], function(k, val){
				v = val['TBLTOverTime'];
				var button = '';
				if(v.Status === 0)
				{
					button = '<p class="mb-2"><a data-id="'+v['ID']+','+val['ID']+'" data-action="approve" class="btn btn-sm btn-success text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> '+language['_overtime_approve'][language_choice]+'</a> <a data-id="'+v['ID']+','+val['ID']+'" data-action="refuse" class="btn btn-sm btn-danger text-light"><i class="fa fa-ban" aria-hidden="true"></i> '+language['_overtime_refuse'][language_choice]+'</a></p>';
				}
				html += '\
				<div class="request_item">\
					<p class="mb-2">'+language['_overtime_date'][language_choice]+': '+formatDate(v['StartTime'], 'YYYY/MM/DD')+' <span class="ml-2">'+language['_overtime_time'][language_choice]+': '+formatDate(v['StartTime'], 'HH:II')+'-'+formatDate(v['EndTime'], 'HH:II')+'</span></p>\
					<p class="mb-2">'+language['_overtime_total'][language_choice]+': <span class="toatl">'+timeConvert(v['Total'])+'</span> | <span class="status_request">'+renderStatus(v.Status, true)+'</span></p>\
					<p class="mb-2">'+res['staff'][v['StaffID']]['Position']+': '+res['staff'][v['StaffID']]['StaffID']+' '+res['staff'][v['StaffID']]['Name']+'</p>\
					'+button+'\
					<hr></hr>\
				</div>\
				';
			});
			$('#result-ot').html(html);
			$('#result-ot .btn-success, #result-ot .btn-danger').on('click', function(){
				var _self = $(this);
				var id = $(this).attr('data-id').split(',');
				var action = $(this).attr('data-action');
				var actionRequest = $.ajax({
					headers: {'X-CSRF-TOKEN': csrfToken},
					url: __baseUrl+"overtime-request",
					method: "POST",
					data: { 
						'Type': "action",
						'ID' : id,
						'Action' : action
					},
					dataType: "json"
				});
				actionRequest.done(function(res) {
					if(typeof res !== 'undefined' && typeof res['data'] !== 'undefined' && +res['data'] > 0)
					{
						_self.parents('.request_item').find('.status_request').html(renderStatus(+res['data'], true));
						_self.parents('.mb-2').remove();
					}
					else
					{
						swal("", language['_overtime_validate_error'][language_choice], "error");
					}
				});
				actionRequest.fail(function( jqXHR, textStatus ) {
					console.log(textStatus);
				});
			});
		}
		else
		{
			$('#result-ot').html(noData);
		}
	});
	request.fail(function( jqXHR, textStatus ) {
		$('#result-ot').html(noData);
	});
}

var getMyListAL = function(Year, Month)
{
	var request = $.ajax({
		headers: {'X-CSRF-TOKEN': csrfToken},
		url: __baseUrl+"annual-leave-management",
		method: "POST",
		data: { 
			'Year' : Year,
			'Month' : Month
		},
		dataType: "json"
	});
	request.done(function(res) {
		if(typeof res !== 'undefined' && typeof res['data'] !== 'undefined' && res['data'].length > 0)
		{
			var leaders = [];
			if(typeof res['leaders'] !== 'undefined' && res['leaders'].length > 0)
			{
				$.each(res['leaders'], function(kl, vl){
					leaders.push(vl['Position']+': '+vl['StaffID']+' '+vl['Name']);
				});
			}
			var html = '';
			$.each(res['data'], function(k, v){
				html += '\
				<p class="mb-2"><span>'+language['_overtime_date'][language_choice]+': '+formatDate(v['FromDate'], 'YYYY/MM/DD')+' - '+formatDate(v['ToDate'], 'YYYY/MM/DD')+'</span></p>\
				<p class="mb-2"><span class="toatl">'+dayConvert(v['Total'])+'</span> | '+renderStatusAL(v.Status, true)+'</p>\
				<p class="mb-2">'+leaders.join(' | ')+'</p>\
				<hr></hr>\
				';
			});
			$('#result-al').html(html);
		}
		else
		{
			$('#result-al').html(noData);
		}
	});
	request.fail(function( jqXHR, textStatus ) {
		$('#result-al').html(noData);
	});
}

var getRequestListAL = function(Year, Month)
{
	var html = '';
	var request = $.ajax({
		headers: {'X-CSRF-TOKEN': csrfToken},
		url: __baseUrl+"annual-leave-request",
		method: "POST",
		data: { 
			'Type': "list",
			'Year' : Year,
			'Month' : Month
		},
		dataType: "json"
	});
	request.done(function(res) {
		if(typeof res !== 'undefined' && typeof res['data'] !== 'undefined' && res['data'].length > 0)
		{
			$.each(res['data'], function(k, val){
				v = val['TBLTAnnualLeave'];
				var button = '';
				if(v.Status === 0)
				{
					button = '<p class="mb-2"><a data-id="'+v['ID']+','+val['ID']+'" data-action="approve" class="btn btn-sm btn-success text-light"><i class="fa fa-check-circle" aria-hidden="true"></i> '+language['_overtime_approve'][language_choice]+'</a> <a data-id="'+v['ID']+','+val['ID']+'" data-action="refuse" class="btn btn-sm btn-danger text-light"><i class="fa fa-ban" aria-hidden="true"></i> '+language['_overtime_refuse'][language_choice]+'</a></p>';
				}
				html += '\
				<div class="request_item">\
					<p class="mb-2">'+language['_overtime_date'][language_choice]+': '+formatDate(v['FromDate'], 'YYYY/MM/DD')+' - '+formatDate(v['ToDate'], 'YYYY/MM/DD')+'</span></p>\
					<p class="mb-2">'+language['_overtime_total'][language_choice]+': <span class="toatl">'+dayConvert(v['Total'])+'</span> | <span class="status_request">'+renderStatusAL(v.Status, true)+'</span></p>\
					<p class="mb-2">'+res['staff'][v['StaffID']]['Position']+': '+res['staff'][v['StaffID']]['StaffID']+' '+res['staff'][v['StaffID']]['Name']+'</p>\
					'+button+'\
					<hr></hr>\
				</div>\
				';
			});
			$('#result-al').html(html);
			$('#result-al .btn-success, #result-al .btn-danger').on('click', function(){
				var _self = $(this);
				var id = $(this).attr('data-id').split(',');
				var action = $(this).attr('data-action');
				var actionRequest = $.ajax({
					headers: {'X-CSRF-TOKEN': csrfToken},
					url: __baseUrl+"annual-leave-request",
					method: "POST",
					data: { 
						'Type': "action",
						'ID' : id,
						'Action' : action
					},
					dataType: "json"
				});
				actionRequest.done(function(res) {
					if(typeof res !== 'undefined' && typeof res['data'] !== 'undefined' && +res['data'] > 0)
					{
						_self.parents('.request_item').find('.status_request').html(renderStatus(+res['data'], true));
						_self.parents('.mb-2').remove();
					}
					else
					{
						swal("", language['_overtime_validate_error'][language_choice], "error");
					}
				});
				actionRequest.fail(function( jqXHR, textStatus ) {
					console.log(textStatus);
				});
			});
		}
		else
		{
			$('#result-al').html(noData);
		}
	});
	request.fail(function( jqXHR, textStatus ) {
		$('#result-al').html(noData);
	});
}

var firstRun = false;
if(['Leader', 'Area Leader'].indexOf(role) > -1)
{
	var noData = '<article id="item1" class="tab-content content-visible"><div class="newsBox text-center">'+language['_overtime_no_data'][language_choice]+'</div></article>';
	getOverTimeList(role, function(res){
		if(typeof res !== 'undefined')
		{
			// Parser OT list
			if(typeof res['data'] !== 'undefined' && res['data'].length > 0)
			{
				var html = '';
				$.each(res['data'], function(k, v){
					var totalText = timeConvert(v['Total']);
					html += '\
					<article id="item1" class="tab-content content-visible">\
						<div class="newsBox'+((!v['Notification']) ? ' unread' : '')+'">\
							<a href="'+__baseUrl+'overtime-management">\
								<span>'+language['_overtime_date'][language_choice]+': '+formatDate(v['StartTime'], 'YYYY/MM/DD')+' '+renderStatus(v.Status)+'</span><br>\
								<span>'+language['_overtime_time'][language_choice]+': '+formatDate(v['StartTime'], 'HH:II')+' - '+formatDate(v['EndTime'], 'HH:II')+'</span><br>\
								<span>'+language['_overtime_total'][language_choice]+': '+totalText+'</span>\
							</a>\
						</div>\
					</article>\
					';
				});
				$('#OTNotice #ot_list').html(html);
			}
			else
			{
				$('#OTNotice #ot_list').html(noData);
			}
			// Parser AL list
			if(typeof res['data_al'] !== 'undefined' && res['data_al'].length > 0)
			{
				var html = '';
				$.each(res['data_al'], function(k, v){
					var totalText = dayConvert(v['Total']);
					html += '\
					<article id="item1" class="tab-content content-visible">\
						<div class="newsBox'+((!v['Notification']) ? ' unread' : '')+'">\
							<a href="'+__baseUrl+'annual-leave-management">\
								<span>'+language['_annualleave_status'][language_choice]+': '+renderStatusAL(v.Status)+'</span><br>\
								<span>'+language['_overtime_time'][language_choice]+': '+formatDate(v['FromDate'], 'YYYY/MM/DD')+' - '+formatDate(v['ToDate'], 'YYYY/MM/DD')+'</span><br>\
								<span>'+language['_overtime_total'][language_choice]+': '+totalText+'</span>\
							</a>\
						</div>\
					</article>\
					';
				});
				if(role === 'Leader')
				{
					$('#OTNotice #al_list').html(html);
				}
				else
				{
					$('#ALNotice #al_list').html(html);
				}
			}
			else
			{
				if(role === 'Leader')
				{
					$('#OTNotice #al_list').html(noData);
				}
				else
				{
					$('#ALNotice #al_list').html(noData);
				}
			}
			if(role === 'Area Leader')
			{
				// Parser OT
				if(typeof res['data_request_ot'] !== 'undefined' && res['data_request_ot'].length > 0)
				{
					var html = '';
					$.each(res['data_request_ot'], function(k, val){
						var v = val['TBLTOverTime'];
						var u = val['TBLMStaff'];
						var totalText = timeConvert(v['Total']);
						html += '\
						<article id="item1" class="tab-content content-visible">\
							<div class="newsBox'+((!val['Notification']) ? ' unread' : '')+'">\
								<a href="'+__baseUrl+'overtime-request">\
									<p>'+res['staff'][v['StaffID']]['Position']+': '+res['staff'][v['StaffID']]['StaffID']+' '+res['staff'][v['StaffID']]['Name']+'</p>\
									<span>'+language['_overtime_date'][language_choice]+': '+formatDate(v['StartTime'], 'YYYY/MM/DD')+' | '+renderStatus(v.Status)+'</span><br>\
									<span>'+language['_overtime_time'][language_choice]+': '+formatDate(v['StartTime'], 'HH:II')+' - '+formatDate(v['EndTime'], 'HH:II')+'</span><br>\
									<span>'+language['_overtime_total'][language_choice]+': '+totalText+'</span>\
								</a>\
							</div>\
						</article>\
						';
					});
					$('#OTNotice #my_ot_list').html(html);
				}
				else
				{
					$('#OTNotice #my_ot_list').html(noData);
				}
				// Parser AL
				if(typeof res['data_request_al'] !== 'undefined' && res['data_request_al'].length > 0)
				{
					var html = '';
					$.each(res['data_request_al'], function(k, val){
						var v = val['TBLTAnnualLeave'];
						var u = val['TBLMStaff'];
						var totalText = dayConvert(v['Total']);
						html += '\
						<article id="item1" class="tab-content content-visible">\
							<div class="newsBox'+((!val['Notification']) ? ' unread' : '')+'">\
								<a href="'+__baseUrl+'annual-leave-request">\
									<p>'+res['staff'][v['StaffID']]['Position']+': '+res['staff'][v['StaffID']]['StaffID']+' '+res['staff'][v['StaffID']]['Name']+'</p>\
									<span>'+language['_annualleave_status'][language_choice]+': '+renderStatusAL(v.Status)+'</span><br>\
									<span>'+language['_overtime_time'][language_choice]+': '+formatDate(v['FromDate'], 'YYYY/MM/DD')+' - '+formatDate(v['ToDate'], 'YYYY/MM/DD')+'</span><br>\
									<span>'+language['_overtime_total'][language_choice]+': '+totalText+'</span>\
								</a>\
							</div>\
						</article>\
						';
					});
					$('#ALNotice #my_al_list').html(html);
				}
				else
				{
					$('#ALNotice #my_al_list').html(noData);
				}
				// Parse count for tab
				if(res['total_my_al'] > 0)
				{
					$('#total_al').text(' ('+numberWithCommas(res['total_my_al'])+')');
				}
				if(res['total_my_ot'] > 0)
				{
					$('#total_ot').text(' ('+numberWithCommas(res['total_my_ot'])+')');
				}
				if(res['total_request_al'] > 0)
				{
					$('#total_al_request').text(' ('+numberWithCommas(res['total_request_al'])+')');
				}
				if(res['total_request_ot'] > 0)
				{
					$('#total_ot_request').text(' ('+numberWithCommas(res['total_request_ot'])+')');
				}
				// Show count notify
				if(typeof res['total'] !== 'undefined' && res['total'] > 0)
				{
					$('#OTNotice .number').text(numberWithCommas(res['total']));
				}
				if(typeof res['total_al'] !== 'undefined' && res['total_al'] > 0)
				{
					$('#ALNotice .number').text(numberWithCommas(res['total_al']));
				}
			}
			else
			{
				if(res['total_my_al'] > 0)
				{
					$('#total_al').text(' ('+numberWithCommas(res['total_my_al'])+')');
				}
				if(res['total_my_ot'] > 0)
				{
					$('#total_ot').text(' ('+numberWithCommas(res['total_my_ot'])+')');
				}
				// Show count notify
				res['total'] += res['total_al'];
				if(typeof res['total'] !== 'undefined' && res['total'] > 0)
				{
					$('#OTNotice .number').text(numberWithCommas(res['total']));
				}
			}
			
		}
		else
		{
			$('#ALNotice #al_list').html(noData);
			$('#OTNotice #al_list').html(noData);
		}
	}, function(){
		$('#ALNotice #al_list').html(noData);
		$('#OTNotice #al_list').html(noData);
	});
	$('.dropdown-toggle').click(function() {
		
		if($(this).parents('#ALNotice').length)
		{
			$('#OTNotice .dropdown').hide();
		}
		else
		{
			$('#ALNotice .dropdown').hide();
		}
		$(this).next('.dropdown').toggle( 400 );
		if($(this).parents('#OTNotice').length)
		{
			$('#OTNotice .number').text('');	
		}
		if($(this).parents('#ALNotice').length)
		{
			$('#ALNotice .number').text('');	
		}
		if(firstRun === false)
		{
			getOverTimeList('mark_readed', function(res){
				if(typeof res !== 'undefined')
				{
					firstRun = true;
				}
			}, function(){
				
			});
		}
	});
	$('#OTNotice .tab-item').on('click', function(){
		$('#OTNotice .tab-item a').removeClass('active');
		$(this).find('a').addClass('active');
		$('#OTNotice .wrapper_tab-content>div').addClass('d-none');
		$('#'+$(this).attr('data-id')).removeClass('d-none');
	});
	$('#ALNotice .tab-item').on('click', function(){
		$('#ALNotice .tab-item a').removeClass('active');
		$(this).find('a').addClass('active');
		$('#ALNotice .wrapper_tab-content>div').addClass('d-none');
		$('#'+$(this).attr('data-id')).removeClass('d-none');
	});
	if(location.pathname.indexOf('overtime-management') > -1)
	{
		var noData = '<article id="item1" class="tab-content content-visible"><div class="newsBox text-center">'+language['_overtime_no_data'][language_choice]+'</div></article>';
		var Year = +$('#year').val();
		var Month = +$('#month').val();
		getMyListOT(Year, Month);
		$('#year, #month').on('change', function(){
			getMyListOT(+$('#year').val(), +$('#month').val());
		});
	}
	if(location.pathname.indexOf('overtime-request') > -1)
	{
		var noData = '<article id="item1" class="tab-content content-visible"><div class="newsBox text-center">'+language['_overtime_no_data'][language_choice]+'</div></article>';
		var Year = +$('#year').val();
		var Month = +$('#month').val();
		console.log(Year, Month);
		getRequestListOT(Year, Month);
		$('#year, #month').on('change', function(){
			getRequestListOT(+$('#year').val(), +$('#month').val());
		});
	}
	if(location.pathname.indexOf('annual-leave-management') > -1)
	{
		var noData = '<article id="item1" class="tab-content content-visible"><div class="newsBox text-center">'+language['_overtime_no_data'][language_choice]+'</div></article>';
		var Year = +$('#year').val();
		var Month = +$('#month').val();
		getMyListAL(Year, Month);
		$('#year, #month').on('change', function(){
			getMyListAL(+$('#year').val(), +$('#month').val());
		});
	}
	if(location.pathname.indexOf('annual-leave-request') > -1)
	{
		var noData = '<article id="item1" class="tab-content content-visible"><div class="newsBox text-center">'+language['_overtime_no_data'][language_choice]+'</div></article>';
		var Year = +$('#year').val();
		var Month = +$('#month').val();
		getRequestListAL(Year, Month);
		$('#year, #month').on('change', function(){
			getRequestListAL(+$('#year').val(), +$('#month').val());
		});
	}
}
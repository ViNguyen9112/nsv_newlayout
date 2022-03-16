(function() {
    // The width and height of the captured photo. We will set the
    // width to the value defined here, but the height will be
    // calculated based on the aspect ratio of the input stream.

    var width = 0; // We will scale the photo width to this
    var height = 0; // This will be computed based on the input stream

    // |streaming| indicates whether or not we're currently streaming
    // video from the camera. Obviously, we start at false.

    var streaming = false;

    // The various HTML elements we need to configure or control. These
    // will be set by the startup() function.

    var videos = null;
    var canvas = null;
    var checkin = null;
    var checkout = null;

    function startup() {

        videos = document.getElementById('video');
        canvas = document.getElementById('canvas');

        turnOn(videos)

        checkin = document.getElementById('btnInsertCheckin');
        checkout = document.getElementById('btnInsertCheckout');

        checkin.addEventListener('click', function(ev) {
            if (streaming) {
                takepicture('insertCheckin');
                ev.preventDefault();
            }
        }, false);

        checkout.addEventListener('click', function(ev) {
            if (streaming) {
                $timecardID = $('#timecardIDCheckout').val()
                takepicture('insertCheckout', $timecardID);
                ev.preventDefault();
            }
        }, false);

        window.addEventListener('focus', function(e) {
            turnOn(videos)
        })

        window.addEventListener('blur', function(e) {
            turnOff(videos)
        })
    }

    function turnOn(videos) {
        // camera image acquisition
        navigator.mediaDevices.getUserMedia({ video: true, audio: false })
            .then(stream => {
                // On success, the video element is set to a camera image and played back.
                videos.srcObject = stream;
                videos.play();
            }).catch(error => {
                // Outputs error log in case of failure.
                console.error('mediaDevice.getUserMedia() error:', error);
                //alert('Permission denied. Please allow access your camera!')
                return;
            });

        videos.addEventListener('canplay', function(ev) {
            if (!streaming) {
                height = videos.videoHeight
                width = videos.videoWidth
                    // Firefox currently has a bug where the height can't be read from
                    // the video, so we will make assumptions if this happens.

                if (isNaN(height)) {
                    height = 500
                }
                streaming = true;
            }
        }, false);
    }

    function turnOff(videos) {
        videos.pause();
        videos.src = "";
        // videos.srcObject.getTracks()[0].stop();
        videos.srcObject.getTracks().forEach(function(track) {
            track.stop();
        });
        console.log("videos off");
    }

    // Capture a photo by fetching the current contents of the video
    // and drawing it into a canvas, then converting that to a PNG
    // format data URL. By drawing it on an offscreen canvas and then
    // drawing that to the screen, we can change its size and/or apply
    // other changes before drawing it.

    function takepicture(type, timecardIDCheckout = 0) {
        var context = canvas.getContext('2d');

        if (width && height) {
            canvas.width = width;
            canvas.height = height;
            context.drawImage(videos, 0, 0, width, height);

            var data = canvas.toDataURL('image/png');

            if ($("#currentCoord").val() != "none") {
                if (type == 'insertCheckin') {
                    // TODO:^language
                    $('#titleSignModal').html($('#text_check_in').val())

                    $('#CapturePhoto').attr("src", data)

                    // TODO:^language
                    $("#textSignModal").html($('#text_check_in_successfully').val() + '<br>' + $('#text_time_checked_in').val() + '<br>' + moment().format('HH:mm:ss'))

                    $('#TimeCheckin').html(moment().format('HH:mm:ss'))
                    $('#TimeCheckout').html('')
                    $('#signModal').modal()
                } else {
                    // TODO: ^language
                    $('#titleSignModal').html($('#text_check_out').val())

                    $('#CapturePhoto').attr("src", data)

                    // TODO: ^language
                    $("#textSignModal").html($('#text_check_out_successfully').val() + '<br>' + $('#text_time_checked_out').val() + '<br>' + moment().format('HH:mm:ss'))

                    $('#TimeCheckout').html(moment().format('HH:mm:ss'))
                    $('#signModal').modal()
                }

                setTimeout(function() {
                    $('#signModal').modal('hide')
                }, 3000)

                $.ajax({
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    type: 'POST',
                    url: __baseUrl + 'mypage/' + type,
                    data: {
                        img: data,
                        customerID: $('#CustomerName').val(),
                        coord: $("#currentCoord").val(),
                        timecardID: timecardIDCheckout
                    },
                    success: function(response) {
                        if (response.success) {
                            $("#currentCoord").val('none')

                            // insert table Distance
                            if (type == "insertCheckin") {
                                var data = response.dataStaff
                                var totalDistance = 0
                                for (var i = 0; i < data.length - 1; i++) {
                                    totalDistance += Number(getDistance({ 'lat': data[i].CheckinLocation.split(",")[0], 'lng': data[i].CheckinLocation.split(",")[1] }, { 'lat': data[i + 1].CheckinLocation.split(",")[0], 'lng': data[i + 1].CheckinLocation.split(",")[1] })
                                        .replaceAll(",", ""))

                                }
                                totalDistance = (totalDistance / 1000).toFixed(2)
                                $.ajax({
                                    headers: { 'X-CSRF-TOKEN': csrfToken },
                                    type: 'POST',
                                    url: __baseUrl + 'mypage/putDistance',
                                    data: {
                                        distance: totalDistance
                                    },
                                    success: function(res) {

                                    }
                                })
                            }
                        }
                    },
                    error: function(res) {
                        console.log('error')
                        console.log(res)
                    }
                })

            } else {
                alert($("#text_enable_location").val())
            }
        } else {
            console.log('error')
        }
    }

    var rad = function(x) {
        return x * Math.PI / 180;
    };

    function getDistance(p1, p2) {
        var R = 6378137; // Earth’s mean radius in meter
        var dLat = rad(p2.lat - p1.lat);
        var dLong = rad(p2.lng - p1.lng);
        var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(rad(p1.lat)) * Math.cos(rad(p2.lat)) *
            Math.sin(dLong / 2) * Math.sin(dLong / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        var d = R * c;
        return commafy(d.toFixed(0)); // returns the distance in meter
    };

    function commafy(num) {
        var str = num.toString().split('.');
        str[0] = str[0].replace(/(\d)(?=(\d{3})+$)/g, '$1,');
        if (str[1]) {
            str[1] = str[1].replace(/(\d{3})/g, '$1 ');
        }
        return str.join(',');
    }



    // Set up our event listener to run the startup process
    // once loading is complete.
    window.addEventListener('load', startup, false);
})();

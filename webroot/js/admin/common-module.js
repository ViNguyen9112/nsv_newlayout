let MessageModule = (function () {
    "use strict";

    let get = function (id, msg) {
        let rst = message_jp[id];
        let rep = msg;
        if (!$.isArray(msg)) {
            rep = new Array(msg);
        }
        rep.forEach(function (value, key, array) {
            let index = "{" + key + "}";
            rst = rst.replace(index, value);
        });
        return rst;
    };

    return {
        get: get,
    };
})();

/**
 * @type {{confirmPopup: *, notificationPopup: *, alertPopup: *}}
 */
var PopupModule = (function () {
    "use strict";

    var confirmPopup = function (message) {
        return new Promise((resolve, reject) => {
            swal(message, {
                buttons: {
                    cancel: "NO",
                    catch: {
                        text: "YES",
                        value: true,
                    },
                },
            }).then((value) => {
                resolve(value);
            });
        });
    };

    var notificationPopup = function (message) {
        var model_obj = $("#notification-modal-box");
        if (model_obj.length == 0) {
            console.warn("There are no notification-modal-box element");
            return;
        }
        model_obj.find(".notification-modal-content").multiline(message);
        model_obj.fadeIn(300).delay(1500).fadeOut(400);
    };

    var alertPopup = function (message) {
        return new Promise((resolve, reject) => {
            swal(message, {
                buttons: {
                    catch: {
                        text: "OK",
                        value: true,
                    },
                },
                className: "swal-full-width",
            }).then((value) => {
                resolve(value);
            });
        });
    };

    var errorPopup = function (message) {
        return new Promise((resolve, reject) => {
            swal(message, {
                buttons: {
                    catch: {
                        text: "OK",
                        value: true,
                    },
                },
                className: "swal-errors-div",
            }).then((value) => {
                resolve(value);
            });
        });
    };

    // confirm with 2 message
    var confirmPopuWithContent = function (title, text) {
        return new Promise((resolve, reject) => {
            swal({
                title: title,
                text: text,
                buttons: {
                    cancel: "NO",
                    catch: {
                        text: "YES",
                        value: true,
                    },
                },
                className: "swal-full-width",
            }).then((value) => {
                resolve(value);
            });
        });
    };

    return {
        confirmPopup: confirmPopup,
        alertPopup: alertPopup,
        errorPopup: errorPopup,
        notificationPopup: notificationPopup,
        confirmPopuWithContent: confirmPopuWithContent,
    };
})();

function outputPasswordConfirm() {
    swal("Password", {
        content: {
            element: "input",
            attributes: {
                type: "password",
                id: "input-text-delete-password",
                size: 10,
            }
        },
        buttons: {
            cancel: "NO",
            confirm: {
                text: "YES",
                value: "catch",
            }
        },
        className: "swal-password",
    })
        .then((value) => {
            outputPassword();
        });
}

function outputPassword() {
    var password = $("#input-text-delete-password").val();

    $.ajax({
        type: 'post',
        url: __baseUrl + 'ajax/confirmPassword',
        headers: {'X-CSRF-TOKEN': __csrfToken},
        data: {
            password: password
        }
    })
        .done(function (response) {
            $("#input-text-delete-password").val('');
            if (0 === parseInt(response.success)) {
                $(".container-fluid .card-body").html('You don\'t have permission to use this function.');
            }
        });
}

$('.modal-dialog').draggable({
    handle: ".modal-header"
});

$('form input').keydown(function (e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        sKeyword = $(this).val()
        $('#filterStaff').click()
        return false;
    }
});

var sRegion = ''
var sAreaID = ''
var sKeyword = ''

// ************* DATATABLE *************
var table = $('#serverDataTable').DataTable({
    "dom": '<"pull-left"fi>tp',
    "processing": true,
    "serverSide": true,
    "ajax": {
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/customer',
        type: 'POST',
        data: function(d) {
            d.region = sRegion;
            d.area = sAreaID;
            d.search.value = sKeyword;
        }
    },
    "columns": [{
            "data": null,
            "sortable": false,
            "orderable": false,
            "sClass": "text-right",
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
            "data": "CustomerID",
            "sClass": "new-tag text-center",
            render: function(data, type, row) {
                if (parseInt(row.isNew) === 0) {
                    return row.CustomerID
                }
                else {
                    return '<span class="New-Icon">New</span>' +
                        row.CustomerID
                }
            }
        },
        { "data": "Name" },
        {
            "data": "Region",
            "sClass": "text-center",
            render: function(data, type, row) {
                var result = Object.keys(areas).map(key => ({ key, value: areas[key] }));
                for(var z = 0; z < result.length; z++) {
                    var e = result[z]
                    if (e.key == row.TBLMArea.Region) {
                        return e.value;
                    }
                }
                return "";
            }
        },
        { "data": "TBLMArea.Name" },
        { "data": "Address" },
        { "data": "Longitude", "sClass": "text-center" },
        { "data": "Latitude", "sClass": "text-center" },
        {
            "data": "ImplementDate",
            "sClass": "text-center",
            render: function(data, type, row) {
                return (row.ImplementDate) ? moment(row.ImplementDate).format('YYYY/MM/DD') : ""

            }
        },
        {
            data: "ID",
            orderable: false,
            render: function(data, type, row) {
                return "" +
                    "<input type=\"hidden\" id=\"ID\" value=\"" + row.ID + "\">\n" +
                    "<button type=\"button\" class=\"btn btn-info btnEditCustomer\"><i class=\"far fa-edit\"></i></button>\n" +
                    "<button type=\"button\" class=\"btn btn-danger btnDeleteCustomer\"><i class=\"far fa-trash-alt\"></i></button>";
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

// $('#ImplementDate').datepicker({
//     format: 'yyyy/mm/dd',
//     autoclose: true,
//     todayHighlight: true,
//     orientation: "bottom",
// });

let map_events = []
$(document).ready(function() {
    // $('#ImplementDate').on('click', function() {
    //     $('#ImplementDate').datepicker('update', $('#ImplementDate').val())
    // })

    // ************* SHOW MODAL *************
    $('#show-modal-customer').on('click', function() {
        clearCustomerForm()
        $('#modalCustomer').modal()
    })

    // ************* SHOW EDIT STAFF MODAL *************
    $('#tblCustomer').on('click', '.btnEditCustomer', function() {
        let id = $(this).closest('tr').find("#ID").val()
        formCustomerEdit(id)
    })

    // *************** SUBMIT ADD/EDIT STAFF ****************
    $('#btnSubmitCustomer').on('click', function() {
        putCustomer()
    })

    // *************** CHECK CHANGE AREA ****************
    $('#AreaID').on('change', function() {
        var regionValue = $(this).find(':selected').data('region');
        $('#Region').val(regionValue)
    })

    // **************** DELETE STAFF *************
    $('#tblCustomer').on('click', '.btnDeleteCustomer', function() {
        swal({
                title: "Are you sure you want to delete this customer?",
                icon: "error",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    let id = $(this).closest('tr').find("#ID").val()
                    delCustomer(id)
                }
            })

    })

    //  ************* SHOW MAP **********
    $('#Longitude').on('input', function() {
        if ($(this).val().length && $('#Latitude').val().length) {
            $('#btnMapCustomer').prop('disabled', false)
        }
    })
    $('#Latitude').on('input', function() {
        if ($(this).val().length && $('#Longitude').val().length) {
            $('#btnMapCustomer').prop('disabled', false)
        }
    })
    $('#btnMapCustomer').on('click', function(e) {
        e.preventDefault()
        const long = $('#Longitude').val()
        const lat = $('#Latitude').val()
        map_events[0] = { "lat": lat, "long": long }
        MapModule.initGoogleMap(map_events)
        MapModule.addLocationsToMap(map_events)
        $("#modalCustomer").modal('hide')
        $("#eventMapModal").modal()
    })
    $("#close-map").click(function() {
        //do something
        $("#modalCustomer").modal('show')
        $("#eventMapModal").modal('hide')
    });

    // **************** SEARCH AREA *************
    // Region
    $(document).on('change', '#sRegion', function(e) {
        sRegion = $(this).val()

        getArea(sRegion)


        $('#filterStaff').click()
    })

    // Region
    $(document).on('change', '#sAreaID', function(e) {
        sAreaID = $(this).val()
        $('#filterStaff').click()
    })

    // Region
    $(document).on('change', '#sKeyword', function(e) {
        sKeyword = $(this).val()
        $('#filterStaff').click()
    })
});

// clear filter
$(document).on('click', '#clearFilter', function(e) {
    e.preventDefault()
    $("#sKeyword").val("");
    sKeyword = ''
    $("#sRegion").val(null);
    sRegion = ''
    $("#sAreaID").val(null);
    sAreaID = ''
    // reset table
    $('#filterStaff').click()
})

// apply filter
$(document).on('click', '#filterStaff', function() {
    table.ajax.reload();
})

/**
 *
 * @param region
 */
function getArea(region) {
    $.ajax({
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/schedule/getArea',
        type: 'POST',
        data: {
            'region': region
        },
        success: function(response) {
            var list = '<option value=""></option>'
            $.each(response, function(index, value){
                list +=  '<option value="'+ value.AreaID +'">【'+ value.AreaID + "】" + value.Name +'</option>'
            })
            $('#sAreaID').html(list)
        }
    })
}

/**
 *
 */
function clearCustomerForm() {
    $('.input-customer').val('')
    $('#Region').val('')
        // handle CustomerID when clicked Edit
    $('#spanCustomerID').html('')
    $('#spanCustomerID').css('display', 'none')
    $('#CustomerID').show()
    $('#btnMapCustomer').prop('disabled', true)
}

/**
 *
 * @param id
 */
function formCustomerEdit(id) {
    clearCustomerForm()
    $.ajax({
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/customer/search',
        type: 'post',
        data: { 'id_customer': id },
        success: function(response) {
            if (response.success) {
                const data = response.data
                $('#id_customer_form').val(data.ID)
                    // handle CustomerID
                $('#CustomerID').hide()
                $('#spanCustomerID').html(data.CustomerID)
                $('#spanCustomerID').css('display', 'block')

                $('#Name').val(data.Name)
                $('#AreaID').val(data.AreaID)
                $('#Address').val(data.Address)
                if(data.ImplementDate != null){
                    $('#ImplementDate').val(moment(data.ImplementDate).format('YYYY/MM/DD'))
                }
                $('#PositionNo').val(data.PositionNo)
                $('#TaxCode').val(data.TaxCode)
                $('#Longitude').val(data.Longitude)
                $('#Latitude').val(data.Latitude)
                if (data.TBLMArea != null) {
                    $('#Region').val(data.TBLMArea.Region)
                }
                if (data.Longitude && data.Latitude) {
                    $('#btnMapCustomer').prop('disabled', false)
                }
                $('#modalCustomer').modal()
            }
        },
        error: function(response) {
            console.log(response)
        }
    })
}

/**
 *
 */
function putCustomer() {
    let check = validateform()
    if (check) {
        var data = {}
        if ($('#spanCustomerID').html() != "") {
            data = {
                'ID': $('#id_customer_form').val(),
                'Name': $('#Name').val(),
                'AreaID': $('#AreaID').val(),
                'Address': $('#Address').val(),
                'ImplementDate': $('#ImplementDate').val(),
                'PositionNo': $('#PositionNo').val(),
                'TaxCode': $('#TaxCode').val(),
                'Longitude': $('#Longitude').val(),
                'Latitude': $('#Latitude').val()
            }
        } else {
            data = {
                'ID': $('#id_customer_form').val(),
                'CustomerID': $('#CustomerID').val(),
                'Name': $('#Name').val(),
                'AreaID': $('#AreaID').val(),
                'Address': $('#Address').val(),
                'ImplementDate': $('#ImplementDate').val(),
                'PositionNo': $('#PositionNo').val(),
                'TaxCode': $('#TaxCode').val(),
                'Longitude': $('#Longitude').val(),
                'Latitude': $('#Latitude').val()
            }
        }
        $.ajax({
            url: __baseUrl + 'admin/customer/edit',
            headers: { 'X-CSRF-Token': __csrfToken },
            type: 'post',
            data: data,
            success: function(response) {
                if (parseInt(response.status) === 1) {
                    $('#modalCustomer').modal('hide')
                        // clearCustomerForm()
                        // loadCustomerTable(response.lst_customers)
                    swal({
                            title: "Successfully!",
                            icon: "success",
                        })
                        .then((reload) => {
                            if (reload) {
                                location.reload()
                            }
                        })
                        // table.order([[Number($('#currIndexSort').val()), $('#currDirSort').val()]]).draw()

                } else {
                    swal({
                        title: "Have error. Please double check that the customer ID is not duplicates in the database.",
                        icon: "error",
                    })

                }
            },
            error: function(response) {
                console.log(response)
            }
        })
    }
}

/**
 *
 * @param id
 */
function delCustomer(id) {
    $.ajax({
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/customer/delete',
        type: 'post',
        data: { 'id_customer': id },
        success: function(response) {
            if (response.status) {
                // loadCustomerTable(response.lst_customers)
                swal({
                    'title': 'Deleted Successfully!',
                    'icon': 'success'
                }).then((OK) => {
                    location.reload()
                });
            }
        },
        error: function(response) {
            console.log(response)
        }
    })
}

/**
 *
 * @param lst_customers
 */
function loadCustomerTable(lst_customers) {
    $('#serverDataTable').DataTable().clear().draw();
    var table = $('#serverDataTable').DataTable();
    for (i = 0; i < lst_customers.length; i++) {
        table.rows.add($(
            '<tr>' +
            '<td class="text-center">' + lst_customers[i].CustomerID + '</td>' +
            '<td>' + lst_customers[i].Name + '</td>' +
            '<td>' + lst_customers[i]['TBLMArea'].Name + '</td>' +
            '<td>' + lst_customers[i].Address + '</td>' +
            '<td>' + lst_customers[i].Latitude + '</td>' +
            '<td>' + lst_customers[i].Longitude + '</td>' +
            '<td class="text-center">' + lst_customers[i].TaxCode + '</td>' +
            '<td class="text-center w-10">\n' +
            '    <input type="hidden" id="ID" value="' + lst_customers[i].ID + '">\n' +
            '    <button type="button" class="btn btn-info btnEditCustomer"><i class="far fa-edit"></i></button>\n' +
            '    <button type="button" class="btn btn-danger btnDeleteCustomer"><i class="far fa-trash-alt"></i></button>\n' +
            '</td>' +
            '</tr>'
        )).draw();
    }
}

function validateform() {
    return true;
}

var MapModule = (function() {
    var mapElement = document.getElementById("MAPCONTENT");
    var mapInstance = null;

    var initGoogleMap = function() {
        mapInstance = new google.maps.Map(mapElement, {
            zoom: 13,
            //center: new google.maps.LatLng(15.967674, 108.020437),
            center: new google.maps.LatLng(map_events[0].lat, map_events[0].long),
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
    }

    var addLocationsToMap = function(map_events) {
        console.log(map_events)
            // Create markers.
        for (i = 0; i < map_events.length; i++) {
            new google.maps.Marker({
                position: new google.maps.LatLng(map_events[i].lat, map_events[i].long),
                map: mapInstance,
                title: map_events[i].CustomerName
            });
        }
    }

    return {
        initGoogleMap: initGoogleMap,
        addLocationsToMap: addLocationsToMap
    }
})()

$('#serverDataTable th').on('click', function() {
    setSort($(this))
})

function setSort(self) {
    setTimeout(function() {
        var index = $('th:contains(' + self.html() + ')').index()
        var dir = ''
        if (index != 0) {
            var name_col = self.html()
            if (name_col == 'Name') {
                name_col = 'Customer Name'
            }
            $('#currIndexSort').val(index)
            dir = (self.attr('aria-sort') == 'ascending') ? "asc" : "desc"
            $('#currDirSort').val(dir)

            $.ajax({
                url: __baseUrl + 'admin/customer/sessionSort',
                headers: { 'X-CSRF-Token': __csrfToken },
                type: 'post',
                data: { 'col': name_col, 'dir': dir },
            })
        }
    }, 1000)
}

function beforeRender() {
    var col = $('#currIndexSort').val()
    var dir = $('#currDirSort').val()
    table.order([Number(col), dir]).draw()
}
window.onload = beforeRender()

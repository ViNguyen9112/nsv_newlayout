$('#datepicker_date, #datepicker_date_to').datepicker({
    format: 'yyyy/mm/dd',
    daysOfWeekHighlighted: "1",
    todayHighlight: true,
    toggleActive: true,
	autoclose: true
});

var fromdate = '';
var todate = '';
var sKeyword = ''

var table = $('#serverDataTable').DataTable({
    "dom": '<"pull-left"fi>tp',
    "processing": true,
    "serverSide": true,
    "ajax": {
        headers: { 'X-CSRF-TOKEN': __csrfToken },
        url: __baseUrl + 'admin/overtime',
        type: 'POST',
        data: function(d) {
            d.fromdate = fromdate;
            d.todate = todate;
			if(sKeyword.length)
			{
				d.search.value = sKeyword;
			}
        }
    },
    "columns": [
		{
            "data": null,
            "sortable": false,
            "orderable": false,
            "sClass": "text-right",
            render: function(data, type, row, meta) {
                return meta.row + meta.settings._iDisplayStart + 1;
            }
        },
        {
            "data": "TBLTOverTime.DetailInfo.StaffID",
            "sClass": "new-tag text-center",
            render: function(data, type, row) {
                return row.TBLTOverTime.DetailInfo.StaffID;
            }
        },
		{
            "data": "TBLTOverTime.DetailInfo.Name",
            "sClass": "new-tag text-center",
            render: function(data, type, row) {
                return row.TBLTOverTime.DetailInfo.Name;
            }
        },
		{
            "data": "TBLTOverTime.DetailInfo.Position",
            "sClass": "new-tag text-center",
            render: function(data, type, row) {
                return row.TBLTOverTime.DetailInfo.Position;
            }
        },
		{
            "data": "TBLTOverTime.StartTime",
            "sClass": "text-center",
            render: function(data, type, row) {
				return moment(row.TBLTOverTime.StartTime).format('YYYY/MM/DD');
            }
        },
		{
            "data": "TBLTOverTime.StartTime",
            "sClass": "text-center",
            render: function(data, type, row) {
				return moment(row.TBLTOverTime.StartTime).format('HH:mm');
            }
        },
		{
            "data": "TBLTOverTime.EndTime",
            "sClass": "text-center",
            render: function(data, type, row) {
				return moment(row.TBLTOverTime.EndTime).format('HH:mm');
            }
        },
		{
            "data": "TBLTOverTime.Total",
            "sClass": "text-left",
            render: function(data, type, row) {
				var timeConvert = function(n) {
					var num = n;
					var hours = (num / 60);
					var rhours = Math.floor(hours);
					var minutes = (hours - rhours) * 60;
					var rminutes = Math.round(minutes);
					if(rhours > 0)
					{
						if(rminutes > 0)
						{
							return rhours+" hour(s) " + rminutes + " minutes(s)";	
						}
						else
						{
							return rhours+" hour(s) ";
						}
					}
					else
					{
						return rminutes + " "+"minutes(s)";
					}
				}
				return timeConvert(row.TBLTOverTime.Total);
            }
        },
		{
            "data": "TBLTOverTime.Status",
            "sClass": "text-left",
            render: function(data, type, row) {
				var renderStatus = function(status){
					var icon = true;
					if(status === 1)
					{
						if(typeof icon !== 'undefined')
						{
							return '<i style="color:#02b921" class="fa fa-check-circle" aria-hidden="true"></i> Approved';
						}
					}
					else if(status === 2)
					{
						if(typeof icon !== 'undefined')
						{
							return '<i style="color:#F00" class="fa fa-ban" aria-hidden="true"></i> Reject';
						}
					}
					else
					{
						if(typeof icon !== 'undefined')
						{
							return '<i style="color:#014ffb" class="fa fa-clock" aria-hidden="true"></i> Pending';
						}
					}
				}
				if(row.TBLTOverTime.Status === 0 && row.TBLTOverTime.DetailInfo.Position === 'Area Leader')
				{
					return "" +
                    "<button data-action=\"approve\" data-id=\""+row.TBLTOverTime.ID+","+row.ID+"\" type=\"button\" class=\"btn btn-success btn-approve\"><i class=\"fa fa-check-circle\"></i></button>\n" +
                    "<button data-action=\"refuse\" data-id=\""+row.TBLTOverTime.ID+","+row.ID+"\" type=\"button\" class=\"btn btn-danger  btn-refuse\"><i class=\"fa fa-ban\"></i></button>";
				}
				else
				{
					return renderStatus(row.TBLTOverTime.Status)+'<br><i style="color:#02b921" class="fa fa-user" aria-hidden="true"></i> '+row.TBLMStaff.StaffID+' '+row.TBLMStaff.Name;
				}
				
            }
        }
    ],
    "searching": true,
    "paging": true,
    "pageLength": 200,
    "info": true,
    "order": [
        [1, 'asc']
    ],
});

$(document).on('change', '#datepicker_date', function(e) {
	fromdate = $(this).val();
	table.draw();
});

$(document).on('change', '#datepicker_date_to', function(e) {
	todate = $(this).val()
	table.draw();
});

$('form input').keydown(function (e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        sKeyword = $(this).val();
		table.draw();
        return false;
    }
});

$(document).on('change', '#sKeyword', function(e) {
	sKeyword = $(this).val()
	table.draw();
});

$('#filterStaff').on('click', function(){
	fromdate = $('#datepicker_date').val();
	todate = $('#datepicker_date_to').val();
	sKeyword = $('#sKeyword').val();
	table.draw();
});

$('#clearFilter').on('click', function(){
	$('#datepicker_date').val('');
	$('#datepicker_date_to').val('');
	$('#sKeyword').val('');
	fromdate = '';
	todate = '';
	sKeyword = '';
	table.draw();
});

$(document).on('click', '.btn-approve, .btn-refuse', function(){
	var _self = $(this);
	var id = $(this).attr('data-id').split(',');
	var action = $(this).attr('data-action');
	var actionRequest = $.ajax({
		headers: {'X-CSRF-TOKEN': __csrfToken},
		url: __baseUrl + 'admin/overtime/edit',
		method: "POST",
		data: { 
			'Type': "action",
			'ID' : id,
			'Action' : action
		},
		dataType: "json"
	});
	actionRequest.done(function(res) {
		table.draw();
	});
	actionRequest.fail(function( jqXHR, textStatus ) {
		console.log(textStatus);
	});
});
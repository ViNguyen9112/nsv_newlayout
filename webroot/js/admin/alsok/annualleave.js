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
        url: __baseUrl + 'admin/annual-leave',
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
            "data": "TBLTAnnualLeave.DetailInfo.StaffID",
            "sClass": "new-tag text-center",
            render: function(data, type, row) {
                return row.TBLTAnnualLeave.DetailInfo.StaffID;
            }
        },
		{
            "data": "TBLTAnnualLeave.DetailInfo.Name",
            "sClass": "new-tag text-center",
            render: function(data, type, row) {
                return row.TBLTAnnualLeave.DetailInfo.Name;
            }
        },
		{
            "data": "TBLTAnnualLeave.DetailInfo.Position",
            "sClass": "new-tag text-center",
            render: function(data, type, row) {
                return row.TBLTAnnualLeave.DetailInfo.Position;
            }
        },
		{
            "data": "TBLTAnnualLeave.FromDate",
            "sClass": "text-center",
            render: function(data, type, row) {
				return moment(row.TBLTAnnualLeave.FromDate).format('YYYY/MM/DD');
            }
        },
		{
            "data": "TBLTAnnualLeave.ToDate",
            "sClass": "text-center",
            render: function(data, type, row) {
				return moment(row.TBLTAnnualLeave.ToDate).format('YYYY/MM/DD');
            }
        },
		{
            "data": "TBLTAnnualLeave.Total",
            "sClass": "text-left",
            render: function(data, type, row) {
				var numberWithCommas = function(x) {
					return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
				}
				var dayConvert = function(d) {
					return numberWithCommas(d)+" day(s)";
				}
				return dayConvert(row.TBLTAnnualLeave.Total);
            }
        },
		{
            "data": "TBLTAnnualLeave.Reason"
        },
		{
            "data": "TBLTAnnualLeave.DetailLeader.StaffID",
			"sClass": "text-left",
            render: function(data, type, row) {
				try {
					return '<i style="color: #02b921" class="fa fa-check-circle" aria-hidden="true"></i> '+row.TBLTAnnualLeave.DetailLeader.StaffID+' - '+row.TBLTAnnualLeave.DetailLeader.TBLMStaff.Name;
				} catch(e) {
					return '<i style="color: #02b921" class="fa fa-check-circle" aria-hidden="true"></i> '+row.TBLTAnnualLeave.DetailInfo.StaffID+' - '+row.TBLTAnnualLeave.DetailInfo.Name;
				}
				
            }
        },
		{
            "data": "TBLTAnnualLeave.Status",
            "sClass": "text-left",
            render: function(data, type, row) {
				var renderStatus = function(status){
					var icon = true;
					if(status === 3)
					{
						if(typeof icon !== 'undefined')
						{
							return '<i style="color: #02b921" class="fa fa-check-circle" aria-hidden="true"></i> '+row.StaffID+' - '+row.TBLMStaff.Name;
						}
					}
					else if(status === 2)
					{
						if(typeof icon !== 'undefined')
						{
							return '<i style="color:#F00" class="fa fa-ban" aria-hidden="true"></i> '+row.StaffID+' - '+row.TBLMStaff.Name;;
						}
					}
					else
					{
						if(typeof icon !== 'undefined')
						{
							return '<i style="color:#014ffb" class="fa fa-clock" aria-hidden="true"></i> '+row.StaffID+' - '+row.TBLMStaff.Name;;
						}
					}
				}
				if(row.TBLTAnnualLeave.Status === 1)
				{
					return "" +
                    "<button data-action=\"approve\" data-id=\""+row.TBLTAnnualLeave.ID+","+row.ID+"\" type=\"button\" class=\"btn btn-success btn-sm btn-approve\"><i class=\"fa fa-check-circle\"></i></button>\n" +
                    "<button data-action=\"refuse\" data-id=\""+row.TBLTAnnualLeave.ID+","+row.ID+"\" type=\"button\" class=\"btn btn-danger btn-sm btn-refuse\"><i class=\"fa fa-ban\"></i></button>";
				}
				return renderStatus(row.TBLTAnnualLeave.Status);
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
		url: __baseUrl + 'admin/annual-leave/edit',
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
<?php
echo $this->Html->css('calendar/fullcalendar.css', ['block' => 'head-end']) . PHP_EOL;
echo $this->Html->css('calendar/datepicker.css', ['block' => 'head-end']) . PHP_EOL;
echo $this->Html->css('calendar/jquery-ui.css', ['block' => 'head-end']) . PHP_EOL;
?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<style>
.datepicker table {
    margin: 0;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -khtml-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    font-size: 16px;
}
    .Staff-ID {
        position: relative;
    }

    .tooltip-distance {
        display: none;
        position: absolute;
        z-index: 100;
        border: 1px;
        background-color: white;
        border: 1px solid #2c7090;
        padding: 3px;
        color: #2c7090;
        top: 15px;
        left: 65px;
        width: 300px;
        text-align: left;
    }

    .Staff-ID:hover .tooltip-distance {
        display: block;
    }


    .Report-ID {
        position: relative;
    }

    .tooltip-report {
        display: none;
        position: absolute;
        z-index: 100;
        border: 1px;
        background-color: white;
        border: 1px solid #2c7090;
        padding: 3px;
        color: #2c7090;
        top: 15px;
        left: 65px;
        width: auto;
        text-align: left;
        min-width: 100px;
    }

    .Report-ID:hover .tooltip-report {
        display: block;
    }

    .input-group-addon {
        cursor: pointer;
    }

    .form-control {
        border: 1px solid #ccc;
        box-shadow: none;

    &
    :hover,

    &
    :focus,

    &
    :active {
        box-shadow: none;
    }

    &
    :focus {
        border: 1px solid #34495e;
    }

    }

    .form-control:disabled, .form-control[readonly] {
        background-color: initial;
        /*opacity: 1;*/
    }

    table.table-bordered.dataTable tbody th, table.table-bordered.dataTable tbody td {
        border-bottom-width: 0;
        vertical-align: middle;
    }

</style>
<link rel="stylesheet"
      href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css"
      integrity="sha512-TQQ3J4WkE/rwojNFo6OJdyu6G8Xe9z8rMrlF9y7xpFbQfW5g8aSWcygCQ4vqRiJqFsDsE1T6MoAOMJkFXlrI9A=="
      crossorigin="anonymous"/>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">Delete Image</h6>
    </div>
    <div class="card-body">
        <div class="col-lg-6">
            <?php echo $this->Form->create(null, ['class' => 'form-inline', 'id' => 'tblDelete']); ?>
            <div class="card-body">
			<table class="table table-borderless" style="font-size:16px; ">
                <tbody>
                <tr>
                    <td class="text-center" style="vertical-align: middle">1</td>
                    <td style="vertical-align: middle; font-weight:bold">Delete Face Image</td>
                    <td style="display:flex; ">
                        <div class="form-group" >
                            <span style="color:#000;margin-right:3px;font-size:14px; width:30px;; text-align:left">To</span>
                            <input type="text" style="font-size:16px; width:160px" class="form-control" value="<?=date('Y/m/d', strtotime("-2 month"))?>" name="datepicker_face_to" id="datepicker_face_to" size="10" placeholder="Date" style="width:120px">
                            <i class="far fa-calendar-alt" style=" position: relative; right: 30px;font-size: 14px;color: #000;"></i>
                        </div>
                    </td>
                    <td class=" text-center" style="width:100px">
                        <button total-image="<?php echo @$total_face?>" type="button" data-toggle="tooltip" title="Edit" class="btn btn-info btn-delete-face" style="width:100px; padding:5px; height: 36px;">
                            <i class="far fa-file-excel mr-2"></i>Delete
                        </button>
                    </td>
                </tr>
                <tr>
                    <td class="text-center"  style="vertical-align: middle; font-weight-bold">2</td>
                    <td style="vertical-align: middle; font-weight:bold">Delete Report Image</td>
                    <td style="display:flex; ">
                        <div class="form-group" >
                            <span style="color:#000;margin-right:3px;font-size:14px; width:30px;; text-align:left">To</span>
                            <input style="font-size:16px; width:160px" type="text" class="form-control" value="<?=date('Y/m/d', strtotime("-2 month"))?>" name="datepicker_report_to" id="datepicker_report_to" size="10" placeholder="Date" style="width:120px">
                            <i class="far fa-calendar-alt" style=" position: relative; right: 30px;font-size: 14px;color: #000;"></i>
                        </div>
                    </td>
                    <td class=" text-center" style="width:100px">
                        <button total-image="<?php echo @$total_report?>" type="button" data-toggle="tooltip" title="Edit" class="btn btn-info btn-delete-report" style="width:100px; padding:5px; height: 36px;">
                            <i class="far fa-file-excel mr-2"></i>Delete
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
            <?php echo $this->Form->hidden("target", ['id' => 'target']); ?>
            <?php echo $this->Form->end(); ?>
			</div>
        </div>
    </div>
</div>

<?php
echo $this->Html->script('calendar/moment.min.js', ['block' => 'scriptBottom']);
echo $this->Html->script('calendar/fullcalendar.js', ['block' => 'scriptBottom']);
echo $this->Html->script('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['block' => 'scriptBottom']);
echo $this->Html->script('jquery.ui.monthpicker.js', ['block' => 'scriptBottom']);
echo $this->Html->script('admin/delete/index.js?v=' . date('YmdHis'), ['block' => 'scriptBottom']);
?>

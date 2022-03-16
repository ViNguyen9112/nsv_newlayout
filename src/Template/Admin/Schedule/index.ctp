<?php
    echo $this->Html->css('calendar/fullcalendar.css', ['block' => 'head-end']) . PHP_EOL;
    echo $this->Html->css('calendar/datepicker.css', ['block' => 'head-end']) . PHP_EOL;
    echo $this->Html->css('calendar/jquery-ui.css', ['block' => 'head-end']) . PHP_EOL;
    echo $this->Html->css('admin/style_schedule.css?v=123', ['block' => 'head-end']) . PHP_EOL;
?>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<style>
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

    .form-control:disabled, .form-control[disabled] {
         background-color: initial;
        /*opacity: 1;*/
    }

   .disabled-check{
        
        pointer-events: none;
    }
</style>
<link rel="stylesheet"
      href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css"
      integrity="sha512-TQQ3J4WkE/rwojNFo6OJdyu6G8Xe9z8rMrlF9y7xpFbQfW5g8aSWcygCQ4vqRiJqFsDsE1T6MoAOMJkFXlrI9A=="
      crossorigin="anonymous"/>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Check Staff's Schedules</h6>
    </div>
    <div class="card-body pb-0">
        <div class="pt-1"></div>

        <div class="row ">
            <div class="col-md-12  mx-auto text-center form p-8">

                <form class="form-inline" action="#">
                <table class="table table-borderless mb-0 p-0" style="width:auto">
                    <tbody>
                    <tr>
                        <th scope="row" colspan="4" style=" min-width: 810px; padding-right: 0px;">
                            <div class="form-group  mb-2">
                                <div class="form-group" style="width:220px">
                                    <select id="multiple-select" name="staffIds" class="sStaffID form-control " style="width:200px" place>
                                        <option value=""></option>
                                        <?php foreach ($staffIds as $staffId => $value) : ?>
                                            <option value="<?= $staffId ?>"><?= $value ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group" style="width:200px">
                                    <span style="color:#000;margin-right:3px;font-size:14px; width:50px; text-align:left">From</span>
                                    <input type="text" class="form-control" value="<?= @$params['datepicker'] ?>" name="datepicker" id="datepicker_date" size="10" placeholder="Date" style="width:120px">
                                    <input type="hidden" class="form-control" id="default_datepicker" value=""><i class="far fa-calendar-alt" style=" position: relative; right: 30px;font-size: 14px;color: #000;"></i>
                                </div>
                                <div class="form-group" style="width:175px">
                                    <span style="color:#000;margin-right:3px;font-size:14px; width:30px;; text-align:left">To</span>
                                    <input type="text" class="form-control" value="<?= @$params['datepicker'] ?>" name="datepicker" id="datepicker_date_to" size="10" placeholder="Date" style="width:120px">
                                    <input type="hidden" class="form-control" id="default_to_datepicker" value=""><i class="far fa-calendar-alt" style=" position: relative; right: 30px;ont-size: 14px;color: #000;"></i>
                                </div>
                                <div class="form-group  " style="width:300px">
                                    <span style="color:#000;margin-right:3px;font-size:14px; width:60px; text-align:left ">Region</span>
                                    <select name="" class="form-control" id="Region" style="width: 230px">
                                        <option value=""></option>
                                        <?php foreach($this->Common->getAreaByPosition() as $key => $area): ?>
                                            <option value="<?=$key?>"><?=$area?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </th>
                        <td rowspan="2">
                            <div class="img-but" id="btnMapSchedule"><?php echo $this->Html->image('admin/but-map.png', ['alt' => 'logo']); ?></div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" colspan="4">
                            <div class="form-group  mb-0 ">
                                <div class="form-group" style="width:220px">
                                    <select id="multiple-select-customer" name="customerIds" class="sCustomerID form-control " style="width:200px">
                                        <option value=""></option>
                                        <?php foreach ($customerIds as $customerId => $value) : ?>
                                            <option value="<?= $customerId ?>"><?= $value ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div class="form-group" style="width:200px">
                                    <span style="color:#000;margin-right:3px;font-size:14px; width:50px; text-align:left">From</span>
                                    <input type="time" class="form-control" name="datepicker" id="datepicker_time" value="00:00" size="10" style="width:120px">
                                </div>
                                <div class="form-group" style="width:175px">
                                    <span style="color:#000;margin-right:3px;font-size:14px; width:30px; text-align:left">To</span>
                                    <input type="time" class="form-control" name="datepicker" id="datepicker_time_to" value="23:59" size="10" style="width:120px">
                                </div>

                                <div class="form-group selectBox" style="width:350px">
                                    <span style="color:#000;margin-right:3px;font-size:14px ;width:60px; text-align:left ">Area</span>
                                    <select id="AreaID" class="form-control form-popup input-customer" style="width: 230px">
                                        <option></option>
                                        <?php foreach ($listArea as $each): ?>
                                            <option data-region="<?php echo $each->Region?>" value="<?php echo $each->AreaID?>"><?php echo '【' .$each->AreaID. '】' .$each->Name?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                        </th>
                    </tr>
                    <tr>
                        <th colspan="6" scope="row">
                            <div class="form-group" style="width:200px">
                                <button type="button" id="filterSchedule" class="btn btn-primary"  style="width:70px;margin-right: 16px;height: 32px;line-height: 12px; display: none">
                                    Filter
                                </button>
                                <button type="button" id="clearFilter" class="btn btn-danger" style="width:70px;height: 32px;line-height: 12px;">
                                    Clear
                                </button>
                            </div>
                        </th>
                    </tr>
                    </tbody>
                </table>
                </form>
            </div>
        </div>
    </div>

    <div class="card-body pt-0">
        <div class="row ">
            <div class="col-md-12">
                <div class="table ">
                    <table class="table table-bordered" id="serverDataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Customer Name</th>
                            <th>GPS</th>
                            <th>Distance</th>
                            <th>Report</th>
                            <th>Face Image</th>
                            <th>Region</th>
                            <th>Area</th>
                        </tr>
                        </thead>
                        <tfoot>
                        </tfoot>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>

<!--            <div class="col-md-4 p-0" style="display: none">-->
<!--                <div id="staffScheduleMap" style="min-height: 550px; height: 550px"></div>-->
<!--            </div>-->
        </div>
    </div>
    <input type="hidden" id="Auth" value="<?php echo $auth->StaffID; ?>">
</div>

<!-- temp values -->
<?php
$col = 3;
$dir = 'desc';
if (isset($sort)) {
    if (isset($sort['col']) && isset($sort['dir'])) {
        if (strpos($sort['col'], 'ID') !== false) {
            $col = 1;
        } else if ($sort['col'] == 'Staff Name') {
            $col = 2;
        } else if ($sort['col'] == 'Customer Name') {
            $col = 5;
        } else {
            $col = 3;
        }
        $dir = $sort['dir'];
    }
}
?>
<input type="hidden" id="currIndexSort" value="<?php echo $col; ?>">
<input type="hidden" id="currDirSort" value="<?php echo $dir; ?>">

<?php echo $this->element('Admin/popup_view_image'); ?>
<?php echo $this->element('Admin/popup_info_staff'); ?>
<?php echo $this->element('Admin/popup_info_customer'); ?>
<!-- popup for gps -->
<?php echo $this->element('Admin/popup_gps'); ?>
<!-- popup for report -->
<?php echo $this->element('Admin/popup_event_admin'); ?>
<!-- popup for face -->
<?php echo $this->element('Admin/popup_face_admin'); ?>
<!-- popup for map -->
<?php echo $this->element('Admin/popup_schedule_map'); ?>

<?php
echo $this->Html->script('calendar/moment.min.js', ['block' => 'scriptBottom']);
echo $this->Html->script('calendar/fullcalendar.js', ['block' => 'scriptBottom']);
echo $this->Html->script('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['block' => 'scriptBottom']);
echo $this->Html->script('jquery.ui.monthpicker.js', ['block' => 'scriptBottom']);
echo $this->Html->script('admin/schedule/index.js?v=' . date('YmdHis'), ['block' => 'scriptBottom']);
?>

<script type="text/template" id="tplSecCheck">
    <legend class="legend-report category-report form-jp">__category-jp__</legend>
    <legend class="legend-report category-report form-en" style="display:none">__category-en__</legend>
    <legend class="legend-report category-report form-vn" style="display:none">__category-vn__</legend>
    __checkboxs__
</script>

<script type="text/template" id="tplCheckbox">
    <label class="label-report form-jp"><input class="checkbox-report disabled-check" type="checkbox" name="Check" value="__id__" __checked__/ onClick="return false;">__detail-jp__</label>
    <label class="label-report form-en" style="display:none"><input class="checkbox-report disabled-check" type="checkbox" name="Check" value="__id__" __checked__ onClick="return false;"/>__detail-en__</label>
    <label class="label-report form-vn" style="display:none"><input class="checkbox-report disabled-check" type="checkbox" name="Check" value="__id__" __checked__ onClick="return false;"/>__checkcode__ - __detail-vn__</label>
</script>
<script type="text/template" id="tplRadio">
    <label class="label-report form-jp">
        <input type="radio" class="checkbox-report radio-report disabled-check" name="__name-jp__" value="__id__" __checked-jp__ onClick="return false;">__detail-jp__
    </label>
    <label class="label-report form-en" style="display:none">
        <input type="radio" class="checkbox-report radio-report disabled-check" name="__name-en__" value="__id__" __checked-en__ onClick="return false;">__detail-en__
    </label>
    <label class="label-report form-vn" style="display:none">
        <input type="radio" class="checkbox-report radio-report disabled-check" name="__name-vn__" value="__id__" __checked-vn__ onClick="return false;">__detail-vn__
    </label>
</script>


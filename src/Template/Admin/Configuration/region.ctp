<?php
    echo $this->Html->css('calendar/fullcalendar.css', ['block' => 'head-end']) . PHP_EOL;
    echo $this->Html->css('calendar/datepicker.css', ['block' => 'head-end']) . PHP_EOL;
    echo $this->Html->css('calendar/jquery-ui.css', ['block' => 'head-end']) . PHP_EOL;
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
        <h6 class="m-0 font-weight-bold text-primary float-left">Region</h6>
    </div>
    <div class="card-body">
        <div class="col-lg-9">

            <!-- Basic Card Example -->
            <div class="card  mb-4">
                <div class="card-header py-3">
                    <?php echo $this->Form->create(null, ['class' => 'user', 'id' => 'formRegion']); ?>
                        <div class=" row">
                            <div class="col-sm-5 mb-3 mb-sm-0">
                                <span style="font-size:15px; font-weight: 500; line-height: 30px;">Region Code</span>
                                <input type="text" maxlength="4" class="form-control form-control-user" name="RegionCode" id="RegionCode" placeholder="">
                                <p>(Code can not be more than 4 characters long)</p>
                            </div>
                            <div class="col-sm-5 mb-3 mb-sm-0">
                                <span style="font-size:15px; font-weight: 500; line-height: 30px;">Region Name</span>
                                <input type="text" maxlength="50" class="form-control form-control-user" name="RegionName" id="RegionName" placeholder="">
                                (Region Name can not be more than 50 characters long)
                            </div>
                            <div class="col-sm-2 mt-4">
                                <i class="fa fa-plus tron"></i><i class="fa fa-minus tron1"></i>
                            </div>
                        </div>
                    <input type="hidden" class="form-control form-control-user" name="ID" id="ID" placeholder="">
                    <?php echo $this->Form->end(); ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="serverDataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th class="w-td2">No</th>
                                <th class="w-td4">Code</th>
                                <th>Region</th>
                                <th class="w-td4">Number of Area</th>
                                <th class="w-td2"></th>
                            </tr>
                            </thead>
                            <tbody>
                        <?php foreach ($regions as $idx => $region) : ?>
                            <tr <?php if ($region->Deleted): ?>class="delete"<?php endif;?>>
                                <td class="text-center"><?=($idx+1)?></td>
                                <td target-id="<?=$region->ID?>"><?=$region->RegionID?></td>
                                <td><?php if (!$region->Deleted): ?><a href="javascript:void(0)" class="a-edit-click" target-id="<?=$region->ID?>"><?php endif;?><?=$region->Region?><?php if (!$region->Deleted): ?></a><?php endif;?></td>
                                <td class="text-right"><?=count($region->TBLMArea)?></td>
                                <td class="text-center">
                                    <?php if (!$region->Deleted): ?>
                                        <button type="button" data-toggle="tooltip" title="Edit" class="btn btn-info a-edit-click" target-id="<?= $region->ID ?>"><i class="far fa-edit"></i></button>
                                    <?php endif;?>
                                </td>
                            </tr>
                        <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<?php
echo $this->Html->script('calendar/moment.min.js', ['block' => 'scriptBottom']);
echo $this->Html->script('calendar/fullcalendar.js', ['block' => 'scriptBottom']);
echo $this->Html->script('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['block' => 'scriptBottom']);
echo $this->Html->script('jquery.ui.monthpicker.js', ['block' => 'scriptBottom']);
echo $this->Html->script('admin/configuration/region.js?v=' . date('YmdHis'), ['block' => 'scriptBottom']);
?>

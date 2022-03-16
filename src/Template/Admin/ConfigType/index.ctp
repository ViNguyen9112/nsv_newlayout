<style>
    #serverDataTable_filter {
        display: none;
    }

    .work-pre {
        word-break: break-word;
        white-space: pre-wrap;
        clear: both;
    }
</style>

  <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/font-awesome/4.0.0/css/font-awesome.css">
<?php    
    echo $this->Html->css('admin/color/jquery.simplecolorpicker.css', ['block' => 'head-end']) . PHP_EOL;
 ?>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">Report Type</h6>
        <button type="button" class="btn btn-success rounded-pill float-right" id="show-modal-config-type"><i class="fas fa-plus"></i> Add Type</button>
    </div>
   
    <div class="card-body pb-0">
        <div class="pt-1"></div>

        <div class="row ">
            <div class="col-md-12  mx-auto text-center form p-8">

                <form class="form-inline" action="#">
                    <table class="table table-borderless mb-0 p-0" style="width:auto">
                        <tbody>
                        <tr>
                             <th scope="row">
                                <div class="form-group" style="width:200px;padding-top: 8px">
                                    <label class="checkbox">
                                        <input  type="checkbox" name="showDeleted" class="checkDelete" value="1" />&nbsp;&nbsp;&nbsp;Show Deleted Type
                                    </label>
                                </div>
                            </th>
                           
                        </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body pb-0">
        <div class="pt-1"></div>

        <div class="row ">
            <div class="col-md-12  mx-auto text-center form p-8">
                <table class="table table-bordered" id="serverDataTable">
                    <thead>
                    <tr>
                        <th class="w-td2">No.</th>
                        <th class="w-td4">Type ID</th>                        
                        <th class="text-left">English</th>
                        <th class="text-left">Japanese</th>
                        <th class="text-left">Native language</th>
                        <th class="text-left">Color</th>
                        <th class="text-left">Icon</th>
                        <th class="w-td100"></th>
                    </tr>
                    </thead>
                    <tbody id="tblConfigType">
                    <!--datatable-->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- popup config type -->
<?php echo $this->element('Admin/popup_config_type'); ?>

<?php
    echo $this->Html->script('calendar/moment.min.js', ['block' => 'scriptBottom']);
    echo $this->Html->script('calendar/fullcalendar.js', ['block' => 'scriptBottom']);
    echo $this->Html->script('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['block' => 'scriptBottom']);
    echo $this->Html->script('jquery.ui.monthpicker.js', ['block' => 'scriptBottom']);
    echo $this->Html->script('admin/config_type/index.js?v=' . date('YmdHis'), ['block' => 'scriptBottom']);
    echo $this->Html->script('admin/config_type/jquery.simplecolorpicker.js?v=' . date('YmdHis'), ['block' => 'scriptBottom']);
?>

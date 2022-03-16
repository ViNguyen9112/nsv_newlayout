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
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">Report Detail</h6>
        <button type="button" class="btn btn-success rounded-pill float-right" id="show-modal-config-type"><i class="fas fa-plus"></i> Add Detail</button>
    </div>
        <div class="card-body pb-0">
        <div class="pt-1"></div>

        <div class="row ">
            <div class="col-md-12  mx-auto text-center form p-8">

                <form class="form-inline" action="#">
                    <table class="table table-borderless mb-0 p-0" style="width:auto">
                        <tbody>
                        <tr>
                            
                            <th scope="row" style="padding-right: 0px;">
                                <div class="form-group  mb-2">
                                    <div class="form-group  " style="width:300px">
                                        <span style="color:#000;margin-right:3px;font-size:14px; width:50px; text-align:left ">Type</span>
                                        <select name="" class="form-control" id="sType" style="width: 200px">
                                           <option value="">Please choose Type </option>
                                            <?php foreach($type_opt as $key => $value): ?>
                                                <option value="<?=$key?>"><?=$value?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </th>
                            <th scope="row" style="padding-right: 0px;">
                                <div class="form-group  mb-2">
                                    <div class="form-group  " style="width:400px">
                                        <span style="color:#000;margin-right:15px;font-size:14px; width:60px; text-align:left ">Category</span>
                                        <select name="" class="form-control " id="sCategory" style="width: 270px">
                                            <option value="">Please choose Type </option>
                                            <?php foreach($category_opt as $key => $value): ?>
                                                <option value="<?=$key?>"><?=$value?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </th>                            

                            <th scope="row">
                                <div class="form-group" style="width:200px;padding-top: 8px">
                                    <label class="checkbox">
                                        <input  type="checkbox" name="showDeleted" class="checkDelete" value="1" />&nbsp;&nbsp;&nbsp;Show Deleted Report
                                    </label>
                                </div>
                            </th>
                            <th scope="row">
                                <div class="form-group" style="width:200px">
                                    <button type="button" id="filterStaff" class="btn btn-primary"  style="width:70px;margin-right: 16px;height: 32px;line-height: 12px; display: none">
                                        Filter
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
    <div class="card-body pb-0">
        <div class="pt-1"></div>

        <div class="row ">
            <div class="col-md-12  mx-auto text-center form p-8">
            <table class="table table-bordered" id="serverDataTable">
                <thead>
                <tr>
                    <th class="w-td3">No.</th>
                    <th class="w-td2">Detail Code</th>                    
                    <th class="text-left">English</th>
                    <th class="text-left">Japanese</th>
                    <th class="text-left">Native language</th>
                    <th class="text-left">Category</th>
                    <th class="text-center">Sort</th>
                    <th class="w-td100"></th>
                </tr>
                </thead>
                <tbody id="tblConfigReport">
                <!--datatable-->
                </tbody>
            </table>
            </div>
        </div>
    </div>
</div>
<!-- popup config detail -->
<?php echo $this->element('Admin/popup_config_report'); ?>

<?php
echo $this->Html->script('calendar/moment.min.js', ['block' => 'scriptBottom']);
echo $this->Html->script('calendar/fullcalendar.js', ['block' => 'scriptBottom']);
echo $this->Html->script('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['block' => 'scriptBottom']);
echo $this->Html->script('jquery.ui.monthpicker.js', ['block' => 'scriptBottom']);
echo $this->Html->script('admin/config_report/index.js?v=' . date('YmdHis'), ['block' => 'scriptBottom']);
?>

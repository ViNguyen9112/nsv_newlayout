<style>
    #serverDataTable_filter {
        display: none;
    }
</style>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">Staff Management</h6>
        <button type="button" class="btn btn-success rounded-pill float-right" id="show-modal-staff"><i class="fas fa-plus"></i>Add</button>
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
                                        <span style="color:#000;margin-right:3px;font-size:14px; width:60px; text-align:left ">Search</span>
                                        <input type="text" class="form-control" name="search[value]" id="sKeyword" value="" size="10" style="width:220px">
                                    </div>
                                </div>
                            </th>
                            <th scope="row" style="padding-right: 0px;">
                                <div class="form-group  mb-2">
                                    <div class="form-group  " style="width:340px">
                                        <span style="color:#000;margin-right:3px;font-size:14px; width:60px; text-align:left ">Region</span>
                                        <select name="" class="form-control" id="sRegion" style="width:250px">
                                            <option value=""></option>
                                            <?php foreach($this->Common->getAreaByPosition() as $key => $area): ?>
                                                <option value="<?=$key?>"><?=$area?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </th>
                            <th scope="row" style="padding-right: 0px;">
                                <div class="form-group  mb-2">
                                    <div class="form-group  " style="width:400px">
                                        <span style="color:#000;margin-right:3px;font-size:14px; width:60px; text-align:left ">Area</span>
                                        <select id="sAreaID" class="form-control form-popup input-customer">
                                            <option></option>
                                            <?php foreach ($listArea as $each): ?>
                                                <option data-region="<?php echo $each->Region?>" value="<?php echo $each->AreaID?>"><?php echo '???' .$each->AreaID. '???' . $each->Name?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <th scope="row">
                                <div class="form-group" style="width:200px">
                                    <button type="button" id="filterStaff" class="btn btn-primary"  style="width:70px;margin-right: 16px;height: 32px;line-height: 12px; display: none">
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

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="serverDataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th class="w-td3 text-center">No.</th>
                    <th class="w-td2">StaffID</th>
                    <th>Name</th>
                    <th class="w-td2">Password</th>
                    <th class="w-td4">Position</th>
                    <th class="w-10a">Region</th>
                    <th class="w-td4">Area</th>
                    <th class="w-td1">Created At</th>
                    <th class="text-center w-button"></th>
                </tr>
                </thead>
                <tbody id="tblStaff">
                <!--datatable-->
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- temp values -->
<?php
    $col = 1;
    $dir = 'asc';
    if(isset($sort)){
        if(isset($sort['col']) && isset($sort['dir'])){
            if(strpos($sort['col'], 'ID') !== false){
                $col = 1;
            } else if(strpos($sort['col'], 'Name') !== false){
                $col = 2;
            } else if($sort['col'] == 'Area'){
                $col = 5;
            } else {
                $col = 1;
            }
            $dir = $sort['dir'];
        }

    }
?>
<input type="hidden" id="currIndexSort" value="<?php echo $col; ?>">
<input type="hidden" id="currDirSort" value="<?php echo $dir; ?>">

<!-- popups -->
<?php echo $this->element('Admin/popup_staff'); ?>
<?php echo $this->element('Admin/popup_qrcode'); ?>

<!-- script for staff -->
<?= $this->Html->script('admin/alsok/staff.js?v='. date('YmdHis'), ['block' => 'scriptBottom']) . PHP_EOL ?>

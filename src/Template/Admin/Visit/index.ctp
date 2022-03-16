<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">Visit Log</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="serverDataTable" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <td class="border-right-0 border-top-0 border-bottom-0"></td>
                    <td class="border-right-0 border-top-0 border-bottom-0"></td>
<!--                    <td class="border-0">-->
<!--                        <select name="" class="form-control" id="sRegion" style="width: 130px">-->
<!--                            <option value=""></option>-->
<!--                            --><?php //foreach($this->Common->getAreaByPosition() as $key => $area): ?>
<!--                                <option value="--><?//=$key?><!--">--><?//=$area?><!--</option>-->
<!--                            --><?php //endforeach; ?>
<!--                        </select>-->
<!--                    </td>-->
                    <td class="border-0">
                        <?php
                            echo $this->Form->select(
                                'sArea',
                                $areas,
                                ['class' => "sArea w-100", "empty" => true]
                            );
                        ?>
                    </td>
                    <td class="border-0">
                        <?php
                        echo $this->Form->select(
                            'sCustomerID',
                            $customerIds,
                            ['class' => "sCustomerID w-100", "empty" => true]
                        );
                        ?>
                    </td>
                    <td class="border-0"></td>
                    <td class="border-0"></td>
                    <td class="border-0">
                        <?php
                        echo $this->Form->select(
                            'sStaffID',
                            $staffIds,
                            ['class' => "sStaffID w-150", "empty" => true]
                        );
                        ?>
                    </td>
                    <td class="border-0"></td>
                    <td class="border-left-0 border-top-0 border-bottom-0">
                        <?php
                        echo $this->Form->select(
                            'sTimeVisit',
                            $timeVisits,
                            ['class' => "sTimeVisit w-100", "empty" => true]
                        );
                        ?>
                    </td>
                </tr>
                <tr>
                    <th class="w-5 text-center">No</th>
                    <th class="w-15">Region</th>
                    <th class="w-15">Area</th>
                    <th class="w-10">Customer ID</th>
                    <th>Customer Name</th>
                    <th class="w-5">Number of visit</th>
                    <th class="w-10">Staff ID</th>
                    <th class="w-12">Staff Name</th>
                    <th class="w-12">Last Visit</th>
                </tr>
                </thead>
                <tbody id="tblVisitor">
                <!--dataTable-->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- temp values -->
<?php
    $col = 7;
    $dir = 'desc';
    if(isset($sort)){
        if(isset($sort['col']) && isset($sort['dir'])){
            if($sort['col'] == 'Area'){
                $col = 1;
            } else if($sort['col'] == 'CustomerID'){
                $col = 2;
            } else if($sort['col'] == 'Customer Name'){
                $col = 3;
            } else if($sort['col'] == 'StaffID'){
                $col = 5;
            } else if($sort['col'] == 'Staff Name'){
                $col = 6;
            } else {
                $col = 7;
            }
            $dir = $sort['dir'];
        }

    }
?>
<input type="hidden" id="currIndexSort" value="<?php echo $col; ?>">
<input type="hidden" id="currDirSort" value="<?php echo $dir; ?>">

<?php echo $this->element('Admin/popup_view_image'); ?>
<!-- popup for visit -->
<?php echo $this->element('Admin/popup_visit'); ?>

<!-- popup for report -->
<?php echo $this->element('Admin/popup_report'); ?>

<!-- popup for face -->
<?php echo $this->element('Admin/popup_face_admin'); ?>

<!-- script for customer -->
<?= $this->Html->script('admin/alsok/visit.js?v='. date('YmdHis'), ['block' => 'scriptBottom']) . PHP_EOL ?>

<script type="text/template" id="tplSecCheck">
    <legend class="legend-report category-report form-jp">__category-jp__</legend>
    <legend class="legend-report category-report form-en" style="display:none">__category-en__</legend>
    <legend class="legend-report category-report form-vn" style="display:none">__category-vn__</legend>
    __checkboxs__
</script>

<script type="text/template" id="tplCheckbox">
    <label class="label-report form-jp"><input class="checkbox-report" type="checkbox" name="Check" value="__id__" __checked__/>__checkcode__ - __detail-jp__</label>
    <label class="label-report form-en" style="display:none"><input class="checkbox-report" type="checkbox" name="Check" value="__id__" __checked__/>__checkcode__ - __detail-en__</label>
    <label class="label-report form-vn" style="display:none"><input class="checkbox-report" type="checkbox" name="Check" value="__id__" __checked__/>__checkcode__ - __detail-vn__</label>
</script>

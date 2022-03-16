<?php
echo $this->Html->css('admin/style_schedule.css', ['block' => 'head-end']) . PHP_EOL;
?>

<!-- temp vars -->
<?php if(isset($timecard)): ?>
    <input type="hidden" id="idTimecard" value="<?php echo $timecard->TimeCardID; ?>">
    <input type="hidden" id="customerID" value="<?php echo $timecard->CustomerID; ?>">
<?php endif; ?>

<div class="form-group m-3">
    <div id='calendar'></div>
</div>
<?php if (!isset($roll)) : ?>
    <div class="row m-3 div-memo">
        <?php foreach ($typeReports as $typeReport) : ?>
            <div class="note-item" style="background: <?= $typeReport['color'] ?>"><?= $typeReport['name'] ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<div class="modal fade" id="calendarModal" tabindex="-1" role="dialog" aria-labelledby="application-leave-modal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color:#7386D5 !important">Please choose Staff</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalBody" style="color:#7386D5 !important">
                <div class="pt-1"></div>
                <?php if (isset($roll) && $roll) : ?>
                    <form class="form-inline" action="#">
                        <div class="form-group w-75 mb-2 mr-3">
                            <select id="multiple-select" name="staffIds" class="form-control w-100" place>
                                <option value="">Please choose staff</option>
                                <?php foreach ($staffIds as $staffId => $value) : ?>
                                    <option value="<?= $staffId ?>" <?php if (@$params['staffIds'] == $staffId) echo "selected"; ?>><?= $value ?></option>
                                <?php endforeach ?>
                            </select>
                            <input type="hidden" class="form-control" id="default_datepicker" value="<?= date('Y-m') ?>">
                        </div>
                        <button type="button" onclick="return showCalendarStaff()" class="btn btn-primary mb-2">
                            Filter
                        </button>
                    </form>
                <?php else : ?>
                    <input type="hidden" class="form-control" id="default_datepicker" value="<?= date('Y-m') ?>">
                    <input type="hidden" class="form-control" id="multiple-select" value="<?= $staff['StaffID'] ?>">
                <?php endif; ?>
                <input type="hidden" class="form-control" id="roll" value="<?= @$roll ?>">
            </div>
        </div>
    </div>
</div>

<?php if (isset($roll) && $roll) : ?>
    <!-- popup for event -->
    <?php echo $this->element('Admin/popup_event_admin'); ?>
    <!-- popup for map -->
    <?php echo $this->element('Admin/popup_map'); ?>
    <!-- popup for gps -->
    <?php echo $this->element('Admin/popup_face_admin'); ?>
<?php else : ?>
    <!-- popup for event -->
    <?php echo $this->element('Admin/popup_event'); ?>
    <!-- popup for map -->
    <?php echo $this->element('Admin/popup_map'); ?>
    <!-- popup for gps -->
    <?php echo $this->element('Admin/popup_face'); ?>
    <!-- popup view image -->
    <?php echo $this->element('Mypage/popup_view_image'); ?>
<?php endif; ?>

<?php if (isset($roll) && $roll) : ?>

    <script async defer src="//maps.googleapis.com/maps/api/js?key=<?= MAP_KEY ?>&libraries=&v=weekly"></script>
    <?php echo $this->Html->script('sweetalert.min.js', ['block' => 'scriptBottom']); ?>
    <?php echo $this->Html->script('calendar/index.js?v=' . date('YmdHis'), ['block' => 'scriptBottom']); ?>
    <?php echo $this->Html->script('calendar/common.js?v=' . date('YmdHis'), ['block' => 'scriptBottom']); ?>
<?php else : ?>
    <?php echo $this->Html->script('sweetalert.min.js', ['block' => 'body-end']); ?>
    <?php echo $this->Html->script('calendar/attached-picture-calendar.js?v=' . date('YmdHis'), ['block' => 'body-end']); ?>
    <?php echo $this->Html->script('calendar/mobile-index.js?v=' . date('YmdHis'), ['block' => 'body-end']); ?>
    <?php echo $this->Html->script('calendar/common.js?v=' . date('YmdHis'), ['block' => 'body-end']); ?>

<?php endif; ?>


<!-- template field check report -->
<script type="text/template" id="tplSecCheck">
    <legend class="legend-category">__category__</legend>
    __checkboxs__
</script>

<script type="text/template" id="tplCheckbox">
    <label><input type="checkbox" class="checkbox-report" name="Check" value="__id__" />__detail__</label>
</script>

<script type="text/template" id="tplRadio">
    <label><input type="radio" class="checkbox-report" name="Check__idCat__" value="__id__" />__detail__</label>
</script>
<!-- end -->

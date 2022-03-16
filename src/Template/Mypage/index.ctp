<style>
    .icon-logout {
        padding:0;
        background:#c61111;
        border-radius:0 20px 20px 0;
        line-height: 33px;
    }
</style>

<div class="loading-screen">
  <div class="loader-n">
    <div class="dot"></div>
    <div class="dot"></div>
    <div class="dot"></div>
  </div>
</div>

<div style="height:38vh">
    <div >
        <div class="account row">
            <div class="col-7 staff" style="line-height: 17px">
                <strong><?php echo $staff->StaffID; ?></strong><br/>
                <strong><?php echo $staff->Name; ?></strong>
            </div>
            <div class="col-4" style="padding:0; line-height: 17px">
                <strong><span id="today"></span></strong><br/>
                <strong><span id="digital-clock"></span></strong>
            </div>
            <div class="col-1 icon-logout">
                <a style="color:#fff" href="<?php echo $this->Url->build('/', true); ?>users/logout">
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center align-items-center">
        <div class="sec-video d-flex justify-content-center align-items-center">
            <!-- <div class="col-2" style="padding:0;text-align:right"><img class="icon-dot" src="<?php echo $this->Url->build('/', true); ?>img/dot_cam.png" /></div> -->
            <div class="col-12" style="padding:0"><video id="video" autoplay muted playsinline></video></div>
        </div>
    </div>
</div>
<!-- FIXME: SEC BOTTOM-->
<div class="sec-bottom row d-flex justify-content-center align-items-center">
    <!-- TODO: BUTTON 1: current location  -->
    <!-- <div class="bottom-location col-10">
        <button class="btn btn-success btn-current-location" id="CurrentLocation"><i class="fas fa-map-marker-alt"></i> My current location</button>
    </div>
    <div class="col-12 row d-flex justify-content-center" style="height:1px;margin-bottom:1vh">
        <div class="col-12" style="border-top:1px solid #fff;height:1px">&ensp;</div>
    </div> -->
    <div class="col-12 row mb-1" style="text-align: center">
        <span class="col-12 label-drop"><?php echo $data_language['area']; ?>:</span>
        <select class="dropdown-index col-12" id="Area">
            <option value="-1" id="onloadArea"></option>
            <?php foreach($listArea as $item): ?>
            <option value="<?php echo $item['AreaID']; ?>" <?php if(isset($AreaID) && $item['AreaID'] == $AreaID) echo "selected"; ?> ><?php echo $item['Name']; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12 row" style="text-align: center;">
        <span class="col-12 label-drop"><?php echo $data_language['customer']; ?>:</span>
        <select class="dropdown-index col-12" id="CustomerName">
            <!-- <option value="-1">Customer Name</option> -->
            <?php if(isset($listCustomer)):?>
                <?php foreach($listCustomer as $item): ?>
                    <option value="<?php echo $item['CustomerID']; ?>" <?php if(isset($CustomerID) && $item['CustomerID'] == $CustomerID) echo "selected"; ?> ><?php echo $item['Name']; ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>
    <div class="col-12 row d-flex justify-content-center" style="height:1px;margin-top:1vh;margin-bottom:1vh">
        <div class="col-12" style="border-top:1px solid #fff;height:1px">&ensp;</div>
    </div>
    <!-- span show time check-in -->
    <div class="col-12 row d-flex justify-content-center mb-2" style="text-align: center;">
        <div class="row col-sm-12 col-md-8 col-lg-8" style="font-family: 'Prompt';font-size: 4.5vw">
            <button class="btn-sign col-6" id="CheckIn"><?php echo $data_language['check_in']; ?></button>
            <div class="col-5" style="text-align: left">
                <span id="TimeCheckin">
                    <?php if(isset($timecard)): ?>
                        <?php echo $timecard->TimeIn; ?>
                    <?php endif; ?>
                </span>
            </div>
        </div>
    </div>
    <!-- span show time check-out -->
    <div class="col-12 row d-flex justify-content-center" style="text-align: center;">
        <div class="row col-sm-12 col-md-8 col-lg-8" style="font-family: 'Prompt';font-size: 4.5vw">
            <button class="btn-sign check-out col-6" id="CheckOut"><?php echo $data_language['check_out']; ?></button>
            <div class="col-5" style="text-align: left">
                <span id="TimeCheckout">
                    <?php if(isset($timecard) && $timecard->TimeOut): ?>
                        <?php echo $timecard->TimeOut; ?>
                    <?php endif; ?>
                </span>
            </div>
            <input type="hidden" id="timecardIDCheckout">
        </div>
    </div>
    <div class="col-12 row d-flex justify-content-center" style="height:1px;margin-top:1vh">
        <div class="col-12" style="border-top:1px solid #fff;height:1px">&ensp;</div>
    </div>
    <div class="col-12 row d-flex justify-content-between" style="text-align: center;margin-bottom:1.5vh">
		<div class="col-6" style="padding:0; padding-right:5px">
			<a class="btn btn-success btn-report" href="<?php echo $this->Url->build('/overtime', true); ?>"><i class="far fa-clock"></i> <?php echo $data_language['over_time']; ?></a>
		</div>
		<div class="col-6" style="padding:0; padding-left:5px">
			<a class="btn btn-primary btn-calendar" href="<?php echo $this->Url->build('/annual_leave', true); ?>"><i class="fas fa-calendar"></i> <?php echo $data_language['annual_leave']; ?></a>
		</div>
        <div class="col-6" style="padding:0; padding-right:5px">
            <button class="btn btn-success btn-report" id="Report"><i class="far fa-edit"></i> <?php echo $data_language['report']; ?></button>
        </div>
        <div class="col-6" style="padding:0; padding-left:5px">
            <button class="btn btn-primary btn-calendar" id="WorkingCalendar"><i class="fas fa-tasks"></i> <?php echo $data_language['working_calendar']; ?></button>
        </div>
    </div>
</div>

<!-- temp vars -->
<?php if(isset($timecard)): ?>
    <input type="hidden" id="idTimecard" value="<?php echo $timecard->TimeCardID; ?>">
    <input type="hidden" id="customerID" value="<?php echo $timecard->CustomerID; ?>">
    <input type="hidden" id="customerName" value="<?php echo $customerName; ?>">
    <input type="hidden" id="timeIn" value="<?php echo $timecard->TimeIn; ?>">
    <input type="hidden" id="checkedOut" value="<?php echo $CheckedOut; ?>">
<?php endif; ?>
<input type="hidden" id="currentCoord" value="none">
<p style="display: none;" id="btnInsertCheckin"></p>
<p style="display: none;" id="btnInsertCheckout"></p>

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


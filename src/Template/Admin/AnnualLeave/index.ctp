<link rel="stylesheet"
      href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.standalone.min.css"
      integrity="sha512-TQQ3J4WkE/rwojNFo6OJdyu6G8Xe9z8rMrlF9y7xpFbQfW5g8aSWcygCQ4vqRiJqFsDsE1T6MoAOMJkFXlrI9A=="
      crossorigin="anonymous"/>
<style>
    #serverDataTable_filter {
        display: none;
    }
</style>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">Annual Leave Management</h6>
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
									<div class="form-group" style="width:200px">
										<span style="color:#000;margin-right:3px;font-size:14px; width:50px; text-align:left">From</span>
										<input type="text" class="form-control" value="<?= @$params['datepicker'] ?>" name="datepicker" id="datepicker_date" size="10" placeholder="Date" style="width:120px">
										<input type="hidden" class="form-control" id="default_datepicker" value=""><i class="far fa-calendar-alt" style=" position: relative; right: 30px;font-size: 14px;color: #000;"></i>
									</div>
								</th>
								<th scope="row" style="padding-right: 0px;">
									<div class="form-group" style="width:175px">
										<span style="color:#000;margin-right:3px;font-size:14px; width:30px;; text-align:left">To</span>
										<input type="text" class="form-control" value="<?= @$params['datepicker'] ?>" name="datepicker" id="datepicker_date_to" size="10" placeholder="Date" style="width:120px">
										<input type="hidden" class="form-control" id="default_to_datepicker" value=""><i class="far fa-calendar-alt" style=" position: relative; right: 30px;ont-size: 14px;color: #000;"></i>
									</div>
								</th>
								<th scope="row" style="padding-right: 0px;">
									<div class="form-group" style="width:200px">
										<button type="button" id="filterStaff" class="btn btn-primary"  style="width:70px;margin-right: 16px;height: 32px;line-height: 12px;">
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
                    <th>Position</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Total</th>
					<th>Reason</th>
					<th>Area Leader</th>
                    <th>Manager</th>
                </tr>
                </thead>
                <tbody id="tblCustomer">
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
                $col = 3;
            } else {
                $col = 1;
            }
            $dir = $sort['dir'];
        }

    }
?>
<input type="hidden" id="currIndexSort" value="<?php echo $col; ?>">
<input type="hidden" id="currDirSort" value="<?php echo $dir; ?>">

<!-- popup for customer -->
<?php echo $this->element('Admin/popup_customer'); ?>
<?php echo $this->element('Admin/popup_map'); ?>

<!-- script for customer -->
<?php
    echo $this->Html->script('calendar/moment.min.js', ['block' => 'scriptBottom']);
    echo $this->Html->script('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js', ['block' => 'scriptBottom']);
?>
<?= $this->Html->script('admin/alsok/annualleave.js?v='. date('YmdHis'), ['block' => 'scriptBottom']) . PHP_EOL ?>

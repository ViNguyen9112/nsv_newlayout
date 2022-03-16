<?php 
echo $this->Html->css('picker/picker.min.css').PHP_EOL;
echo $this->Html->script('picker/picker.min.js'); 
?>
<div class="box-ot">
	<div class="bg-white">
		<h2 class="text-center mb-3"><?php echo $data_language['_overtime_title'][$lang]; ?></h2>
		<hr  class="mb-0">
		<div class="card-body" id="form_input">
			<form autocomplete="off" class="form" role="form">
				<div class="form-group">
					<label for="inputPasswordOld"><?php echo $data_language['_overtime_name'][$lang]; ?></label> 
					<span class="span"><?php echo $staff->StaffID; ?> <?php echo $staff->Name; ?></span>
				</div>
				<div class="form-group datepicker_form">
					<label for="inputPasswordNew"><?php echo $data_language['_overtime_date'][$lang]; ?></label> 
					<input type="text" name="StartDate" class="form-control datepicker" placeholder="____/__/__" />
					<i class="fa fa-calendar" aria-hidden="true"></i>
				</div>
				<div class="form-group" style="position:relative">
					<label for="inputPasswordNewVerify"><?php echo $data_language['_overtime_from_time'][$lang]; ?></label> 
					<input name="FromTime" class="form-control js-time-picker" id="FromTime" type="text" placeholder="__:__" disabled> 
					<i class="fa fa-clock" aria-hidden="true" style="position: absolute;top: 12px;right: 22px;font-size: 14px;"></i>
				</div>
				<div class="form-group" style="position:relative">
					<label for="inputPasswordNewVerify"><?php echo $data_language['_overtime_to_time'][$lang]; ?></label> 
					<input name="ToTime" class="form-control js-time-picker" id="ToTime" type="text" placeholder="__:__" disabled> 
					<i class="fa fa-clock" aria-hidden="true" style="position: absolute;top: 12px;right: 22px;font-size: 14px;"></i>
				</div>
				<div class="form-group">
					<label for="inputPasswordOld"><?php echo $data_language['_overtime_total'][$lang]; ?></label> 
					<span class="span" id="Estimate"></span>
				</div>
				<hr >
				<div class="text-center">
					<button class="btn-ot" id="PreviewForm" type="button" disabled><?php echo $data_language['_overtime_preview'][$lang]; ?></button>
				</div>
				<div class="clearfix"></div>
			</form>
		</div>
		<div class="card-body d-none" id="form_preview">
			<div class="form-group">
				<label for="inputPasswordOld"><?php echo $data_language['_overtime_name'][$lang]; ?></label> 
				<span class="span"><?php echo $staff->StaffID; ?> <?php echo $staff->Name; ?></span>
			</div>
			<div class="form-group">
				<label for="inputPasswordNew"><?php echo $data_language['_overtime_date'][$lang]; ?></label> 
				<span class="span" id="StartDatePreview"></span>
			</div>
			<div class="form-group">
				<label for="inputPasswordNewVerify"><?php echo $data_language['_overtime_time'][$lang]; ?></label> 
				<span class="span" id="TimePreview"></span>
			</div>
			<div class="form-group">
				<label for="inputPasswordOld"><?php echo $data_language['_overtime_total'][$lang]; ?></label> 
				<span class="span" id="EstimatePreview"></span>
			</div>
			<hr>
			<div class="text-center ">
				<button class="btn-ot" id="SubmitForm"><?php echo $data_language['_overtime_submit'][$lang]; ?></button><button class="btn-ot" id="HideForm" type="button"><?php echo $data_language['_overtime_back'][$lang]; ?></button>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

<div class="box-ot">
	<div class="bg-white">
		<h2 class="text-center mb-3"><?php echo $data_language['_annualleave_title'][$lang]; ?></h2>
		<hr  class="mb-0">
		<div class="card-body" id="form_input">
			<form autocomplete="off" class="form" role="form">
				<div class="form-group">
					<label for="inputPasswordOld"><?php echo $data_language['_overtime_name'][$lang]; ?></label> 
					<span class="span"><?php echo $staff->StaffID; ?> <?php echo $staff->Name; ?></span>
				</div>
				<div class="form-group display-f">
					<label class="line-h" for="inputPasswordOld"><?php echo $data_language['_annualleave_type'][$lang]; ?></label> 
					<div class="form-check-label display-f">
						<div style="width:60px">
							<input name="Type" class="form-check-input" value="al" type="radio"> 
							<span>AL</span>
						</div>
						<div class="ml-4" style="width:60px">
							<input name="Type" class="form-check-input" value="ul" type="radio"> 
							<span>UL</span>
						</div>
					</div>
				</div>
				<div class="form-group datepicker_form">
					<label for="inputPasswordNew"><?php echo $data_language['_overtime_from_time'][$lang]; ?></label> 
					<input type="text" name="FromDate" class="form-control datepicker" placeholder="YYYY/MM/DD" />
					<i class="fa fa-calendar" aria-hidden="true"></i>
				</div>
				<div class="form-group datepicker_form">
					<label for="inputPasswordNew"><?php echo $data_language['_overtime_to_time'][$lang]; ?></label> 
					<input type="text" name="ToDate" class="form-control datepicker" placeholder="YYYY/MM/DD" disabled />
					<i class="fa fa-calendar" aria-hidden="true"></i>
				</div>
				<div class="form-group">
					<label for="inputPasswordOld"><?php echo $data_language['_overtime_total'][$lang]; ?></label> 
					<span class="span" id="Estimate"></span>
				</div>
				<div class="form-group">
					<label class="line-h" for="inputPasswordOld" style="width:100% !important"><?php echo $data_language['_annualleave_reason'][$lang]; ?> (<span id="CountText">0</span>/500)</label> <br>
					<textarea name="Reason" class="form-control w-100" id="complexExampleMessage" rows="3" maxlength="500"></textarea>                   
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
				<label for="inputPasswordNew"><?php echo $data_language['_annualleave_type'][$lang]; ?></label> 
				<span class="span" id="TypePreview"></span>
			</div>
			<div class="form-group">
				<label for="inputPasswordNew"><?php echo $data_language['_overtime_from_time'][$lang]; ?></label> 
				<span class="span" id="FromDatePreview"></span>
			</div>
			<div class="form-group">
				<label for="inputPasswordNewVerify"><?php echo $data_language['_overtime_to_time'][$lang]; ?></label> 
				<span class="span" id="ToDatePreview"></span>
			</div>
			<div class="form-group">
				<label for="inputPasswordNewVerify"><?php echo $data_language['_overtime_total'][$lang]; ?></label>
				<span class="span" id="EstimatePreview"></span>                 
			</div>
			<div class="form-group">
				<label for="inputPasswordNewVerify"><?php echo $data_language['_annualleave_reason'][$lang]; ?></label>
				<span class="span" id="ReasonPreview"></span>                 
			</div>
			<hr>
			<div class="text-center ">
				<button class="btn-ot" id="SubmitForm"><?php echo $data_language['_overtime_submit'][$lang]; ?></button><button class="btn-ot" id="HideForm" type="button"><?php echo $data_language['_overtime_back'][$lang]; ?></button>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>